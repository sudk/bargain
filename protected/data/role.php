<?php
/*
 * 数据级别data：0级为最高级（不受任何限制），
 *               1级为受部分限制（目前和0级的区别在于查看交易明细时交易账号被加*号）；
 *               2级为客户经理或维护人员：只能看该客户经理所发展或维护的终端；
 * */
return array(
    'smanager' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '超级管理员',
        'display'=>true,
        'children' => array(
            'area','auth','dboard','site','sys','operator','mchtm','posm','rpt',
            'sys/operator/pedit','risk'
        ),
        'bizRules' => '',
        'data' => '0'
    ),
    'imanager' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => '机构或网点管理员',
        'display'=>true,
        'children' => array(
            'area','auth','dboard','site','mchtm','posm','rpt'
            ,'sys/operator/pedit','risk'
        ),
        'bizRules' => '',
        'data' => '0'
    ),
);