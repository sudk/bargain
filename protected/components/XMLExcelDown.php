<?php
class XMLExcelDown {
	/**
	 * ext.phpexcelr.XMLExcel在线导出数据到excel
	 * @param string $filename 下载报表名称
	 * @param array $fields 数据字段描述
	 * @param array $data 数据
	 * @param string title 表头描述
	 */
	public static function down($filename,$fields, $data = array(), $title=""){
		set_time_limit(0);  //解决导出超时问题
		ini_set('memory_limit','256M');
		Yii::import('application.extensions.phpexcelr.XMLExcel');
		
		//处理数据使之符合XMLExcel
		$header_list = array();
		$data_list = array();
		
		if($title){
			$data_list[] = array($title);
		}
		
		if(is_array($fields)){
			foreach ($fields as $field => $desc) {
				$header = $desc["desc"];
				$header_list[] = $header;
			}
				
			$data_list[] = $header_list;
				
			if(is_array($data)){
				foreach ($data as $k => $v){
					$row = array();
					foreach ($fields as $field => $desc){
						$cell_v = $v[$field];
						
						if($cell_v==''){
							if($desc['style']=='num')
								$cell_v = 0;
						}else{
							if($desc['style']=='arr')
								$cell_v = $desc['value'][$cell_v];
							if($desc['style']=='num'){
								$cell_v = self::getvalidNumber($cell_v);
							}
						}
		
						$row[] = $cell_v;
					}
					$data_list[] = $row;
				}
			}
		}
		
		//XMLExcel
		$xls=new XMLExcel();
		
		//设置格式
		$rowsAttr = array(
				array("mergeAcross" => count($fields)-1,"style"=>'s1','height'=>'30'),
				array("style"=>'s2')
		);
		
		$xls -> setRowsAttr($rowsAttr);
		
		$xls -> setDefaultStyles();
		
		$xls->generateXML($data_list,$filename,false);
		
	}
	
	//截去数字前、后无效的零
	public static function getvalidNumber($number){
		$numbers = explode('.', $number);
		$int = $numbers[0];
		$float = $numbers[1];
		if($float){
			$matches = array();
			$cnt = preg_match_all("/[1-9]/", $float,$matches,PREG_OFFSET_CAPTURE);
			if($cnt>0){
				$last_pos = $matches[0][$cnt-1][1];
				$float_valid = substr($float, 0,$last_pos+1);
			}
		}
			
		$int_valid = intval($int);
			
		$number_valid = $float_valid?$int_valid.".".$float_valid:$int_valid;
			
		return strval($number_valid);
			
	}
}