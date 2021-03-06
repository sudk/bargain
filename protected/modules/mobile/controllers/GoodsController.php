<?php

/**
 * 商品杀价
 *
 * @author sudk
 */
class GoodsController extends MobileBaseController
{

    public function actionIndex(){
        //$id=$_REQUEST['id'];
        $id="g54a39d2d99f07";
        if($id){
            $model=Goods::GetTheActiveOne($id);
        }else{
            $model=Goods::GetTheActiveOne();
        }
        Yii::app()->name=$model['name'];
        $this->render('index',array('model'=>$model));
    }

    public function actionList(){
        $rows=Goods::GetActive();
        $this->render("list",array('rows'=>$rows));
    }

    public function actionGenerate(){
        $id=$_POST['id'];
        $number=$_POST['number'];
        $qrcode=$_POST['qrcode'];
        $msg['status']=0;
        $msg['desc']='生成链接成功';
        do{
            if(!is_numeric($number)||strlen($number)!=11){
                $msg['status']=-1;
                $msg['desc']='请输入正确的手机号';
                break;
            }
            $l_id=$id.Yii::app()->params['split'].$number;
            $link=Yii::app()->fcache->get($l_id);
            $rs=false;
            if($link){
                $msg['l']=$link;
            }else{
                $l=uniqid("l");
                $link=Yii::app()->params['base_host']."?l=".$l;
                Yii::app()->fcache->set($l_id,$link);
                Yii::app()->fcache->set($l,$l_id);
                $bargainPrice=new BargainPrice();
                $rs=$bargainPrice->firstAdd($id,$number);
                if(!$rs){
                    $msg['status']=-2;
                    $msg['desc']='生成链接失败';
                    break;
                }
                $msg['l']=$link;
            }
            if($qrcode){
                Yii::import('ext.qrcode.QRCode');
                $code=new QRCode($link);
                $n=uniqid("qr");
                $img=Yii::app()->params['upload_file_path']."/qrcode/{$n}.png";
                $code->create($img);
                $msg['img']=$img;
                $is_time_out=Yii::app()->fcache->get($l_id."_pc");
                if(!$is_time_out&&$rs){
                    $m="【网上砍价】尊敬的客户：您的砍价链接：{$link}，请点击使用。";
                    Utils::SendMsg($number,$m);
                    Yii::app()->fcache->set($l_id."_pc",true,60);
                    $msg['desc']="分享链接已经发送到你的手机上。";
                }
            }
        }while(false);

        print_r(json_encode($msg));
    }

    public function actionLink(){
        $id=$_POST['id'];
        $s_n=$_POST['s_n'];
        $msg['status']=0;
        $msg['desc']='成功';
        $l_id=$id.Yii::app()->params['split'].$s_n;
        $l=Yii::app()->fcache->get($l_id);
        if($l){
            $msg['l']=$l;
        }else{
            $msg['status']=-1;
            $msg['desc']='链接不存在';
        }
        print_r(json_encode($msg));
    }

    public function actionPrice(){
        $id=$_POST['id'];
        $number=$_POST['number'];
        $b_price=new BargainPrice();
        $price=$b_price->getPrice($id,$number);
        if($price){
            $msg['status']=0;
            $msg['desc']='成功';
            $msg['price']=$price;
        }else{
            $msg['status']=-1;
            $msg['desc']='失败';
        }
        print_r(json_encode($msg));
    }

