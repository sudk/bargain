<?php

/**
 * Description of Statsystem
 *
 * @author sudk
 */
class TransrecordCommand extends CConsoleCommand
{
    private $task_path="/opt/posm/task";
    /*获得刷卡记录*/
    public function actionGet($mchtid="",$recorddate="")
    {
        if($recorddate=="")
        {
            $recorddate=date("Ymd",strtotime("-1 days"));

        }
        echo '开始获取交易记录,可能要花几分钟时间....';
        $r=Transrecord::getRemoteData($recorddate,$mchtid);
        print_r($r);
    }
    public function actionCheck($recorddate="")
    {
        if($recorddate=="")
        {
            $recorddate=date("Ymd",strtotime("-1 days"));

        }
        echo '开始获取交易记录,可能要花几分钟时间....';
        Transrecord::checkRemoteData($recorddate);
        echo "获取交易记录完成！";
    }

    public function actionInserttask(){
        date_default_timezone_set('Asia/Shanghai');
        $inserting=".inserting";
        
        while(true) {
            try{
                //打开 images 目录
                $dir = opendir($this->task_path);

                //列出 images 目录中的文件
                while (($file_name = readdir($dir)) !== false)
                {
                    $file_name=$this->task_path."/".$file_name;
                    if (strpos($file_name,$inserting)) {
                        Transrecord::InsertTask($file_name);
                        rename($file_name,str_replace($inserting,'.done',$file_name));
                        echo "\n".$file_name." done";
                    }else{
                        continue;
                    }
                }
                closedir($dir);
                echo "\nwaiting for next task!";
                sleep(10);

            }catch(Exception $e){
                echo $e;
            }
        }
        
    }

    public function actionMakedailytask($d=""){
        date_default_timezone_set('Asia/Shanghai');
        if ($d=="") {
            $d=date("Ymd",strtotime("-1 day"));
        }

        // $fo=fopen($task_file, "w");
        // $tran=new Transrecord();
        // $list=$tran->getMchtidList();
        // echo "start to create mchtlist\n";
        // foreach ($list as $row) {
        //     # code...
        //     if(!trim($row['mchtid'])){
        //         continue;
        //     }
        //     fwrite($fo,trim($row['mchtid'])."|".$d."\n");
        // }
        // fclose($fo);
        echo "start to create mchtlist\n";
        TransrecordTask::addTask($d,'每日定时任务'.$d);
        echo "create mchtlist complete\n";
    }

    public function actionBackup(){
        $sql="show table status";
        $rows=Yii::app()->db->createCommand($sql)->queryAll();
        $transrecord_tb=array();
        foreach($rows as $row){
            $tb_name=$row['Name'];
            if(substr($tb_name,0,11)=="transrecord"&&strlen($tb_name)>11){
                $transrecord_tb[]=$tb_name;
            }
        }
        $current_backup_tb="";
        if(count($transrecord_tb)){
            $current_backup_tb="transrecord_".(count($transrecord_tb)+1);
        }else{
            $current_backup_tb="transrecord_1";
        }
        Yii::app()->db->createCommand('create table if not exists `'.$current_backup_tb.'` like transrecord')->execute();
        
    }

    

}
