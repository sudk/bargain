<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AuthBaseController extends CController
{

    public $gridId = 'list';

    public $pageSize = 100;
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();

    public static $authInited = false;

    //页头
    public $contentHeader = '';
    public $smallHeader = '';
    public $bigMenu = '';
	
	public function init()
	{
        parent::init();

        $this->pageTitle = Yii::app()->name;
	}
	
	/**
	 * Checks if rbac access is granted for the current user
	 * @param String $action . The current action
	 * @return boolean true if access is granted else false
	*/
	protected function beforeAction($action) 
	{
        if(Yii::app()->user->isGuest)
        {
            $this->redirect('./?r=m/login');
        }
        return true;

	}
}