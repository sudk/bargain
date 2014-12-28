<?php

class Utils {

	public static function hx2bin($str) {

	  $len = strlen($str);
	  $nstr = "";
	  for ($i=0;$i<$len;$i+=2) {
		$num = sscanf(substr($str,$i,2), "%x");
		$nstr.=chr($num[0]);
	  }
	  return $nstr;
	}

	private static function to64($v, $n) {
	  $ITOA64 = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

	  $ret = "";
	  while (($n - 1) >= 0) {
		$n--;
		$ret .= $ITOA64[$v & 0x3f];
		$v = $v >> 6;
	  }
	  
	  return $ret;

	}


	public static function md5crypt($pw, $salt, $magic="") {

	  $MAGIC = "$1$";

	  if ($magic == "") $magic = $MAGIC;
	  
	  $slist = explode("$", $salt);
	  if ($slist[0] == "1") $salt = $slist[1];
	  $salt = substr($salt, 0, 8);
	  
	  $ctx = $pw . $magic . $salt;
	  
	  $final = self::hx2bin(md5($pw . $salt . $pw));
	  
	  for ($i=strlen($pw); $i>0; $i-=16) {
		if ($i > 16)
		  $ctx .= substr($final,0,16);
		else
		  $ctx .= substr($final,0,$i);
	  }
	  
	  $i = strlen($pw);
	  while ($i > 0) {
		if ($i & 1) $ctx .= chr(0);
		else $ctx .= $pw[0];
		$i = $i >> 1;
	  }
	  
	  $final = self::hx2bin(md5($ctx));

	  # this is really stupid and takes too long

	  for ($i=0;$i<1000;$i++) {
		$ctx1 = "";
		if ($i & 1) $ctx1 .= $pw;
		else $ctx1 .= substr($final,0,16);
		if ($i % 3) $ctx1 .= $salt;
		if ($i % 7) $ctx1 .= $pw;
		if ($i & 1) $ctx1 .= substr($final,0,16);
		else $ctx1 .= $pw;
		$final = self::hx2bin(md5($ctx1));
	  }
	  
	  $passwd = "";
	  
	  $passwd .= self::to64( ( (ord($final[0]) << 16) | (ord($final[6]) << 8) | (ord($final[12])) ), 4);
	  $passwd .= self::to64( ( (ord($final[1]) << 16) | (ord($final[7]) << 8) | (ord($final[13])) ), 4);
	  $passwd .= self::to64( ( (ord($final[2]) << 16) | (ord($final[8]) << 8) | (ord($final[14])) ), 4);
	  $passwd .= self::to64( ( (ord($final[3]) << 16) | (ord($final[9]) << 8) | (ord($final[15])) ), 4);
	  $passwd .= self::to64( ( (ord($final[4]) << 16) | (ord($final[10]) << 8) | (ord($final[5])) ), 4);
	  $passwd .= self::to64( ord($final[11]), 2);

	  return "$magic$salt\$$passwd";

	}
    
    /**
     * 产生action的权限配置文件
     * @param <string> 模块名称, 为空表示app下Controller
     */
    public static function getControllersActions($module='')
	{
		
		if($module=='')
		{
			//Yii::import('application.controllers.*');
			$controllerPath = Yii::app()->basePath.'/controllers';
            $path = get_include_path();
            set_include_path($controllerPath.PATH_SEPARATOR.$path);
		}
		else
		{
			//Yii::import('application.modules.'.$module.'.controllers.*');
			$controllerPath = Yii::app()->basePath.'/modules/'.$module.'/controllers';
            $path = get_include_path();
            set_include_path($controllerPath.PATH_SEPARATOR.$path);
		}
		$a = array();
		
		
		$d = @dir($controllerPath);
        if(false === $d) return array();
		while (false !== ($entry = @$d->read()))
		if ($entry != '..' && $entry != '.' && substr($entry,-14)=='Controller.php')
		{
			//echo $entry,'<br/>';
			$controller = substr($entry,0,strlen($entry)-4);
			//echo $controller,'<br/>';
			$class = new ReflectionClass($controller);
			$methods = $class->getMethods();
			foreach($methods as $method)
			{
				//var_dump($method);
				if($method->class==$controller && substr($method->name,0,6)=='action')
				{
					//echo $method->name,'<br>';
					$a[] = strtolower(substr($controller,0,strlen($controller)-10).'/'.substr($method->name,6));
				}
			}
		}
		$d->close();
		return $a;
	}


