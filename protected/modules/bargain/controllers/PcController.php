<?php

/**
 * 操作员管理
 *
 * @author sudk
 */
class PcController extends MobileBaseController
{
    public function actionIndex(){
        $this->layout="//layouts/light";
        $rows=Goods::GetActive();
        yii::app()->name=$rows[0]['name'];
        $this->render("index",array('rows'=>$rows));
    }

    public function actionCheck(){
        $this->layout="//layouts/light";
        yii::app()->name="内蒙电信宽带砍价";
        $this->render("check");
    }

    public function actionGet_price(){

        $id="g54a39d2d99f07";
        $number=$_POST['number'];
        $b_price=new BargainPrice();
        $price=$b_price->getPrice($id,$number);
        if($price){
            $msg['status']=0;
            $msg['desc']="当前价格：￥".number_format($price['price_now']/100,2);
            if($price['price_now']>8000){
                $msg['desc'].="，砍价还没成功！";
            }else{
                $msg['desc'].="，砍价成功！";
            }
        }else{
            $msg['status']=-1;
            $msg['desc']="该用户没有砍过价";
        }
        print_r(json_encode($msg));
    }

    public function actionSet_bcode(){
        $id="g54a39d2d99f07";
        $number=$_POST['number'];
        $bcode=$_POST['bcode'];
        do{
            $b_price=new BargainPrice();
            $price=$b_price->getPrice($id,$number);
            if(!$price){
                $msg['status']=-1;
                $msg['desc']="该用户没有砍过价";
                break;
            }
            if($price['price_now']>8000){
                $msg['status']=-2;
                $msg['desc']="砍价还没成功！";
                break;
            }
            if(trim($price['bcode'])){
                $msg['status']=-3;
                $msg['desc'].="已经办理过业务";
                break;
            }
            $has_code=Bcode::HasCode($bcode);
            if($has_code<1){
                $msg['status']=-4;
                $msg['desc'].="营业厅代码不存在！";
                break;
            }
            $rs=$b_price->setBcode($id,$number,$bcode);
            if(!$rs){
                $msg['status']=-5;
                $msg['desc'].="业务办理失败，请重试";
                break;
            }
            $msg['status']=0;
            $msg['desc'].="办理成功！";
        }while(false);
        print_r(json_encode($msg));
    }

}