<?php

Yii::import('application.controllers.site.*');

class SiteController extends BaseController {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }
    public function actionIndex() {

        if($_GET['l']){
            $l_id=Yii::app()->fcache->get($_GET['l']);
            if($l_id){
                $ar=explode(Yii::app()->params['split'],$l_id);
                $id=$ar[0];
                $s_n=$ar[1];
                $link=Yii::app()->params['base_host']."?r=mobile/goods/index&id={$id}&s_n={$s_n}";
                $this->redirect($link);
            }

        }else{
            $this->redirect('index.php?r=dboard');
        }
    }

}
