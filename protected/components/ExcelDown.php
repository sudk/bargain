<?php
class ExcelDown {
	/**
	 * 
	 * @param string $filename 文件名称 
	 * @param array $data 数据 eg:array(0=>array('user_name' => '用户001'))
	 * @param array $fieldsDesc 数据列属性  
	 * 	eg:array('user_name' =>array('desc'=>'列名','style'=>'str','width'=>20,'value'=>''))
	 * 	style:str-字符串，
	 * 		num-数字（自动去掉前后无效的0），
	 * 		arr-数组（通常是一些字典数据，这时value代表字典数组如 array('1'=>'男','2'=>'女')，而data数据对应列的值只需要填入对应的键就可以，如填入1就代表男）；
	 *  	width:列宽 默认20
	 * @param string $title 表头
	 */
	public static function down($filename, $data = array(), $fieldsDesc = array(), $title = "") {
		set_time_limit(0);  //解决导出超时问题
		spl_autoload_unregister ( array ('YiiBase', 'autoload' ) ); //关闭yii的自动加载功能
		Yii::import ( 'application.extensions.PHPExcel.PHPExcel', true );
		$filename = iconv ( 'utf-8', 'gbk', $filename . '.xls' );
		
		$totalSheet = ceil ( count ( $data ) / 65530);
		$sheetData = array();
		for($s = 0;$s<$totalSheet;$s++){
			$sheetData[$s] = array_slice($data,$s*65530,65530);
		}
		/** PHPExcel */
		//设置单元格缓存方式,默认为内存缓存
		//$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized; 
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;   //保存在php://temp
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod);  
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel ();
		
		// Set properties
		$objPHPExcel->getProperties ()->setCreator ( "TrunkBow" )
		                              ->setLastModifiedBy ( "TrunkBow" )
		                              ->setTitle ( "Office 2003 XLS Document" )
		                              ->setSubject ( "Office 2003 XLS Document" )
		                              ->setDescription ( "TrunkBow" )
		                              ->setKeywords ( "TrunkBow" )
		                              ->setCategory ( "TrunkBow" );
		
