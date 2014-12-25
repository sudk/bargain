<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'砍价管理系统',
    'language' => 'zh_cn',
    // preloading 'log' component
    'preload'=>array('log'),
    'runtimePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'/runtime',
    //'runtimePath'=>'/usr/local/webapp/1430/runtime',
    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.components.widgets.*',
		'ext.PHPExcel.*',
    ),
	//配置模块信息
    // application components
    'components'=>array(
        'user'=>array(
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
            'loginUrl' => 'index.php?r=m/login',
            'returnUrl'=> 'index.php?r=m',
        ),
        'authManager' => array(
            'class' => 'CPhpAuthManager',
        ),
        'db' => array(
         	'connectionString' => 'mysql:host=localhost;port=3306; dbname=bargain',
         	'username' => 'root',
         	'password' => '',
          	'charset' => 'utf8',
        ),
//        'urlManager'=>array(
//            'urlFormat'=>'path',
//            'showScriptName'=>false,
//            'rules'=>array(
//                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
//                '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
//            ),
//        ),
        // uncomment the following to use a MySQL database
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            //'errorAction'=>'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning, info',
                ),
            ),
        ),
        // "redis" => array(
        //     "class" => "ext.redis.ARedisConnection",
        //     "hostname" => "192.168.22.35",
        //     "port" => 6379,
        // ),
		 'fcache'=>array(
            'class'=>'system.caching.CFileCache'
            ),
    ),
    'modules'=>array(
    	'sys'=>array(),
    	'bargain'=>array(),
        'mobile'=>array(),
    ),
   
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
);
