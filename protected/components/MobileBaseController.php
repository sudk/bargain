<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class MobileBaseController extends CController
{
    public $gridId = 'list';

    public $pageSize = 20;
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/mobile';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */


     public function init()
     {
         parent::init();
     }

    /**
	 * Checks if rbac access is granted for the current user
	 * @param String $action . The current action
	 * @return boolean true if access is granted else false
	*/
	protected function beforeAction($action)
	{

        return true;
    }

}