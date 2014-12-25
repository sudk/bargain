<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wangdy
 * Date: 12-1-29
 * Time: 下午1:59
 * To change this template use File | Settings | File Templates.
 */
class ReflectCommand extends CConsoleCommand
{
    public function actionRun($m)
    {
        $a = $this->getControllersActions($m);
        //var_dump($a);
        foreach($a as $action)
        {
            echo "$action\n";
        }
        echo "\n";
    }

    private function getControllersActions($module)
    {

        $controllerPath = Yii::app()->basePath.'/modules/'.$module.'/controllers';
        $a = array();

        Yii::import('application.modules.'.$module.'.controllers.*');


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
}
