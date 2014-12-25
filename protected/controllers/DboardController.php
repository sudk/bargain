<?php
/*
 * 模块编号: M1001
 */
class DboardController extends BaseController
{
    public $contentHeader = "控制面板";
    public $bigMenu = "首页";

    public function accessRules()
    {
        return array(
            array('allow',
                'users' => array('@'),
            ),
            array('deny',
                'actions' => array(),
            ),
        );
    }

	public function actionIndex()
	{
        return $this->actionSystem();
	}

	public function actionSystem()
	{
        $this -> smallHeader = "首页";
        $this->render('system');
	}


}