		//set cell style
		foreach($sheetData as $sheet => $rows) {
			$t = ord ( 'A' );
			$i = 1;
			if($sheet!=0)
				$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($sheet);
			$activeSheet = $objPHPExcel->getActiveSheet();
			//表头
			if($title&&$sheet==0){
				$range = ord ( 'A' )+count($fieldsDesc)-1;
				$mergeRange = "A1:".chr($range)."1";
				$activeSheet -> mergeCells($mergeRange);
				$a1Style = $activeSheet -> getStyle ("A1");
				$a1Style -> getFont () -> setBold ( true );
				$a1Style -> getAlignment ()
					-> setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$a1Style -> getAlignment() 
					-> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$activeSheet -> getRowDimension(1)->setRowHeight(30);
				$activeSheet -> setCellValue("A1",$title);
				$i++;
			}
			//列名
			foreach ( $fieldsDesc as $desc ) {
				$activeSheet ->setCellValue ( chr ( $t ) . $i, $desc['desc'] );
				$activeSheet ->getStyle ( chr ( $t ) . $i )
					->getFont ()
					->setBold ( true );
				$col_width = $desc['width']?$desc['width']:20;
				$activeSheet ->getColumnDimension ( chr ( $t ) )->setWidth($col_width);
				$t ++;
			}
			//整体格式
			$range = 'A1'.':'.chr($t-1).($i+count($rows));
			$activeSheet ->getStyle ($range)
				->getNumberFormat()
				->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
			
			$activeSheet ->getStyle ($range)
				->getAlignment ()
				->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			
			$styleBorders = array(
					'borders' => array(
							'allborders' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN
							),
					),
			);
			$activeSheet->getStyle($range)->applyFromArray($styleBorders);
		
		}
		
		//数据
		if ($sheetData) {
			foreach ( $sheetData as $sheet =>$rows ) {
				if($title=='')
					$i = 2;
				else 
					$i = 3;
				
				$j = ord ( 'A' );
				$objPHPExcel->setActiveSheetIndex($sheet)->setTitle('sheet'.($sheet+1));
				foreach($rows as $row){
					foreach($fieldsDesc as $k =>$v ){
						$cell_v = $row[$k];
						if($row[$k]==''){
							if($v['style']=='num')
								$cell_v = 0;
						}else{
							if($v['style']=='arr')
								$cell_v = $v['value'][$row[$k]];
							if($v['style']=='num'){
								$cell_v = Utils::getvalidNumber($cell_v);
							}
						}
							
						$objPHPExcel->getActiveSheet() ->
							setCellValue ( chr ( $j ++ ) . $i," ".$cell_v);
					}
					$j = ord ( 'A' );
					$i ++;
				}
				
			}
		}
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex ( 0 );
		
		$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( "Content-Disposition: attachment;filename=$filename" ); 
		header ( 'Cache-Control: max-age=0' );
		$objWriter->save ( 'php://output' );
		Yii::app()->end();
		spl_autoload_register ( array ('YiiBase', 'autoload' ) ); //打开yii的自动加载功能
		//exit();
	
	}
	
	/**
	 * 本类提供excel文件生成、数据ajax写入，每次调用数据量不会太大，所以不考虑数据分页
	 * @param string $filepath 文件路径  文件存放目录+taskid.xls/xlsx
	 * @param array $data 数据 eg:array(0=>array('user_name' => '用户001'))
	 * @param array $fieldsDesc 数据列属性
	 * 	eg:array('user_name' =>array('desc'=>'列名','style'=>'str','width'=>20,'value'=>''))
	 * 	style:str-字符串，
	 * 		num-数字（自动去掉前后无效的0），
	 * 		arr-数组（通常是一些字典数据，这时value代表字典数组如 array('1'=>'男','2'=>'女')，而data数据对应列的值只需要填入对应的键就可以，如填入1就代表男）；
	 *  width:列宽 默认20
	 * @param string $title 表头
	 *
	 * @return array status(1:成功 ,-1:失败) desc：描述
	 */
	public static function save($filepath, $data = array(), $fieldsDesc = array(), $title = "") {
		set_time_limit(0);  //解决导出超时问题
		spl_autoload_unregister ( array ('YiiBase', 'autoload' ) ); //关闭yii的自动加载功能
		Yii::import ( 'application.extensions.PHPExcel.PHPExcel',true);
		$filepath = iconv ( 'utf-8', 'gbk', $filepath );
	
		/** PHPExcel */
		//设置单元格缓存方式,默认为内存缓存
		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
		$cacheSettings = array('memoryCacheSize'=>'8MB');
		PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);
	
		if(!file_exists($filepath)){   //第一次生成文件
			// Create new PHPExcel object
			$objPHPExcel = new PHPExcel ();
			$newfile_flag  = true;
		}else{
			$PHPReader = new PHPExcel_Reader_Excel5();  //excel2003
			if(!$PHPReader->canRead($filepath)){
				return array('status'=>-1,'desc'=>'读取文件失败');
			}
			$objPHPExcel = $PHPReader->load($filepath);
			$newfile_flag = false;
		}
	
		// Set properties
		$objPHPExcel->getProperties ()->setCreator ( "TrunkBow" )
		->setLastModifiedBy ( "TrunkBow" )
		->setTitle ( "Office 2003 XLS Document" )
		->setSubject ( "Office 2003 XLS Document" )
		->setDescription ( "TrunkBow" )
		->setKeywords ( "TrunkBow" )
		->setCategory ( "TrunkBow" );
	
		$styleBorders = array(
				'borders' => array(
						'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
						),
				),
		);
	
		if($newfile_flag==true){   //新文件 写入标题和表头
			$t = ord ( 'A' );
			$i = 1;
			$objPHPExcel->setActiveSheetIndex(0);
			$activeSheet = $objPHPExcel->getActiveSheet();
			//表头
			if($title){
				$range = ord ( 'A' )+count($fieldsDesc)-1;
				$mergeRange = "A1:".chr($range)."1";
				$activeSheet -> mergeCells($mergeRange);
				$a1Style = $activeSheet -> getStyle ("A1");
				$a1Style -> getFont () -> setBold ( true );
				$a1Style -> getAlignment ()
				-> setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$a1Style -> getAlignment()
				-> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$activeSheet -> getRowDimension(1)->setRowHeight(30);
				$activeSheet -> setCellValue("A1",$title);
				$i++;
			}
			//列名
			foreach ( $fieldsDesc as $desc ) {
				$activeSheet ->setCellValue ( chr ( $t ) . $i, $desc['desc'] );
				$activeSheet ->getStyle ( chr ( $t ) . $i )
				->getFont ()
				->setBold ( true );
				$col_width = $desc['width']?$desc['width']:20;
				$activeSheet ->getColumnDimension ( chr ( $t ) )->setWidth($col_width);
				$t ++;
			}
			$maxCol = chr($t-1);
			$maxRow = $i;
				
			$activeSheet->getStyle('A1:'.$maxCol.$maxRow)->applyFromArray($styleBorders);
				
		}else{
			$activeSheet = $objPHPExcel->getSheet(0);
			$maxCol = $activeSheet -> getHighestColumn();
			$maxRow = $activeSheet -> getHighestRow();
		}
	
	
		if($data){
			//整体格式
			$range = 'A'.($maxRow+1).':'.$maxCol.($maxRow+count($data));
			$activeSheet ->getStyle ($range)
			->getNumberFormat()
			->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
	
			$activeSheet ->getStyle ($range)
			->getAlignment ()
			->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				
			$activeSheet->getStyle($range)->applyFromArray($styleBorders);
				
			//写数据
			$j = ord('A');
			$i = $maxRow+1;
			foreach($data as $row){
				foreach($fieldsDesc as $k =>$v ){
					$cell_v = $row[$k];
					if($cell_v==''){
						if($v['style']=='num')
							$cell_v = 0;
					}else{
						if($v['style']=='arr')
							$cell_v = $v['value'][$row[$k]];
						if($v['style']=='num'){   //去掉数字前后无效的0
							$cell_v = self::getvalidNumber($cell_v);
						}
					}
						
					$objPHPExcel->getActiveSheet() ->
					setCellValue ( chr ( $j ++ ) . $i," ".$cell_v);
				}
				$j = ord ( 'A' );
				$i ++;
			}
		}
	
		$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		$objWriter->save ($filepath);
		//Yii::app()->end();
		spl_autoload_register ( array ('YiiBase', 'autoload' ) ); //打开yii的自动加载功能
		return array("status"=>1,"desc"=>"成功");
	
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