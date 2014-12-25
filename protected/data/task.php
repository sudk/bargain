<?php

return array(

    'area' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display'=>false,
        'description' => '地区选择器',
        'children' => array(
            'area/district',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'auth' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display'=>false,
        'description' => '权限',
        'children' => array(
            'auth/list','auth/del','auth/add',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'dboard' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display'=>false,
        'description' => '首页',
        'children' => array(
            'dboard/index','dboard/system',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'site' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display'=>false,
        'description' => 'site',
        'children' => array(
            'site/s','site/index','site/error','site/login','site/getpass','site/logout','site/passwd','site/updateoperation','site/showtask','site/session','site/new',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'sys' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display'=>false,
        'description' => '系统管理',
        'children' => array(
            'sys/institute/grid','sys/institute/list','sys/institute/new','sys/institute/edit','sys/institute/search','sys/operator/grid','sys/operator/list','sys/operator/new','sys/operator/edit','sys/operator/role',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'operator' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display'=>true,
        'description' => '操作员管理',
        'children' => array(
            'operator/operator/list','operator/operator/grid','operator/operator/new'
        ),
        'bizRules' => '',
        'data' => ''
    ),
	'mchtm' => array(
		'type' => CAuthItem::TYPE_TASK,
		'display'=>true,
		'description' => '商户管理',
		'children' => array(
            'mchtm/mcht/grid','mchtm/mcht/list','mchtm/mcht/new','mchtm/mcht/edit','mchtm/mcht/delete','mchtm/mcht/detail','mchtm/mcht/import','mchtm/mcht/gettask','mchtm/mcht/download',
        ),
		'bizRules' => '',
		'data' => ''
	),
    'posm' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display'=>true,
        'description' => 'POS管理',
        'children' => array(
            'posm/pos/grid','posm/pos/list','posm/pos/mgrid','posm/pos/mlist','posm/pos/new','posm/pos/edit','posm/pos/delete','posm/pos/detail','posm/pos/search',
        ),
        'bizRules' => '',
        'data' => ''
    ),
    'rpt' => array(
        'type' => CAuthItem::TYPE_TASK,
        'display'=>true,
        'description' => '统计报表',
        'children' => array(
            'rpt/mchtchart/grid','rpt/mchtchart/list','rpt/mchtday/grid',
        	'rpt/mchtday/list','rpt/mchtday/detail','rpt/mchtday/chart',
        	'rpt/mchtday/chartdata','rpt/mchtday/download','rpt/mchtday/loaddata',
        	'rpt/mchtmonth/grid','rpt/mchtmonth/list','rpt/mchtmonth/detail',
        	'rpt/mchtmonth/chartdata','rpt/mchtmonth/download','rpt/mchtmonth/loaddata',
        	'rpt/posday/grid','rpt/posday/list','rpt/posday/detail',
        	'rpt/posday/download','rpt/posday/loaddata','rpt/posmonth/grid',
        	'rpt/posmonth/list','rpt/posmonth/detail','rpt/posmonth/download',
        	'rpt/posmonth/loaddata','rpt/mchtday/export'
        ),
        'bizRules' => '',
        'data' => ''
    ),
		
	'risk' => array(
		'type' => CAuthItem::TYPE_TASK,
		'display'=>true,
		'description' => '风险监控',
		'children' => array(
			'risk/single/list','risk/single/grid','risk/single/posdetail',
			'risk/day/list','risk/day/grid',
			'risk/month/list','risk/month/grid',
			'risk/mgrowth/list','risk/mgrowth/grid',
			'risk/repeat/list','risk/repeat/grid',
			'risk/timeout/list','risk/timeout/grid',
		),
		'bizRules' => '',
		'data' => ''
	),
);

