<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<?php
if ($msg) {
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>提示：</b>{$msg['msg']}
          </div>
          ";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form_goods',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model,'id'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
    'autoValidation' => true,
));
if(isset($__model__)){
    echo $form->activeHiddenField($model, 'id', array());
}
?>
    <div class="form-group">
        <label for="pw2" class="col-sm-2 control-label">商品名称</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'name', array('class' =>'form-control','check-type' =>'required','placeholder' => '商户名称')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vercode" class="col-sm-2 control-label">商品图片</label>
        <div class="col-sm-6">
            <input type="file" name="file" id="file">
            <a class="btn btn-primary btn-sm" onclick="fUpload()">开始上传</a>
            <div id="upmsg">
                <?php if(isset($__model__)&&$model->img_url):?>
                <img src='attachment/<?=$model->img_url?>' style='vertical-align:middle;max-height:500px;' width='150px'>
                <?php endif;?>
            </div>
            <?php echo $form->activeHiddenField($model, 'img_url', array()); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="pw2" class="col-sm-2 control-label">价格</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'price', array('class' =>'form-control','check-type' =>'required number','placeholder' => '价格')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="pw2" class="col-sm-2 control-label">每次递减价格</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'reduce', array('class' =>'form-control','check-type' =>'required number','placeholder' => '每次递减价格')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="mail" class="col-sm-2 control-label">有效时间</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'start_time', array('class' =>'form-control', 'placeholder' => '', 'check-type' => 'required')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="mail" class="col-sm-2 control-label">失效时间</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'end_time', array('class' =>'form-control', 'placeholder' => '', 'check-type' => 'required')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vercode" class="col-sm-2 control-label">商品描述</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextArea($model, 'desc', array('class' =>'form-control', 'placeholder' => '')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vercode" class="col-sm-2 control-label">状态</label>
        <div class="col-sm-6">
            <?php echo $form->activeDropDownList($model,'status',Goods::GetStatus(),array('class' =>'form-control')); ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" id="submit1" class="btn btn-primary btn-lg">保存</button>
            <button type="reset" class="btn btn-default btn-lg" style="margin-left: 10px">重置</button>
            <a href="./?r=bargain/goods/list" type="button" class="btn btn-info btn-lg" style="margin-left: 10px">返回列表</a>
        </div>
    </div>
<?php $this->endWidget(); ?>
<!-- InputMask -->
<script src="js/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="js/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script>
<script src="js/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<!-- CK Editor -->
<script src="js/plugins/ckeditor/ckeditor.js" type="text/javascript"></script>

<script type="text/javascript">
    jQuery(document).ready(function() {
        $("#Goods_start_time").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
        $("#Goods_end_time").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
        CKEDITOR.replace('Goods_desc');
    });
    //ajax上传图片
    function fUpload(){
        $("#upmsg").html("<img src='img/loading.gif' style='vertical-align:middle;' width='16px' height='16px'>正在上传请稍候...");
        return jQuery.ajaxFileUpload({
            url:'./index.php?r=m/picupload',
            secureuri:false,
            fileElementId:'file',
            dataType: 'json',
            success: function (data, status) {
                if (data.status==0) {
                    alert(data.msg)
                    $("#Goods_img_url").val(data.path)
                    $("#upmsg").html("<img src='attachment/"+data.path+"' style='vertical-align:middle;max-height:500px;' width='150px'>");
                } else {
                    $("#upmsg").hide();
                    alert(data.msg)
                }
            },
            error: function () {
                alert("上传失败");
            }
        });
        return false;
    }
</script>