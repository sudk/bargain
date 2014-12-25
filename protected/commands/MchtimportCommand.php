<?php

/**
 * Description of MchtimportCommand
 * 批量导入商户和终端
 * @author liuxy
 */
class MchtimportCommand extends CConsoleCommand {

    public function actionImport() {
        while (true) {
            try {
                //第一步：从任务列表中取向待处理的任务
                $task = $this->getTask();

                //第二步：读取Excel，生成数据
                if ($task != null) {
                    $data = $this->readExcel($task);
                    //第三步：往表里送数据
                    if (!empty($data)) {
                        $r = $this->batchImport($data);
                    }
                }
                echo "waiting for next task!\n";
                sleep(10);
            } catch (Exception $e) {
                echo $e;
            }
        }//while end
    }

    /**
     * 取一个任务
     * @return type
     */
    public function getTask() {
        echo "read task ...\n";
        echo date('Y-m-d H:i:s') . "\n";

        $sql = 'SELECT * FROM pm_task WHERE status=-1 ORDER BY dttm DESC ';
        $command = Yii::app()->db->createCommand($sql);
        $row = $command->queryRow();

        if ($row == null) {
            echo 'row=';
            var_dump($row);
            return null;
        }

        echo "task_id=" . $row['task_id'] . "\n";

        if ($row['file_name'] == ''):

            //更改任务的状态
            $sql = 'UPDATE pm_task SET status=-2 WHERE task_id=:task_id';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(":task_id", $row['task_id'], PDO::PARAM_INT);
            $result = $command->execute();
            echo "change task status -2 ……\n";
            var_dump($result);
        
            echo "file_name is null.\n";
            return null;
        endif;

        return $row;
    }

