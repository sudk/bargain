<?php
if ($msg) {
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>提示：</b>{$msg['msg']}
          </div>
          <script type='text/javascript'>
          {$this->gridId}.refresh();
          </script>
          ";
}

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => isset($__model__),
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'id'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
    'autoValidation' => false,
));
?>
    <div class="form-group">
        <label for="id" class="col-sm-2 control-label">用户名</label>
        <div class="col-sm-6" style="vertical-align:bottom">
            <?php
            if(!isset($__model__)){ echo $form->activeTextField($model, 'id', array('class' => 'form-control', 'placeholder' => '英文字母', 'check-type' => 'char required'));}
            else{
                echo "<label class=\"control-label\">$model->id</label>";
            }
            ?>
            <!-- 详细验证规则请参照bootstrap3-validation.js -->
        </div>
    </div>
    <div class="form-group">
        <label for="pw1" class="col-sm-2 control-label">密码</label>
        <div class="col-sm-6">
            <input type="password" name="Operator[password]" class="form-control" id="pw1" <?php if(!isset($__model__)){echo 'check-type="required" minlength="6"';}?>>
        </div>
    </div>
    <div class="form-group">
        <label for="pw2" class="col-sm-2 control-label">确认密码</label>
        <div class="col-sm-6">
            <input type="password" name="Operator[password_c]"  class="form-control" id="pw2" <?php if(!isset($__model__)){echo 'check-type="required" minlength="6"';}?>>
        </div>
    </div>
    <div class="form-group">
        <label for="pw2" class="col-sm-2 control-label">真实姓名</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'name', array('class' =>'form-control','check-type' =>'required','placeholder' => '请填写真实姓名')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="pw2" class="col-sm-2 control-label">电话</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'phone', array('class' =>'form-control', 'placeholder' => '个人联系电话')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="mail" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'email', array('class' =>'form-control', 'placeholder' => 'xxxx@xxx.com', 'check-type' => 'mail required')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vercode" class="col-sm-2 control-label">住址</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'addr', array('class' =>'form-control', 'placeholder' => '住址')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vercode" class="col-sm-2 control-label">状态</label>
        <div class="col-sm-6">
            <?php echo $form->activeDropDownList($model, 'status',Operator::getStatusTitle(), array('class' =>'form-control')); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" id="submit1" class="btn btn-primary btn-lg">保存</button>
            <button type="reset" class="btn btn-default btn-lg" style="margin-left: 10px">重置</button>
            <?php if(!isset($__model__)):?>
            <a href="javascript:window.history.go(-1);" type="button" class="btn btn-info btn-lg" style="margin-left: 10px">返回列表</a>
            <?php endif;?>
        </div>
    </div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        $("#form1").validation(function(obj,params){
            if(obj.id=='pw2'&& $("#pw2").val()!=$("#pw1").val()){
                params.err = '两次输入的密码不一致';
                params.msg = "两次输入的密码不一致！";
            }
        });
    });
</script>