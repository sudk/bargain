<?php

/**
 * 商品杀价
 *
 * @author sudk
 */
class GoodsController extends MobileBaseController
{

    public function actionIndex(){
        $id=$_POST['id'];
        if($id){
            $model=Goods::GetTheActiveOne($id);
        }else{
            $model=Goods::GetTheActiveOne();
        }
        $this->render('index',array('model'=>$model));
    }

    public function actionGenerate(){
        $id=$_POST['id'];
        $number=$_POST['number'];
        $msg['status']=0;
        $msg['desc']='生成链接成功';
        do{
            if(!is_numeric($number)||strlen($number)!=11){
                $msg['status']=-1;
                $msg['desc']='请输入正确的手机号';
                break;
            }
            $l_id=$id.Yii::app()->params['split'].$number;
            $l=Yii::app()->fcache->get($l_id);
            if($l){
                $msg['l']=$l;
                break;
            }
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
            $msg['l']=$l;
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
            $t=time();
            $ot=$_SESSION['t'];
            if(($t-$ot)<60){
                $msg['status']=-1;
                $msg['desc']='亲，你发的短信太频烦了，喝杯荼休息一会吧！';
                break;
            }
            $_SESSION['number']=$n;
            $_SESSION['t']=time();
            $captcha=rand(100000,999999);
            //$captcha=100000;
            Utils::SendMsg($n,$captcha);
            Yii::app()->fcache->set($n,$captcha,60*5);
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
            $rs=$bargain_log->log($id,$s_n,$n);
            if($rs){
                if(!$bargain_price->reduce($id,$s_n)){
                    $msg['status']=-3;
                    $msg['desc']='亲，砍价失败，请重试！';
                    break;
                }
            }else{
                $msg['status']=-4;
                $msg['desc']='亲，砍价失败，请重试！';
                break;
            }

        }while(false);
        print_r(json_encode($msg));
    }

    public function actionBargains(){
        $id=$_POST['id'];
        $s_n=$_POST['s_n'];
        $args['goods_id']=$id;
        $args['uid']=$s_n;
        $rs=BargainLog::queryList(0,10,$args);
        $rows=$rs['rows'];
        $str="<li>没有砍价记录，快快去请小伙伴们帮忙</li>";
        $price=false;
        if($rows){
            $str="";
            foreach($rows as $row){
                $bargain_id=$row['bargain_id'];
                $reduce=number_format($row['reduce_price']/100,2);
                $record_time=date("y-m-d H:i",strtotime($row['record_time']));
                $str.="<li>{$bargain_id} ￥-{$reduce} {$record_time}</li>";
            }
            if($rs['total_num']>10){
                $str.="<li onclick='go_log()'>查看更多</li>";
            }
            $b_price=new BargainPrice();
            $price=$b_price->getPrice($id,$s_n);
            $price['price_now']="￥".number_format($price['price_now']/100,2);
            $price['price_pass']="原价".number_format($price['price_pass']/100,2);
        }
        $msg['list']=$str;
        $msg['price']=$price;
        print_r(json_encode($msg));
    }

}