    /**
     * 读取数据
     * @param type $task
     * @return type
     */
    public function readExcel($task) {

        $data = array(); //返回结果数据

        spl_autoload_unregister(array('YiiBase', 'autoload')); //关闭yii的自动加载功能
        Yii::import('application.extensions.PHPExcel.PHPExcel', true);

        /* 默认用excel2007读取excel，若格式不对，则用之前的版本进行读取 */
        $PHPReader = new PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($task['file_name'])) {
            $PHPReader = new PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($task['file_name'])) {
                echo 'no Excel\n';
                return;
            }
        }
        echo "load Excel……\n";

        //更改任务的状态
        $sql = 'UPDATE pm_task SET status=-3 WHERE task_id=:task_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":task_id", $task['task_id'], PDO::PARAM_INT);
        $result = $command->execute();
        echo "change task status -3 ……\n";
        var_dump($result);

        $PHPExcel = $PHPReader->load($task['file_name']);
        /* 读取excel文件中的第一个工作表 */
        $currentSheet = $PHPExcel->getSheet(0);
        /* 取得最大的列号 */
        $allColumn = $currentSheet->getHighestColumn();
        /* 取得一共有多少行 */
        $allRow = $currentSheet->getHighestRow();

        for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {

            $temp = array();

            $temp['file_name'] = $task['file_name'];

            //商户
            $temp['task_id'] = $task['task_id'];
            $temp['mcht_id'] = $currentSheet->getCellByColumnAndRow(0, $currentRow)->getValue(); //商户编号
            $temp['mcht_name'] = $currentSheet->getCellByColumnAndRow(1, $currentRow)->getValue(); //商户名称	
            $temp['mcht_address'] = $currentSheet->getCellByColumnAndRow(2, $currentRow)->getValue(); //商户地址	
            $temp['mcht_contact'] = $currentSheet->getCellByColumnAndRow(3, $currentRow)->getValue(); //联系人	
            $temp['mcht_phone'] = $currentSheet->getCellByColumnAndRow(4, $currentRow)->getValue(); //联系电话	
            $temp['mcht_sub_inst'] = $currentSheet->getCellByColumnAndRow(5, $currentRow)->getValue(); //收单机构支行编号	
            $temp['mcht_license'] = $currentSheet->getCellByColumnAndRow(6, $currentRow)->getValue(); //营业执照号	
            $temp['mcht_rep'] = $currentSheet->getCellByColumnAndRow(7, $currentRow)->getValue(); //法人代表	
            $temp['mcht_repnum'] = $currentSheet->getCellByColumnAndRow(8, $currentRow)->getValue(); //法人代表身份证号	
            $temp['mcht_taxnum'] = $currentSheet->getCellByColumnAndRow(9, $currentRow)->getValue(); //税务登记号	
            $temp['mcht_regcapital'] = $currentSheet->getCellByColumnAndRow(10, $currentRow)->getValue(); //注册资金（单位：元）	
            $temp['mcht_products'] = $currentSheet->getCellByColumnAndRow(11, $currentRow)->getValue(); //营业范围	
            $temp['mcht_account'] = $currentSheet->getCellByColumnAndRow(12, $currentRow)->getValue(); //银行账号	
            $temp['mcht_open_time'] = $currentSheet->getCellByColumnAndRow(13, $currentRow)->getValue(); //商户入网时间	
            $temp['mcht_acq_inst'] = $task['institute'];
            $temp['mcht_operator'] = $task['operator_id'];

            //终端
            $temp['pos_id'] = $currentSheet->getCellByColumnAndRow(14, $currentRow)->getValue(); //终端编号	
            $temp['pos_type'] = $currentSheet->getCellByColumnAndRow(15, $currentRow)->getValue(); //终端类型	
            $temp['pos_address'] = $currentSheet->getCellByColumnAndRow(16, $currentRow)->getValue(); //装机地址	
            $temp['pos_phone'] = $currentSheet->getCellByColumnAndRow(17, $currentRow)->getValue(); //电话号码	
            $temp['pos_mtn_inst'] = $currentSheet->getCellByColumnAndRow(18, $currentRow)->getValue(); //维护方编号	
            $temp['pos_open_time'] = $currentSheet->getCellByColumnAndRow(19, $currentRow)->getValue(); //终端入网时间
            $temp['pos_operator'] = $task['operator_id'];
            $data[] = $temp;
        }
        spl_autoload_register(array('YiiBase', 'autoload')); //打开yii的自动加载功能
        return $data;
    }

    /**
     * 批量导入数据
     */
    public function batchImport($data) {

        if (empty($data) or ! is_array($data)) {
            $r['error'] = "data list is null.\n";
            $r['refresh'] = false;
            return $r;
        }
        foreach ($data as $key => $row) {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                var_dump($row);

                //商户
                $sql = "REPLACE INTO pm_mcht(id,name,address,contact,phone,acq_inst,open_time,operator,"
                        . "areacode,sub_inst,license,rep,repnum,taxnum,regcapital,products,account,status) "
                        . " VALUES(:id,:name,:address,:contact,:phone,:acq_inst,:open_time,:operator,"
                        . ":areacode,:sub_inst,:license,:rep,:repnum,:taxnum,:regcapital,:products,:account,1)";

                $command = Yii::app()->db->createCommand($sql);
                $mcht_id = $row['mcht_id'];
                $areacode = substr($mcht_id, 3, 4);
                $command->bindParam(":id", $mcht_id, PDO::PARAM_STR);
                $command->bindParam(":name", $row['mcht_name'], PDO::PARAM_STR);
                $command->bindParam(":address", $row['mcht_address'], PDO::PARAM_STR);
                $command->bindParam(":contact", $row['mcht_contact'], PDO::PARAM_STR);
                $command->bindParam(":phone", $row['mcht_phone'], PDO::PARAM_STR);
                $command->bindParam(":acq_inst", $row['mcht_acq_inst'], PDO::PARAM_STR);
                $command->bindParam(":open_time", $row['mcht_open_time'], PDO::PARAM_STR);
                $command->bindParam(":operator", $row['mcht_operator'], PDO::PARAM_STR);
                $command->bindParam(":areacode", $areacode, PDO::PARAM_STR);
                $command->bindParam(":sub_inst", $row['mcht_sub_inst'], PDO::PARAM_STR);
                $command->bindParam(":license", $row['mcht_license'], PDO::PARAM_STR);
                $command->bindParam(":rep", $row['mcht_rep'], PDO::PARAM_STR);
                $command->bindParam(":repnum", $row['mcht_repnum'], PDO::PARAM_STR);
                $command->bindParam(":taxnum", $row['mcht_taxnum'], PDO::PARAM_STR);
                $command->bindParam(":regcapital", $row['mcht_regcapital'], PDO::PARAM_STR);
                $command->bindParam(":products", $row['mcht_products'], PDO::PARAM_STR);
                $command->bindParam(":account", $row['mcht_account'], PDO::PARAM_STR);
                $result = $command->execute();

                //终端

                $sql = 'REPLACE INTO pm_term(id,mid,address,phone,mtn_inst,type,open_time,operator)'
                        . 'VALUES(:id,:mid,:address,:phone,:mtn_inst,:type,:open_time,:operator)';

                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":id", $row['pos_id'], PDO::PARAM_STR);
                $command->bindParam(":mid", $row['mcht_id'], PDO::PARAM_STR);
                $command->bindParam(":address", $row['pos_address'], PDO::PARAM_STR);
                $command->bindParam(":phone", $row['pos_phone'], PDO::PARAM_STR);
                $command->bindParam(":mtn_inst", $row['pos_mtn_inst'], PDO::PARAM_STR);
                $command->bindParam(":type", $row['pos_type'], PDO::PARAM_STR);
                $command->bindParam(":open_time", $row['pos_open_time'], PDO::PARAM_STR);
                $command->bindParam(":operator", $row['pos_operator'], PDO::PARAM_STR);

                $result = $command->execute();
                $r['msg'] = 'successed';
                $transaction->commit();
                echo "import " . ($key + 1) . " row successed....\n";

                //更改任务的状态
                $sql = 'UPDATE pm_task SET status=0 WHERE task_id=:task_id';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":task_id", $row['task_id'], PDO::PARAM_INT);
                $result = $command->execute();
                echo "change task status 0 ……\n";
                var_dump($result);
                
                //删除文件
                unlink($row['file_name']);
                
            } catch (Exception $e) {
                $r['msg'] = $e->getMessage();
                TaskError::insertTaskErr(array('task_id' => $row['task_id'], 'desc' => '第' . ($key + 2) . '行：' . $r['error']));
                $transaction->rollBack();
            }
        }//for end

        return $r;
    }

}