    public static function  getMicrotime()
    {
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);
    }

    public static function json_decode_nice($json, $assoc = FALSE){
        $json = '"'.$json.'"';
        $json = str_replace(array("\n","\r"),"",$json);
        $json = preg_replace('/([{,])(\s*)([^"]+?)\s*:/','$1"$3":',$json);
        return json_decode($json,$assoc);
    }

    public static function chinese_week($time=0)
    {
        $w = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
        if($time==0) $time = time();
        return $w[date('w',$time)];
    }


	public static function getProvince() 
	{
		$rs = array(
			''		 =>	'',
			'安徽'   =>	'安徽'  ,   
			'重庆'   =>	'重庆'  ,   
			'福建'   =>	'福建'  ,   
			'甘肃'   =>	'甘肃'  ,   
			'广东'   =>	'广东'  ,   
			'广西'   =>	'广西'  ,   
			'贵州'   =>	'贵州'  ,   
			'海南'   =>	'海南'  ,   
			'河北'   =>	'河北'  ,   
			'河南'   =>	'河南'  ,   
			'黑龙江' =>	'黑龙江',
			'湖北'   =>	'湖北'  ,
			'湖南'   =>	'湖南'  ,   
			'江苏'   =>	'江苏'  ,   
			'江西'   =>	'江西'  ,   
			'吉林'   =>	'吉林'  ,   
			'辽宁'   =>	'辽宁'  ,   
			'内蒙古' =>	'内蒙古',
			'宁夏'   =>	'宁夏'  ,
			'青海'   =>	'青海'  ,   
			'山东'   =>	'山东'  ,   
			'山西'   =>	'山西'  ,   
			'陕西'   =>	'陕西'  ,   
			'四川'   =>	'四川'  ,   
			'天津'   =>	'天津'  ,   
			'西藏'   =>	'西藏'  ,   
			'新疆'   =>	'新疆'  ,   
			'云南'   =>	'云南'  ,   
			'浙江'   =>	'浙江'  ,   
			'香港'   =>	'香港'  ,   
			'澳门'   =>	'澳门'  ,   
			'台湾'   =>	'台湾'  ,
		);
		return $rs;
	}
	
	public static function getTelegroup() 
	{
		$rs = array(
			''		 =>	'',
			'移动'   =>	'移动'  ,   
			'联通'   =>	'联通'  ,   
			'电信'   =>	'电信'  ,

		);
		return $rs;
	}
	
	public static function getCity($citycode) 
	{
		$rs = array(
			'130100' => '石家庄' ,
			'130200' => '唐山',   
			'130300' => '秦皇岛' ,
			'130400' => '邯郸',   
			'130500' => '邢台',   
			'130600' => '保定',   
			'130700' => '张家口' ,
			'130800' => '承德',   
			'130900' => '沧州',   
			'131000' => '廊坊',   
			'131100' => '衡水',   
		);
		return $citycode == '_ARRAY' ? $rs : $rs[$citycode];
	}

    public static function getArea($city='')
    {
        $a = array(
            '430400' => array('衡阳市',array(
                '430401' => '市辖区',
                '430405' => '珠晖区',
                '430406' => '雁峰区',
                '430407' => '石鼓区',
                '430408' => '蒸湘区',
                '430412' => '南岳区',
                '430421' => '衡阳县',
                '430422' => '衡南县',
                '430423' => '衡山县',
                '430424' => '衡东县',
                '430426' => '祁东县',
                '430481' => '耒阳市',
                '430482' => '常宁市',
            )),
            '430500' => array('邵阳市',array(
                '430501' => '市辖区',
                '430502' => '双清区',
                '430503' => '大祥区',
                '430511' => '北塔区',
                '430521' => '邵东县',
                '430522' => '新邵县',
                '430523' => '邵阳县',
                '430524' => '隆回县',
                '430525' => '洞口县',
                '430527' => '绥宁县',
                '430528' => '新宁县',
                '430529' => '城步苗族自治县',
                '430581' => '武冈市',
            )),
        );
        if($city!='') return $a[$city];
        return $a;
    }

    public static function getMessageType($type=""){
        $ar=array(
            '0'=> array('alert-success','fa-check'),
            '-1'=> array('alert-danger','fa-ban'),
            '2'=> array('alert-info','fa-info'),
            '3' => array('alert-warning','fa-warning'),
        );
        return $type!==""?$ar[$type]:$ar;
    }
    public static function markIdNumber($id){
        $id = trim($id);
        if(strlen($id)!=18) return $id;
        return substr($id,0,3).'********'.substr($id,14,17);
    }
    public static function markPhone($phone)
    {
        $phone = trim($phone);
        if(strlen($phone)!=11) return $phone;
        return substr($phone,0,3).'****'.substr($phone,7,4);
    }

    public static function timeTranslate($time,$timeto="now"){
        $translate="";
        $strtotime=strtotime($timeto);
        $strtime=strtotime($time);
        $differ=$strtotime-$strtime;
        $rs=intval($differ/60);
        if ($rs < 1) {
            $translate = $differ . "秒前";
        } elseif ($rs < 60) {
            $translate = intval($rs) . "分钟前";
        } else {
//            $h = intval($differ / 3600);
//            $m = intval(($differ - $h*3600) / 60);
//            $translate = $h . "小时" . $m . "分种前";
            $translate=$time;
        }
        return $translate;
    }


    public static  function Pinyin($_String, $_Code='UTF8'){ //GBK页面可改为gb2312，其他随意填写为UTF8
        $_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha".
            "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|".
            "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er".
            "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui".
            "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang".
            "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang".
            "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue".
            "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne".
            "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen".
            "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang".
            "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|".
            "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|".
            "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu".
            "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you".
            "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|".
            "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
        $_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990".
            "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725".
            "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263".
            "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003".
            "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697".
            "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211".
            "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922".
            "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468".
            "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664".
            "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407".
            "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959".
            "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652".
            "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369".
            "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128".
            "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914".
            "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645".
            "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149".
            "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087".
            "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658".
            "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340".
            "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888".
            "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585".
            "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847".
            "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055".
            "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780".
            "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274".
            "|-10270|-10262|-10260|-10256|-10254";
        $_TDataKey   = explode('|', $_DataKey);
        $_TDataValue = explode('|', $_DataValue);
        $_Data = array_combine($_TDataKey, $_TDataValue);
        arsort($_Data);
        reset($_Data);
        if($_Code!= 'gb2312') $_String = self::_U2_Utf8_Gb($_String);
        $_Res = '';
        for($i=0; $i<strlen($_String); $i++) {
            $_P = ord(substr($_String, $i, 1));
            if($_P>160) {
                $_Q = ord(substr($_String, ++$i, 1)); $_P = $_P*256 + $_Q - 65536;
            }
            $_Res .= self::_Pinyin($_P, $_Data);
        }
        return preg_replace("/[^a-z0-9]*/", '', $_Res);
    }

    function _Pinyin($_Num, $_Data){
        if($_Num>0 && $_Num<160 ){
            return chr($_Num);
        }elseif($_Num<-20319 || $_Num>-10247){
            return '';
        }else{
            foreach($_Data as $k=>$v){ if($v<=$_Num) break; }
            return $k;
        }
    }

    function _U2_Utf8_Gb($_C){
        $_String = '';
        if($_C < 0x80){
            $_String .= $_C;
        }elseif($_C < 0x800) {
            $_String .= chr(0xC0 | $_C>>6);
            $_String .= chr(0x80 | $_C & 0x3F);
        }elseif($_C < 0x10000){
            $_String .= chr(0xE0 | $_C>>12);
            $_String .= chr(0x80 | $_C>>6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        }elseif($_C < 0x200000) {
            $_String .= chr(0xF0 | $_C>>18);
            $_String .= chr(0x80 | $_C>>12 & 0x3F);
            $_String .= chr(0x80 | $_C>>6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        }
        return iconv('UTF-8', 'GB2312', $_String);
    }



    /**
     * 取一个汉字码对应的拼音
     * @param int $num 汉字码
     * @param string $blank 空白字符
     * @return string
     */
    private  function getChineseSpell($num, $blank = "")
    {
        $chineseSpellList = array(
            'a'=>-20319,
            'ai'=>-20317,
            'an'=>-20304,
            'ang'=>-20295,
            'ao'=>-20292,
            'ba'=>-20283,
            'bai'=>-20265,
            'ban'=>-20257,
            'bang'=>-20242,
            'bao'=>-20230,
            'bei'=>-20051,
            'ben'=>-20036,
            'beng'=>-20032,
            'bi'=>-20026,
            'bian'=>-20002,
            'biao'=>-19990,
            'bie'=>-19986,
            'bin'=>-19982,
            'bing'=>-19976,
            'bo'=>-19805,
            'bu'=>-19784,
            'ca'=>-19775,
            'cai'=>-19774,
            'can'=>-19763,
            'cang'=>-19756,
            'cao'=>-19751,
            'ce'=>-19746,
            'ceng'=>-19741,
            'cha'=>-19739,
            'chai'=>-19728,
            'chan'=>-19725,
            'chang'=>-19715,
            'chao'=>-19540,
            'che'=>-19531,
            'chen'=>-19525,
            'cheng'=>-19515,
            'chi'=>-19500,
            'chong'=>-19484,
            'chou'=>-19479,
            'chu'=>-19467,
            'chuai'=>-19289,
            'chuan'=>-19288,
            'chuang'=>-19281,
            'chui'=>-19275,
            'chun'=>-19270,
            'chuo'=>-19263,
            'ci'=>-19261,
            'cong'=>-19249,
            'cou'=>-19243,
            'cu'=>-19242,
            'cuan'=>-19238,
            'cui'=>-19235,
            'cun'=>-19227,
            'cuo'=>-19224,
            'da'=>-19218,
            'dai'=>-19212,
            'dan'=>-19038,
            'dang'=>-19023,
            'dao'=>-19018,
            'de'=>-19006,
            'deng'=>-19003,
            'di'=>-18996,
            'dian'=>-18977,
            'diao'=>-18961,
            'die'=>-18952,
            'ding'=>-18783,
            'diu'=>-18774,
            'dong'=>-18773,
            'dou'=>-18763,
            'du'=>-18756,
            'duan'=>-18741,
            'dui'=>-18735,
            'dun'=>-18731,
            'duo'=>-18722,
            'e'=>-18710,
            'en'=>-18697,
            'er'=>-18696,
            'fa'=>-18526,
            'fan'=>-18518,
            'fang'=>-18501,
            'fei'=>-18490,
            'fen'=>-18478,
            'feng'=>-18463,
            'fo'=>-18448,
            'fou'=>-18447,
            'fu'=>-18446,
            'ga'=>-18239,
            'gai'=>-18237,
            'gan'=>-18231,
            'gang'=>-18220,
            'gao'=>-18211,
            'ge'=>-18201,
            'gei'=>-18184,
            'gen'=>-18183,
            'geng'=>-18181,
            'gong'=>-18012,
            'gou'=>-17997,
            'gu'=>-17988,
            'gua'=>-17970,
            'guai'=>-17964,
            'guan'=>-17961,
            'guang'=>-17950,
            'gui'=>-17947,
            'gun'=>-17931,
            'guo'=>-17928,
            'ha'=>-17922,
            'hai'=>-17759,
            'han'=>-17752,
            'hang'=>-17733,
            'hao'=>-17730,
            'he'=>-17721,
            'hei'=>-17703,
            'hen'=>-17701,
            'heng'=>-17697,
            'hong'=>-17692,
            'hou'=>-17683,
            'hu'=>-17676,
            'hua'=>-17496,
            'huai'=>-17487,
            'huan'=>-17482,
            'huang'=>-17468,
            'hui'=>-17454,
            'hun'=>-17433,
            'huo'=>-17427,
            'ji'=>-17417,
            'jia'=>-17202,
            'jian'=>-17185,
            'jiang'=>-16983,
            'jiao'=>-16970,
            'jie'=>-16942,
            'jin'=>-16915,
            'jing'=>-16733,
            'jiong'=>-16708,
            'jiu'=>-16706,
            'ju'=>-16689,
            'juan'=>-16664,
            'jue'=>-16657,
            'jun'=>-16647,
            'ka'=>-16474,
            'kai'=>-16470,
            'kan'=>-16465,
            'kang'=>-16459,
            'kao'=>-16452,
            'ke'=>-16448,
            'ken'=>-16433,
            'keng'=>-16429,
            'kong'=>-16427,
            'kou'=>-16423,
            'ku'=>-16419,
            'kua'=>-16412,
            'kuai'=>-16407,
            'kuan'=>-16403,
            'kuang'=>-16401,
            'kui'=>-16393,
            'kun'=>-16220,
            'kuo'=>-16216,
            'la'=>-16212,
            'lai'=>-16205,
            'lan'=>-16202,
            'lang'=>-16187,
            'lao'=>-16180,
            'le'=>-16171,
            'lei'=>-16169,
            'leng'=>-16158,
            'li'=>-16155,
            'lia'=>-15959,
            'lian'=>-15958,
            'liang'=>-15944,
            'liao'=>-15933,
            'lie'=>-15920,
            'lin'=>-15915,
            'ling'=>-15903,
            'liu'=>-15889,
            'long'=>-15878,
            'lou'=>-15707,
            'lu'=>-15701,
            'lv'=>-15681,
            'luan'=>-15667,
            'lue'=>-15661,
            'lun'=>-15659,
            'luo'=>-15652,
            'ma'=>-15640,
            'mai'=>-15631,
            'man'=>-15625,
            'mang'=>-15454,
            'mao'=>-15448,
            'me'=>-15436,
            'mei'=>-15435,
            'men'=>-15419,
            'meng'=>-15416,
            'mi'=>-15408,
            'mian'=>-15394,
            'miao'=>-15385,
            'mie'=>-15377,
            'min'=>-15375,
            'ming'=>-15369,
            'miu'=>-15363,
            'mo'=>-15362,
            'mou'=>-15183,
            'mu'=>-15180,
            'na'=>-15165,
            'nai'=>-15158,
            'nan'=>-15153,
            'nang'=>-15150,
            'nao'=>-15149,
            'ne'=>-15144,
            'nei'=>-15143,
            'nen'=>-15141,
            'neng'=>-15140,
            'ni'=>-15139,
            'nian'=>-15128,
            'niang'=>-15121,
            'niao'=>-15119,
            'nie'=>-15117,
            'nin'=>-15110,
            'ning'=>-15109,
            'niu'=>-14941,
            'nong'=>-14937,
            'nu'=>-14933,
            'nv'=>-14930,
            'nuan'=>-14929,
            'nue'=>-14928,
            'nuo'=>-14926,
            'o'=>-14922,
            'ou'=>-14921,
            'pa'=>-14914,
            'pai'=>-14908,
            'pan'=>-14902,
            'pang'=>-14894,
            'pao'=>-14889,
            'pei'=>-14882,
            'pen'=>-14873,
            'peng'=>-14871,
            'pi'=>-14857,
            'pian'=>-14678,
            'piao'=>-14674,
            'pie'=>-14670,
            'pin'=>-14668,
            'ping'=>-14663,
            'po'=>-14654,
            'pu'=>-14645,
            'qi'=>-14630,
            'qia'=>-14594,
            'qian'=>-14429,
            'qiang'=>-14407,
            'qiao'=>-14399,
            'qie'=>-14384,
            'qin'=>-14379,
            'qing'=>-14368,
            'qiong'=>-14355,
            'qiu'=>-14353,
            'qu'=>-14345,
            'quan'=>-14170,
            'que'=>-14159,
            'qun'=>-14151,
            'ran'=>-14149,
            'rang'=>-14145,
            'rao'=>-14140,
            're'=>-14137,
            'ren'=>-14135,
            'reng'=>-14125,
            'ri'=>-14123,
            'rong'=>-14122,
            'rou'=>-14112,
            'ru'=>-14109,
            'ruan'=>-14099,
            'rui'=>-14097,
            'run'=>-14094,
            'ruo'=>-14092,
            'sa'=>-14090,
            'sai'=>-14087,
            'san'=>-14083,
            'sang'=>-13917,
            'sao'=>-13914,
            'se'=>-13910,
            'sen'=>-13907,
            'seng'=>-13906,
            'sha'=>-13905,
            'shai'=>-13896,
            'shan'=>-13894,
            'shang'=>-13878,
            'shao'=>-13870,
            'she'=>-13859,
            'shen'=>-13847,
            'sheng'=>-13831,
            'shi'=>-13658,
            'shou'=>-13611,
            'shu'=>-13601,
            'shua'=>-13406,
            'shuai'=>-13404,
            'shuan'=>-13400,
            'shuang'=>-13398,
            'shui'=>-13395,
            'shun'=>-13391,
            'shuo'=>-13387,
            'si'=>-13383,
            'song'=>-13367,
            'sou'=>-13359,
            'su'=>-13356,
            'suan'=>-13343,
            'sui'=>-13340,
            'sun'=>-13329,
            'suo'=>-13326,
            'ta'=>-13318,
            'tai'=>-13147,
            'tan'=>-13138,
            'tang'=>-13120,
            'tao'=>-13107,
            'te'=>-13096,
            'teng'=>-13095,
            'ti'=>-13091,
            'tian'=>-13076,
            'tiao'=>-13068,
            'tie'=>-13063,
            'ting'=>-13060,
            'tong'=>-12888,
            'tou'=>-12875,
            'tu'=>-12871,
            'tuan'=>-12860,
            'tui'=>-12858,
            'tun'=>-12852,
            'tuo'=>-12849,
            'wa'=>-12838,
            'wai'=>-12831,
            'wan'=>-12829,
            'wang'=>-12812,
            'wei'=>-12802,
            'wen'=>-12607,
            'weng'=>-12597,
            'wo'=>-12594,
            'wu'=>-12585,
            'xi'=>-12556,
            'xia'=>-12359,
            'xian'=>-12346,
            'xiang'=>-12320,
            'xiao'=>-12300,
            'xie'=>-12120,
            'xin'=>-12099,
            'xing'=>-12089,
            'xiong'=>-12074,
            'xiu'=>-12067,
            'xu'=>-12058,
            'xuan'=>-12039,
            'xue'=>-11867,
            'xun'=>-11861,
            'ya'=>-11847,
            'yan'=>-11831,
            'yang'=>-11798,
            'yao'=>-11781,
            'ye'=>-11604,
            'yi'=>-11589,
            'yin'=>-11536,
            'ying'=>-11358,
            'yo'=>-11340,
            'yong'=>-11339,
            'you'=>-11324,
            'yu'=>-11303,
            'yuan'=>-11097,
            'yue'=>-11077,
            'yun'=>-11067,
            'za'=>-11055,
            'zai'=>-11052,
            'zan'=>-11045,
            'zang'=>-11041,
            'zao'=>-11038,
            'ze'=>-11024,
            'zei'=>-11020,
            'zen'=>-11019,
            'zeng'=>-11018,
            'zha'=>-11014,
            'zhai'=>-10838,
            'zhan'=>-10832,
            'zhang'=>-10815,
            'zhao'=>-10800,
            'zhe'=>-10790,
            'zhen'=>-10780,
            'zheng'=>-10764,
            'zhi'=>-10587,
            'zhong'=>-10544,
            'zhou'=>-10533,
            'zhu'=>-10519,
            'zhua'=>-10331,
            'zhuai'=>-10329,
            'zhuan'=>-10328,
            'zhuang'=>-10322,
            'zhui'=>-10315,
            'zhun'=>-10309,
            'zhuo'=>-10307,
            'zi'=>-10296,
            'zong'=>-10281,
            'zou'=>-10274,
            'zu'=>-10270,
            'zuan'=>-10262,
            'zui'=>-10260,
            'zun'=>-10256,
            'zuo'=>-10254
        );
        if ($num > 0 && $num < 160) {
            return chr($num);
        }
        elseif ($num < -20319 || $num > -10247) {
            return $blank;
        } else {
            foreach ($chineseSpellList as $spell => $code) {
                if ($code > $num) break;
                $result = $spell;
            }
            return $result;
        }
    }


    /**
     * 下载模板文件
     * @return <type>
     */
    public static function Download($file_path,$show_name,$extend='xml')
    {
//        $filename = trim($_REQUEST['filename']);
//        $showfilename = trim($_REQUEST['showfilename']);
//        $fileDir = Yii::app()->params['stu_template_path'] . $filename . '.xls';
        //文件不存在
        if (file_exists($file_path) == false) {
            header("Content-type:text/html;charset=utf-8");
            echo "<script>alert('您要下载的文件不存在！');</script>";
            return;
        }
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $encoded_filename = urlencode($show_name);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);
        header('Content-Type: application/octet-stream');
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '.'.$extend.'"');
        } else if (preg_match("/Firefox/", $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\'' . $show_name . '.'.$extend.'"');
        } else {
            header('Content-Disposition: attachment; filename="' . $show_name . '.'.$extend.'"');
        }
        header('Content-Length:' . filesize($file_path));
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        ob_clean();
        flush();
        readfile($file_path);

    }

    public static function VisitDocTemp()
    {
        $head=<<<head
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<?mso-application progid="Word.Document"?>
<w:wordDocument xmlns:aml="http://schemas.microsoft.com/aml/2001/core" xmlns:dt="uuid:C2F41010-65B3-11d1-A29F-00AA00C14882" xmlns:ve="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w10="urn:schemas-microsoft-com:office:word" xmlns:w="http://schemas.microsoft.com/office/word/2003/wordml" xmlns:wx="http://schemas.microsoft.com/office/word/2003/auxHint" xmlns:wsp="http://schemas.microsoft.com/office/word/2003/wordml/sp2" xmlns:sl="http://schemas.microsoft.com/schemaLibrary/2003/core" xmlns:ns0="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas" w:macrosPresent="no" w:embeddedObjPresent="no" w:ocxPresent="no" xml:space="preserve"><w:ignoreSubtree w:val="http://schemas.microsoft.com/office/word/2003/wordml/sp2"/><o:DocumentProperties><o:Author>User</o:Author><o:LastAuthor>User</o:LastAuthor><o:Revision>350</o:Revision><o:TotalTime>100</o:TotalTime><o:LastPrinted>2012-11-13T05:13:00Z</o:LastPrinted><o:Created>2012-11-06T09:16:00Z</o:Created><o:LastSaved>2012-11-13T06:33:00Z</o:LastSaved><o:Pages>2</o:Pages><o:Words>12</o:Words><o:Characters>73</o:Characters><o:Company>China</o:Company><o:Lines>1</o:Lines><o:Paragraphs>1</o:Paragraphs><o:CharactersWithSpaces>84</o:CharactersWithSpaces><o:Version>12</o:Version></o:DocumentProperties><w:fonts><w:defaultFonts w:ascii="Times New Roman" w:fareast="宋体" w:h-ansi="Times New Roman" w:cs="Times New Roman"/><w:font w:name="Times New Roman"><w:panose-1 w:val="02020603050405020304"/><w:charset w:val="00"/><w:family w:val="Roman"/><w:pitch w:val="variable"/><w:sig w:usb-0="E0002AFF" w:usb-1="C0007841" w:usb-2="00000009" w:usb-3="00000000" w:csb-0="000001FF" w:csb-1="00000000"/></w:font><w:font w:name="宋体"><w:altName w:val="SimSun"/><w:panose-1 w:val="02010600030101010101"/><w:charset w:val="86"/><w:family w:val="auto"/><w:pitch w:val="variable"/><w:sig w:usb-0="00000003" w:usb-1="288F0000" w:usb-2="00000016" w:usb-3="00000000" w:csb-0="00040001" w:csb-1="00000000"/></w:font><w:font w:name="Cambria Math"><w:panose-1 w:val="02040503050406030204"/><w:charset w:val="01"/><w:family w:val="Roman"/><w:notTrueType/><w:pitch w:val="variable"/><w:sig w:usb-0="00000000" w:usb-1="00000000" w:usb-2="00000000" w:usb-3="00000000" w:csb-0="00000000" w:csb-1="00000000"/></w:font><w:font w:name="@宋体"><w:panose-1 w:val="02010600030101010101"/><w:charset w:val="86"/><w:family w:val="auto"/><w:pitch w:val="variable"/><w:sig w:usb-0="00000003" w:usb-1="288F0000" w:usb-2="00000016" w:usb-3="00000000" w:csb-0="00040001" w:csb-1="00000000"/></w:font></w:fonts><w:styles><w:versionOfBuiltInStylenames w:val="7"/><w:latentStyles w:defLockedState="off" w:latentStyleCount="267"><w:lsdException w:name="Normal"/><w:lsdException w:name="heading 1"/><w:lsdException w:name="heading 2"/><w:lsdException w:name="heading 3"/><w:lsdException w:name="heading 4"/><w:lsdException w:name="heading 5"/><w:lsdException w:name="heading 6"/><w:lsdException w:name="heading 7"/><w:lsdException w:name="heading 8"/><w:lsdException w:name="heading 9"/><w:lsdException w:name="caption"/><w:lsdException w:name="Title"/><w:lsdException w:name="Subtitle"/><w:lsdException w:name="Strong"/><w:lsdException w:name="Emphasis"/><w:lsdException w:name="No Spacing"/><w:lsdException w:name="List Paragraph"/><w:lsdException w:name="Quote"/><w:lsdException w:name="Intense Quote"/><w:lsdException w:name="Subtle Emphasis"/><w:lsdException w:name="Intense Emphasis"/><w:lsdException w:name="Subtle Reference"/><w:lsdException w:name="Intense Reference"/><w:lsdException w:name="Book Title"/><w:lsdException w:name="TOC Heading"/></w:latentStyles><w:style w:type="paragraph" w:default="on" w:styleId="a"><w:name w:val="Normal"/><wx:uiName wx:val="正文"/><w:rsid w:val="002D1C80"/><w:rPr><w:rFonts w:ascii="宋体" w:h-ansi="宋体" w:cs="宋体"/><wx:font wx:val="宋体"/><w:sz w:val="24"/><w:sz-cs w:val="24"/><w:lang w:val="EN-US" w:fareast="ZH-CN" w:bidi="AR-SA"/></w:rPr></w:style><w:style w:type="character" w:default="on" w:styleId="a0"><w:name w:val="Default Paragraph Font"/><wx:uiName wx:val="默认段落字体"/></w:style><w:style w:type="table" w:default="on" w:styleId="a1"><w:name w:val="Normal Table"/><wx:uiName wx:val="普通表格"/><w:rPr><wx:font wx:val="Times New Roman"/><w:lang w:val="EN-US" w:fareast="ZH-CN" w:bidi="AR-SA"/></w:rPr><w:tblPr><w:tblInd w:w="0" w:type="dxa"/><w:tblCellMar><w:top w:w="0" w:type="dxa"/><w:left w:w="108" w:type="dxa"/><w:bottom w:w="0" w:type="dxa"/><w:right w:w="108" w:type="dxa"/></w:tblCellMar></w:tblPr></w:style><w:style w:type="list" w:default="on" w:styleId="a2"><w:name w:val="No List"/><wx:uiName wx:val="无列表"/></w:style><w:style w:type="character" w:styleId="a3"><w:name w:val="Hyperlink"/><wx:uiName wx:val="超链接"/><w:rsid w:val="002D1C80"/><w:rPr><w:color w:val="0000FF"/><w:u w:val="single"/></w:rPr></w:style><w:style w:type="character" w:styleId="a4"><w:name w:val="FollowedHyperlink"/><wx:uiName wx:val="已访问的超链接"/><w:rsid w:val="002D1C80"/><w:rPr><w:color w:val="800080"/><w:u w:val="single"/></w:rPr></w:style><w:style w:type="paragraph" w:styleId="a5"><w:name w:val="header"/><wx:uiName wx:val="页眉"/><w:basedOn w:val="a"/><w:link w:val="Char"/><w:rsid w:val="002D1C80"/><w:pPr><w:pBdr><w:bottom w:val="single" w:sz="6" wx:bdrwidth="15" w:space="1" w:color="auto"/></w:pBdr><w:tabs><w:tab w:val="center" w:pos="4153"/><w:tab w:val="right" w:pos="8306"/></w:tabs><w:snapToGrid w:val="off"/><w:jc w:val="center"/></w:pPr><w:rPr><w:rFonts w:cs="Times New Roman" w:hint="fareast"/><wx:font wx:val="宋体"/><w:sz w:val="18"/><w:sz-cs w:val="18"/><w:lang/></w:rPr></w:style><w:style w:type="character" w:styleId="Char"><w:name w:val="页眉 Char"/><w:link w:val="a5"/><w:locked/><w:rsid w:val="002D1C80"/><w:rPr><w:rFonts w:ascii="宋体" w:fareast="宋体" w:h-ansi="宋体" w:cs="宋体" w:hint="fareast"/><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:style><w:style w:type="paragraph" w:styleId="a6"><w:name w:val="footer"/><wx:uiName wx:val="页脚"/><w:basedOn w:val="a"/><w:link w:val="Char0"/><w:rsid w:val="002D1C80"/><w:pPr><w:tabs><w:tab w:val="center" w:pos="4153"/><w:tab w:val="right" w:pos="8306"/></w:tabs><w:snapToGrid w:val="off"/></w:pPr><w:rPr><w:rFonts w:cs="Times New Roman" w:hint="fareast"/><wx:font wx:val="宋体"/><w:sz w:val="18"/><w:sz-cs w:val="18"/><w:lang/></w:rPr></w:style><w:style w:type="character" w:styleId="Char0"><w:name w:val="页脚 Char"/><w:link w:val="a6"/><w:locked/><w:rsid w:val="002D1C80"/><w:rPr><w:rFonts w:ascii="宋体" w:fareast="宋体" w:h-ansi="宋体" w:cs="宋体" w:hint="fareast"/><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:style><w:style w:type="paragraph" w:styleId="a7"><w:name w:val="Balloon Text"/><wx:uiName wx:val="批注框文本"/><w:basedOn w:val="a"/><w:link w:val="Char1"/><w:rsid w:val="002D1C80"/><w:rPr><w:rFonts w:cs="Times New Roman" w:hint="fareast"/><wx:font wx:val="宋体"/><w:sz w:val="18"/><w:sz-cs w:val="18"/><w:lang/></w:rPr></w:style><w:style w:type="character" w:styleId="Char1"><w:name w:val="批注框文本 Char"/><w:link w:val="a7"/><w:locked/><w:rsid w:val="002D1C80"/><w:rPr><w:rFonts w:ascii="宋体" w:fareast="宋体" w:h-ansi="宋体" w:cs="宋体" w:hint="fareast"/><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:style><w:style w:type="table" w:styleId="a8"><w:name w:val="Table Grid"/><wx:uiName wx:val="网格型"/><w:basedOn w:val="a1"/><w:rsid w:val="002D1C80"/><w:rPr><wx:font wx:val="Times New Roman"/></w:rPr><w:tblPr><w:tblInd w:w="0" w:type="dxa"/><w:tblBorders><w:top w:val="single" w:sz="4" wx:bdrwidth="10" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" wx:bdrwidth="10" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" wx:bdrwidth="10" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" wx:bdrwidth="10" w:space="0" w:color="auto"/><w:insideH w:val="single" w:sz="4" wx:bdrwidth="10" w:space="0" w:color="auto"/><w:insideV w:val="single" w:sz="4" wx:bdrwidth="10" w:space="0" w:color="auto"/></w:tblBorders><w:tblCellMar><w:top w:w="0" w:type="dxa"/><w:left w:w="108" w:type="dxa"/><w:bottom w:w="0" w:type="dxa"/><w:right w:w="108" w:type="dxa"/></w:tblCellMar></w:tblPr></w:style></w:styles><w:shapeDefaults><o:shapedefaults v:ext="edit" spidmax="16386"/><o:shapelayout v:ext="edit"><o:idmap v:ext="edit" data="1"/></o:shapelayout></w:shapeDefaults><w:docPr><w:view w:val="print"/><w:zoom w:percent="120"/><w:doNotEmbedSystemFonts/><w:bordersDontSurroundHeader/><w:bordersDontSurroundFooter/><w:proofState w:spelling="clean" w:grammar="clean"/><w:defaultTabStop w:val="420"/><w:drawingGridHorizontalSpacing w:val="120"/><w:drawingGridVerticalSpacing w:val="163"/><w:displayHorizontalDrawingGridEvery w:val="2"/><w:displayVerticalDrawingGridEvery w:val="2"/><w:characterSpacingControl w:val="DontCompress"/><w:webPageEncoding w:val="unicode"/><w:optimizeForBrowser/><w:validateAgainstSchema/><w:saveInvalidXML w:val="off"/><w:ignoreMixedContent w:val="off"/><w:alwaysShowPlaceholderText w:val="off"/><w:hdrShapeDefaults><o:shapedefaults v:ext="edit" spidmax="16386"/></w:hdrShapeDefaults><w:footnotePr><w:footnote w:type="separator"><w:p wsp:rsidR="00C16E91" wsp:rsidRDefault="00C16E91" wsp:rsidP="00BC5206"><w:r><w:separator/></w:r></w:p></w:footnote><w:footnote w:type="continuation-separator"><w:p wsp:rsidR="00C16E91" wsp:rsidRDefault="00C16E91" wsp:rsidP="00BC5206"><w:r><w:continuationSeparator/></w:r></w:p></w:footnote></w:footnotePr><w:endnotePr><w:endnote w:type="separator"><w:p wsp:rsidR="00C16E91" wsp:rsidRDefault="00C16E91" wsp:rsidP="00BC5206"><w:r><w:separator/></w:r></w:p></w:endnote><w:endnote w:type="continuation-separator"><w:p wsp:rsidR="00C16E91" wsp:rsidRDefault="00C16E91" wsp:rsidP="00BC5206"><w:r><w:continuationSeparator/></w:r></w:p></w:endnote></w:endnotePr><w:compat><w:breakWrappedTables/><w:snapToGridInCell/><w:wrapTextWithPunct/><w:useAsianBreakRules/><w:dontGrowAutofit/><w:useFELayout/></w:compat><wsp:rsids><wsp:rsidRoot wsp:val="00BC5206"/><wsp:rsid wsp:val="0000354B"/><wsp:rsid wsp:val="000C0E2F"/><wsp:rsid wsp:val="000E422E"/><wsp:rsid wsp:val="000E6367"/><wsp:rsid wsp:val="000F54B6"/><wsp:rsid wsp:val="00107C83"/><wsp:rsid wsp:val="00116C4A"/><wsp:rsid wsp:val="0014104D"/><wsp:rsid wsp:val="00174635"/><wsp:rsid wsp:val="00176D84"/><wsp:rsid wsp:val="00181050"/><wsp:rsid wsp:val="00195BE3"/><wsp:rsid wsp:val="001A327E"/><wsp:rsid wsp:val="001D325E"/><wsp:rsid wsp:val="001E6BEF"/><wsp:rsid wsp:val="001F0474"/><wsp:rsid wsp:val="00211B9C"/><wsp:rsid wsp:val="00250603"/><wsp:rsid wsp:val="00277027"/><wsp:rsid wsp:val="0028265D"/><wsp:rsid wsp:val="002A1028"/><wsp:rsid wsp:val="002A1100"/><wsp:rsid wsp:val="002B44EF"/><wsp:rsid wsp:val="002D1C80"/><wsp:rsid wsp:val="002D2D14"/><wsp:rsid wsp:val="002D30F3"/><wsp:rsid wsp:val="002E3C7F"/><wsp:rsid wsp:val="0031436E"/><wsp:rsid wsp:val="00314D7D"/><wsp:rsid wsp:val="00325C72"/><wsp:rsid wsp:val="00332548"/><wsp:rsid wsp:val="003345C3"/><wsp:rsid wsp:val="00350D8B"/><wsp:rsid wsp:val="00354D18"/><wsp:rsid wsp:val="00393107"/><wsp:rsid wsp:val="003B4525"/><wsp:rsid wsp:val="003C6848"/><wsp:rsid wsp:val="003D60C5"/><wsp:rsid wsp:val="00445F97"/><wsp:rsid wsp:val="00454D71"/><wsp:rsid wsp:val="00455081"/><wsp:rsid wsp:val="0048361E"/><wsp:rsid wsp:val="004F03C8"/><wsp:rsid wsp:val="00514B65"/><wsp:rsid wsp:val="00520BE8"/><wsp:rsid wsp:val="00520DCA"/><wsp:rsid wsp:val="00575205"/><wsp:rsid wsp:val="00576B5E"/><wsp:rsid wsp:val="0058588A"/><wsp:rsid wsp:val="005C366A"/><wsp:rsid wsp:val="006312C1"/><wsp:rsid wsp:val="00631A45"/><wsp:rsid wsp:val="00631D06"/><wsp:rsid wsp:val="0066489E"/><wsp:rsid wsp:val="00672486"/><wsp:rsid wsp:val="006835E1"/><wsp:rsid wsp:val="00687174"/><wsp:rsid wsp:val="00694F4F"/><wsp:rsid wsp:val="006D4F3D"/><wsp:rsid wsp:val="0070290C"/><wsp:rsid wsp:val="007111C5"/><wsp:rsid wsp:val="00716C80"/><wsp:rsid wsp:val="0072094D"/><wsp:rsid wsp:val="00725351"/><wsp:rsid wsp:val="007400A6"/><wsp:rsid wsp:val="00745FB3"/><wsp:rsid wsp:val="00772766"/><wsp:rsid wsp:val="00777992"/><wsp:rsid wsp:val="00824F8E"/><wsp:rsid wsp:val="00830546"/><wsp:rsid wsp:val="00830FCD"/><wsp:rsid wsp:val="0085243E"/><wsp:rsid wsp:val="00863CC4"/><wsp:rsid wsp:val="00870A77"/><wsp:rsid wsp:val="008B146D"/><wsp:rsid wsp:val="008E00F9"/><wsp:rsid wsp:val="008E7994"/><wsp:rsid wsp:val="008F570E"/><wsp:rsid wsp:val="009307BB"/><wsp:rsid wsp:val="00942364"/><wsp:rsid wsp:val="0099674D"/><wsp:rsid wsp:val="009D7E68"/><wsp:rsid wsp:val="009F403C"/><wsp:rsid wsp:val="00A06056"/><wsp:rsid wsp:val="00A22638"/><wsp:rsid wsp:val="00A34021"/><wsp:rsid wsp:val="00A47DEE"/><wsp:rsid wsp:val="00A61853"/><wsp:rsid wsp:val="00A74AE6"/><wsp:rsid wsp:val="00AD541A"/><wsp:rsid wsp:val="00AD7097"/><wsp:rsid wsp:val="00AE533D"/><wsp:rsid wsp:val="00AE5B4A"/><wsp:rsid wsp:val="00AE70DA"/><wsp:rsid wsp:val="00B21D03"/><wsp:rsid wsp:val="00B227CE"/><wsp:rsid wsp:val="00B5642B"/><wsp:rsid wsp:val="00B57668"/><wsp:rsid wsp:val="00B70A9B"/><wsp:rsid wsp:val="00BC5206"/><wsp:rsid wsp:val="00C034A1"/><wsp:rsid wsp:val="00C16E91"/><wsp:rsid wsp:val="00C33B51"/><wsp:rsid wsp:val="00C45DC7"/><wsp:rsid wsp:val="00C52264"/><wsp:rsid wsp:val="00C80BE2"/><wsp:rsid wsp:val="00C81DB6"/><wsp:rsid wsp:val="00C90792"/><wsp:rsid wsp:val="00CC1C18"/><wsp:rsid wsp:val="00CD0BE6"/><wsp:rsid wsp:val="00D253C2"/><wsp:rsid wsp:val="00D6107F"/><wsp:rsid wsp:val="00D86D35"/><wsp:rsid wsp:val="00DC18C6"/><wsp:rsid wsp:val="00DC2C10"/><wsp:rsid wsp:val="00DC3C49"/><wsp:rsid wsp:val="00DD453F"/><wsp:rsid wsp:val="00DD464D"/><wsp:rsid wsp:val="00E4788F"/><wsp:rsid wsp:val="00EB1464"/><wsp:rsid wsp:val="00ED0357"/><wsp:rsid wsp:val="00EE1961"/><wsp:rsid wsp:val="00EF22D5"/><wsp:rsid wsp:val="00F208B7"/><wsp:rsid wsp:val="00F23C7F"/><wsp:rsid wsp:val="00F83B97"/><wsp:rsid wsp:val="00F86105"/><wsp:rsid wsp:val="00FA6B08"/><wsp:rsid wsp:val="00FF4353"/></wsp:rsids></w:docPr><w:body>
head;
        $body  = <<<body
<w:p wsp:rsidR="00454D71" wsp:rsidRPr="00A74AE6" wsp:rsidRDefault="00454D71" wsp:rsidP="00454D71"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="59" w:left="142" w:right-chars="57" w:right="137"/><w:rPr><w:b/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r wsp:rsidRPr="00A74AE6"><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>回访编号：</w:t></w:r><w:proofErr w:type="gramStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>num</w:t></w:r><w:proofErr w:type="gramEnd"/></w:p><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="00454D71"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="59" w:left="142" w:right-chars="57" w:right="137"/><w:rPr><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:pPr></w:p><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="00454D71"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="118" w:left="283" w:right-chars="57" w:right="137"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr></w:p><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="00454D71"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:rPr><w:sz w:val="16"/><w:sz-cs w:val="16"/></w:rPr></w:pPr></w:p><w:tbl><w:tblPr><w:tblW w:w="9663" w:type="dxa"/><w:jc w:val="center"/><w:tblLayout w:type="Fixed"/><w:tblLook w:val="04A0"/></w:tblPr><w:tblGrid><w:gridCol w:w="613"/><w:gridCol w:w="5478"/><w:gridCol w:w="3572"/></w:tblGrid><w:tr wsp:rsidR="00454D71" wsp:rsidTr="000F26CD"><w:trPr><w:trHeight w:h-rule="exact" w:val="404"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge w:val="restart"/><w:textFlow w:val="tb-rl-v"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:ind w:left="113" w:right="113"/><w:jc w:val="center"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="00A74AE6" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="500" w:first-line="1050"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>mchtname</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="0014104D" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>mchtid</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr><w:tr wsp:rsidR="00454D71" wsp:rsidTr="000F26CD"><w:trPr><w:trHeight w:h-rule="exact" w:val="409"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="700" w:first-line="1050"/><w:rPr><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="15"/><w:sz-cs w:val="15"/></w:rPr><w:t>mchtaddr</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>contactor</w:t></w:r></w:p></w:tc></w:tr><w:tr wsp:rsidR="00454D71" wsp:rsidTr="000F26CD"><w:trPr><w:trHeight w:h-rule="exact" w:val="399"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="500" w:first-line="1050"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>rep</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>inter_time</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr><w:tr wsp:rsidR="00454D71" wsp:rsidTr="000F26CD"><w:trPr><w:trHeight w:h-rule="exact" w:val="402"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="500" w:first-line="1050"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>account</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="00454D71" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00454D71" wsp:rsidP="000F26CD"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>tel</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr></w:tbl><w:p wsp:rsidR="003C6848" wsp:rsidRPr="000C0E2F" wsp:rsidRDefault="003C6848" wsp:rsidP="000C0E2F"/>
body;
        $body_first=<<<bfi
<w:p wsp:rsidR="00AD7097" wsp:rsidRDefault="00AD7097" wsp:rsidP="00824F8E"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="59" w:left="142" w:right-chars="57" w:right="137"/><w:rPr><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:pPr></w:p><w:p wsp:rsidR="00332548" wsp:rsidRPr="00A74AE6" wsp:rsidRDefault="00332548" wsp:rsidP="00745FB3"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="59" w:left="142" w:right-chars="57" w:right="137"/><w:rPr><w:b/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r wsp:rsidRPr="00A74AE6"><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>回访编号：</w:t></w:r><w:proofErr w:type="gramStart"/><w:r wsp:rsidR="00EB1464"><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>num</w:t></w:r><w:proofErr w:type="gramEnd"/></w:p><w:p wsp:rsidR="00AD7097" wsp:rsidRDefault="00AD7097" wsp:rsidP="00824F8E"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="59" w:left="142" w:right-chars="57" w:right="137"/><w:rPr><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:pPr></w:p><w:p wsp:rsidR="00A74AE6" wsp:rsidRDefault="00A74AE6" wsp:rsidP="00A74AE6"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="118" w:left="283" w:right-chars="57" w:right="137"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr></w:p><w:p wsp:rsidR="001D325E" wsp:rsidRDefault="001D325E"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:rPr><w:sz w:val="16"/><w:sz-cs w:val="16"/></w:rPr></w:pPr></w:p><w:tbl><w:tblPr><w:tblW w:w="9663" w:type="dxa"/><w:jc w:val="center"/><w:tblLayout w:type="Fixed"/><w:tblLook w:val="04A0"/></w:tblPr><w:tblGrid><w:gridCol w:w="613"/><w:gridCol w:w="5478"/><w:gridCol w:w="3572"/></w:tblGrid><w:tr wsp:rsidR="003C6848" wsp:rsidTr="001F0474"><w:trPr><w:trHeight w:h-rule="exact" w:val="404"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge w:val="restart"/><w:textFlow w:val="tb-rl-v"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRDefault="003C6848" wsp:rsidP="00863CC4"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:ind w:left="113" w:right="113"/><w:jc w:val="center"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="00A74AE6" wsp:rsidRDefault="0085243E" wsp:rsidP="00863CC4"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="500" w:first-line="1050"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>mchtname</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="0014104D" wsp:rsidRDefault="00250603" wsp:rsidP="0014104D"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>mchtid</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr><w:tr wsp:rsidR="003C6848" wsp:rsidTr="001F0474"><w:trPr><w:trHeight w:h-rule="exact" w:val="409"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRDefault="003C6848" wsp:rsidP="00863CC4"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRDefault="00250603" wsp:rsidP="00863CC4"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="700" w:first-line="1050"/><w:rPr><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="15"/><w:sz-cs w:val="15"/></w:rPr><w:t>mchtaddr</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00250603" wsp:rsidP="006D4F3D"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>contactor</w:t></w:r></w:p></w:tc></w:tr><w:tr wsp:rsidR="003C6848" wsp:rsidTr="001F0474"><w:trPr><w:trHeight w:h-rule="exact" w:val="399"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRDefault="003C6848" wsp:rsidP="00863CC4"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00250603" wsp:rsidP="006D4F3D"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="500" w:first-line="1050"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>rep</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00250603" wsp:rsidP="006D4F3D"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>inter_time</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr><w:tr wsp:rsidR="003C6848" wsp:rsidTr="001F0474"><w:trPr><w:trHeight w:h-rule="exact" w:val="402"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="613" w:type="dxa"/><w:vmerge/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRDefault="003C6848" wsp:rsidP="00863CC4"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/><w:rPr><w:b/></w:rPr></w:pPr></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="5478" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00250603" wsp:rsidP="006D4F3D"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="500" w:first-line="1050"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>account</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3572" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr><w:p wsp:rsidR="003C6848" wsp:rsidRPr="006D4F3D" wsp:rsidRDefault="00C52264" wsp:rsidP="006D4F3D"><w:pPr><w:spacing w:line="300" w:line-rule="exact"/><w:ind w:first-line-chars="400" w:first-line="840"/><w:rPr><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:hint="fareast"/><w:sz w:val="21"/><w:sz-cs w:val="21"/></w:rPr><w:t>tel</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr></w:tbl><w:p wsp:rsidR="003C6848" wsp:rsidRDefault="003C6848" wsp:rsidP="003B4C75"><w:pPr><w:spacing w:line="180" w:line-rule="exact"/></w:pPr></w:p>
bfi;
        $foot = <<<foot
<w:sectPr wsp:rsidR="003C6848" wsp:rsidSect="00824F8E"><w:pgSz w:w="11906" w:h="16838"/><w:pgMar w:top="851" w:right="851" w:bottom="851" w:left="851" w:header="851" w:footer="992" w:gutter="0"/><w:cols w:space="425"/><w:docGrid w:type="lines" w:line-pitch="326"/></w:sectPr></w:body></w:wordDocument>
foot;
        $page =<<<page
<w:p wsp:rsidR="00454D71" wsp:rsidRDefault="00454D71" wsp:rsidP="00454D71"><w:pPr><w:spacing w:line="0" w:line-rule="at-least"/><w:ind w:left-chars="59" w:left="142" w:right-chars="57" w:right="137"/><w:rPr><w:sz w:val="18"/><w:sz-cs w:val="18"/></w:rPr></w:pPr><w:r><w:br w:type="page"/></w:r></w:p>
page;

        return array('head'=>$head,'body'=>$body,'body_first'=>$body_first,'foot'=>$foot,'page'=>$page);
    }


    //将字符串转化成日期datetime
    public static function StrToTime($date_str){
        do{
            //20120301
            $regex = '/^\d+$/';
            $matches = array();
            if(preg_match($regex, $date_str, $matches)){
                if (strlen($date_str) >= 6) {
                    $y = substr($date_str, 0, 4);
                    $m = substr($date_str, 4, 2);
                    try {
                        $d = substr($date_str, 6, 2);
                    } catch (ErrorException $e) {
                        $d = "01";
                    }
                    $datetime = strtotime($y . "-" . $m . "-" . $d);
                    break;
                }
            }

            //2012-03-01
            if($datetime=strtotime($date_str)){
                break;
            }

            //2012.03.01or2012.03
            if($datetime=strtotime(preg_replace("/[.。]/",'-',$date_str))){
                break;
            }

            //2012.03.01-2016.03.01
            $regex = '/^(\d[\d.-。]+\d)-(\d[\d.-。]+\d)$/i';
            $matches = array();
            if(preg_match($regex, $date_str, $matches)){
                $datetime=$matches[2];
                $datetime=strtotime(preg_replace("/[.。]/",'-',$datetime));
                break;
            }
            return false;

        }while(false);

        return $datetime;
    }

    public static function SeasonRang($s){
        if($s=="上半年"){
            return array("start_m"=>'01-1',"end_m"=>"06-30",);
        }
        if($s=="下半年"){
            return array("start_m"=>'07-1',"end_m"=>"12-31",);
        }
        if($s=="一季度"){
            return array("start_m"=>'01-1',"end_m"=>"03-31",);
        }
        if($s=="二季度"){
            return array("start_m"=>'04-1',"end_m"=>"06-30",);
        }
        if($s=="三季度"){
            return array("start_m"=>'07-1',"end_m"=>"09-30",);
        }
        if($s=="四季度"){
            return array("start_m"=>'10-1',"end_m"=>"12-31",);
        }
        return false;
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

    public static function fileUpload($file,$type,$dir) {
        $return = array ();

        $full_dir = Yii::app ()->params ['upload_file_path'] . '/' . $dir;
        $fname = $file ['name'];
        $ftype = substr ( strrchr ( $fname, '.' ), 1 );
        if ($file ['error'] > 0) {
            $return ['status'] = '-1';
            $return ['desc'] = '上传文件失败';
        } elseif (empty ( $fname )) {
            $return ['status'] = '-2';
            $return ['desc'] = '没有上传任何文件';
        } elseif (! in_array ( $ftype, $type )) {
            $return ['status'] = '-3';
            $return ['desc'] = '上传文件格式不符合要求';
        } elseif ($file ['size'] > (Yii::app ()->params ['upload_file_maxsize']) * 1024 * 1024) {
            $return ['status'] = '-4';
            $return ['desc'] = '上传文件太大了';
        } elseif ($file ['size'] == 0) {
            $return ['status'] = '-5';
            $return ['desc'] = '上传文件不能为空';
        } else {
            $rname = time().rand(0,1000);
            $dname = $rname . '.' . $ftype;
            if (! is_dir ( $full_dir )) {
                umask ( 0000 );
                mkdir ( $full_dir, 0777, true );
            }
            if (file_exists ( $full_dir )) {
                $path = $full_dir . '/' . $dname;
                if (move_uploaded_file ( $file ['tmp_name'], $path )) {
                    $return ['status'] = 0;
                    $return ['desc'] = '文件上传成功';
                    $return ['path'] = $dir . '/' . $dname;
                    $return ['upname'] = $fname;
                    $return ['savename'] = $dname;
                } else {
                    $return ['status'] = '-6';
                    $return ['desc'] = '服务器繁忙，上传失败';
                }

            } else {
                $return ['status'] = '-7';
                $return ['desc'] = '没有找到相应的上传目录';
            }
        }
        return $return;
    }

    public static function SendMsg($number,$code){
        $data=<<< eof
<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Header>
    <Account xmlns="http://219.238.160.104/qxt95/">
      <User>nmdxyzm</User>
      <PassWord>123456</PassWord>
    </Account>
  </soap12:Header>
  <soap12:Body>
    <SendSms xmlns="http://219.238.160.104/qxt95/">
      <AppendID></AppendID>
      <DesMobile>{$number}</DesMobile>
      <Content>【网上砍价】您砍价的验证码是：{$code}</Content>
    </SendSms>
  </soap12:Body>
</soap12:Envelope>
eof;
        $url="http://219.238.160.104/qxt95/qxt95.asmx";
        $tuCurl = curl_init();
        curl_setopt($tuCurl, CURLOPT_URL,$url);
        curl_setopt($tuCurl, CURLOPT_HEADER,false);
        curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($tuCurl, CURLOPT_HTTPHEADER, array("Content-Type: application/soap+xml; charset=utf-8","SOAPAction: http://219.238.160.104/qxt95/SendSms", "Content-length: ".strlen($data)));
        curl_setopt($tuCurl, CURLOPT_POST,true);
        curl_setopt($tuCurl, CURLOPT_POSTFIELDS,$data);
        $tuData = curl_exec($tuCurl);
        return $tuData;
    }

    public static function HiddenPhone($phone){
        return substr_replace($phone,"****",3,4);
    }
}