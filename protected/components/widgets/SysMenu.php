<?php
/*
 * @author Su DunKuai <sudk@trunkbow.com>
 * @version $Id: SysMenu.php 2597 $
 * @package application.components.widgets
 * @since 1.1.1
 */
class SysMenu extends CWidget {

    public function init() {
//        if(isset(Yii::app()->user->auths) && count(Yii::app()->user->auths)>0)
//        {
//            $authManager=Yii::app()->authManager;
//            $userId=Yii::app()->user->id;
//            foreach(Yii::app()->user->auths as $authid)
//            {
//                try{
//                    $authManager->assign($authid,$userId);
//                } catch (Exception $e) {
//                    continue;
//                }
//            }
//        }
    }

    public function Menu() {

        $menus = array();

        $sub_menu = array();
        $sub_menu[] = array("title" => "操作员管理", "url" => "./?r=sys/operator/list", "match" => array('sys\/operator\/list','sys\/operator\/new','sys\/operator\/edit'));

        //$sub_menu[] = array("title" => "机构管理", "url" => "./?r=sys/institute/list", "match" => array('sys\/institute\/list','sys\/institute\/new','sys\/institute\/edit'));

        //$sub_menu[] = array("title" => "任务监控", "url" => "#", "match" => '');

        //$sub_menu[] = array("title" => "日志", "url" => "#", "match" => '');
        $menus['operator'] = array("title" => "系统管理",'ico'=>'fa-gear',"child" => $sub_menu);

        //终端管理
        $sub_menu = array();
        $sub_menu[] = array("title" => "商品管理", "url" => "./?r=bargain/goods/list", "match" => array('bargain\/goods\/list','bargain\/goods\/edit','bargain\/goods\/new'));
        $sub_menu[] = array("title" => "砍价统计", "url" => "./?r=bargain/price/list", "match" => array('bargain\/price\/list'));

        $menus['posm'] = array("title" => "砍价管理",'ico'=>'fa-hdd-o',"child" => $sub_menu);

        
        return $menus;
    }

    public function run() {
        $name = Yii::app()->user->id;
        $logo=Yii::app()->user->getState('logo');
        echo <<<EOF
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="{$logo}" class="img-circle" alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p>您好, {$name}</p>
    
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- search form -->
                    <form action="#" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Search..."/>
                            <span class="input-group-btn">
                                <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="active">
                            <a href="./index.php?r=dboard">
                                <i class="fa fa-dashboard"></i> <span>首页</span>
                            </a>
                        </li>
EOF;
        echo self::showMenu();
        echo "</ul> </section>";
    }

    public function showMenu() {
        $menus = self::Menu();
        $r = $_REQUEST["r"];
        $html_str = "";
        if (count($menus) > 0) {
            foreach ($menus as $id => $menu) {
                $current_menu = false;
                $sub_menus = $menu["child"];
                $sub_str = "";
                if (count($sub_menus) > 0) {
                    foreach ($sub_menus as $k => $sub_menu) {
                        $sub_url = $sub_menu["url"];
                        if ($sub_menu['match'] != '' && self::menuMatch($sub_menu['match'], $r)) {
                            $current_menu = true;
                            $sub_str .= "<li class='active'>";
                        } else {
                            $sub_str .= "<li>";
                        }
                        $sub_str .= "<a href='{$sub_url}'>";
                        $sub_str .= "<i class='fa fa-angle-double-right'></i>";
                        $sub_str .= $sub_menu["title"] . "</a></li>";
                    }
                }
                if ($current_menu == true)
                    $html_str .= "<li class='treeview active'>";
                else
                    $html_str .= "<li class='treeview'>";
                $html_str .= "<a href='#'>";
                $html_str .= "<i class='fa ".$menu['ico']."'></i>";
                $html_str .= "<span>{$menu['title']}</span>";
                $html_str .= "<i class='fa fa-angle-left pull-right'></i>";
                $html_str .= "</a>";

                if ($current_menu == true)
                    $html_str .= "<ul class='treeview-menu' style='display:block;'>";
                else
                    $html_str .= "<ul class='treeview-menu'>";

                $html_str .= $sub_str;

                $html_str .= "</ul></li>";
            }
        }
        return $html_str;
    }

    public function menuMatch($match, $r) {
        if (!$match) {
            return false;
        }
        if (is_array($match)) {
            foreach ($match as $v) {
                if (preg_match('/\b' . $v . '\b/', $r)) {
                    return true;
                }
            }
        } else {
            return preg_match('/\b' . $match . '\b/', $r);
        }
    }

}