    public function actionCaptcha(){
        $id=$_POST['id'];
        $s_n=$_POST['s_n'];
        $n=$_POST['number'];
        $msg['status']=0;
        $msg['desc']='验证码已经发送到您的手机上';
        do{

            if(!is_numeric($n)||strlen($n)!=11){
                $msg['status']=-1;
                $msg['desc']='请输入正确的手机号';
                break;
            }
            if($s_n==$n){
                $msg['status']=-2;
                $msg['desc']='亲，不能自己给自己砍价';
                break;
            }
            $bargain_log=new BargainLog();
            $is_log=$bargain_log->hasLog($id,$s_n,$n);
            if($is_log){
                $msg['status']=-3;
                $msg['desc']='亲，你已经砍过价了';
                break;
            }
            $model = BargainPrice::model()->find("goods_id='{$id}' and uid='{$s_n}'");
            if($model&&$model->price<=8000){
                $msg['status']=-4;
                $msg['desc']='该客户已经完成砍价。';
                break;
            }
            $t=time();
            $ot=$_SESSION['t'];
            if(($t-$ot)<60){
                $msg['status']=-1;
                $msg['desc']='亲，短信发的太频繁了，喝杯荼休息一会吧！';
                break;
            }
            $_SESSION['number']=$n;
            $_SESSION['t']=time();
            $captcha=rand(100000,999999);
            //$captcha=100000;
            $m="【网上砍价】您砍价的验证码是：{$captcha}";
            Utils::SendMsg($n,$m);
            Yii::app()->fcache->set($n,$captcha,60*10);
        }while(false);
        print_r(json_encode($msg));
    }
    public function actionCheck(){
        $id=$_POST['id'];
        $s_n=$_POST['s_n'];
        $captcha=$_POST['captcha'];
        $msg['status']=0;
        $msg['desc']='砍价成功';
        do{
            $n=$_SESSION['number'];
            $c=Yii::app()->fcache->get($n);
            if($captcha!=$c){
                $msg['status']=-1;
                $msg['desc']='验证码错误！';
                $msg['n']=$n;
                break;
            }
            Yii::app()->fcache->delete($n);
            $goods=Goods::model()->findByPk($id);
            if(!$goods){
                $msg['status']=-5;
                $msg['desc']='商品不存在';
                break;
            }
            $start_time=strtotime($goods->start_time);
            $end_time=strtotime($goods->end_time);
            $now=time();
            if($now<$start_time){
                $msg['status']=-6;
                $msg['desc']='亲，砍价时间还不到';
                break;
            }
            if($now>$end_time){
                $msg['status']=-7;
                $msg['desc']='亲，砍价时间已经过了';
                break;
            }
            $bargain_log=new BargainLog();
            $is_log=$bargain_log->hasLog($id,$s_n,$n);
            if($is_log){
                $msg['status']=-2;
                $msg['desc']='亲，你已经砍过价了';
                break;
            }
            $bargain_price=new BargainPrice();
            $rs=$bargain_price->reduce($id,$s_n);
            if($rs['status']!=0){
                $msg=$rs;
                break;
            }
            $bargain_log->log($id,$s_n,$n,$rs['reduce']);
            break;

        }while(false);
        print_r(json_encode($msg));
    }

    public function actionBargains(){
        $id=$_POST['id'];
        $s_n=$_POST['s_n'];
        $is_g=$_POST['is_g'];
        $args['goods_id']=$id;
        $args['uid']=$s_n;
        $rs=BargainLog::queryList(0,10,$args);
        $rows=$rs['rows'];
        $str="<li>没有砍价记录，快快去请小伙伴们帮忙</li>";
        $price=false;
        if($rows){
            $str="";
            foreach($rows as $row){
                $bargain_id=Utils::HiddenPhone($row['bargain_id']);
                $reduce=number_format($row['reduce_price']/100,2);
                $record_time=date("y-m-d H:i",strtotime($row['record_time']));
                $str.="<li>{$bargain_id} ￥-{$reduce} <span class='pull-right'>{$record_time}</span></li>";
            }
            if($rs['total_num']>10){
                $str.="<li onclick='go_log()'>查看更多</li>";
            }
            $b_price=new BargainPrice();
            $price=$b_price->getPrice($id,$s_n);
            if(intval($price['price_now'])<=8000){
                if($is_g){
                    $price['price_success']="砍价成功，请到营业厅办理业务";
                }else{
                    $price['price_success']="砍价成功，谢谢大家";
                }

            }
            $price['price_now']="￥".number_format($price['price_now']/100,2);
            $price['price_pass']="原价".number_format($price['price_pass']/100,2);
        }
        $msg['list']=$str;
        $msg['price']=$price;
        print_r(json_encode($msg));
    }

    public function actionDesc(){
        $id=$_GET['id'];
        $model=Goods::GetTheActiveOne($id);
        $this->render('rule',array('model'=>$model));
    }

}