<?php
/**
 * SimpleValidator class file.
 *
 * @author Wang Dongyang <wangdy@trunkbow.com>
 * @copyright Copyright &copy; 2001-2010 Trunkbow international
 */

class SimpleValidator
{

	static public function required($v)
	{
		return $v==''?'此项不能为空':'';
	}
	
	static public function number($v)
	{
		return !is_numeric($v)?'错误的数值格式':'';
	}
	
	static public function email($v)
	{
		return $v==''?'错误的邮件地址格式':'';
	}

}