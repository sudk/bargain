<?php
class AreaController extends BaseController {

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('district'),
                'users' => array('*'),
            ),
            array('allow',
                'users' => array('@'),
            ),
            array('deny',
                'actions' => array(),
            ),
        );
    }

    public function actionDistrict(){

        $level=$_GET['level'];
        switch($level){
            case 1 :
                $model=new DistrictCode();
                $rs=$model->getProvince();
                $this->renderPartial('province', array('rows'=>$rs));
                break;
            case 2 :
                $p=$_GET['p'];
                $model=new DistrictCodeFull();
                $rs=$model->getCity($p);
                $this->renderPartial('city', array('rows'=>$rs));
                break;
            case 3 :
                $c=$_GET['c'];
                $model=new DistrictCodeFull();
                $rs=$model->getArea($c);
                $this->renderPartial('area', array('rows'=>$rs));
                break;
            default:
                $model=new DistrictCode();
                $rs=$model->getProvince();
                $this->renderPartial('province', array('rows'=>$rs));
        }

    }
}
