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
        <label for="name" class="col-sm-2 control-label">机构名称</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'name', array('class' =>'form-control', 'placeholder' => '机构名称','check-type' => 'required')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="parent" class="col-sm-2 control-label">所属上级机构代码</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'parent', array('class' =>'form-control', 'placeholder' => '如果是顶级机构请留空','readonly'=>'true','check-type' => '')); ?>
            <span class="help-block"><a id="search_code" href="javascript:void(0);">查找机构代码</a></span>
        </div>
    </div>
    <div class="form-group">
        <label for="id" class="col-sm-2 control-label">机构代码</label>
        <div class="col-sm-6" style="vertical-align:bottom">
            <?php
            if(!isset($__model__)){ echo $form->activeTextField($model, 'code', array('class' => 'form-control', 'placeholder' => '数字','check-type' => 'number required'));}
            else{
                echo "<p class=\"form-control-static\">$model->code</p>";
            }
            ?>
            <!-- 详细验证规则请参照bootstrap3-validation.js -->
        </div>
    </div>
    <div class="form-group">
        <label for="contact" class="col-sm-2 control-label">联系人</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'contact', array('class' =>'form-control', 'placeholder' => '机构联系人')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="phone" class="col-sm-2 control-label">电话</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'phone', array('class' =>'form-control', 'placeholder' => '个人联系电话')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="address" class="col-sm-2 control-label">住址</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'address', array('class' =>'form-control', 'placeholder' => '住址')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="valid_time" class="col-sm-2 control-label">账号有效期</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'valid_time', array('class' =>'form-control', 'placeholder' => 'yyyy-mm-dd', 'check-type' => 'required','data-date-format'=>"yyyy-mm-dd")); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="logo" class="col-sm-2 control-label">图标</label>
        <div class="col-sm-6">
            <?php echo $form->activeDropDownList($model, 'logo',Institute::GetLogo(), array('class' =>'form-control','check-type' => 'required')); ?>
        </div>
    </div>
    <div class="form-group">
        <label for="areacode" class="col-sm-2 control-label">地区代码</label>
        <div class="col-sm-6">
            <?php echo $form->activeTextField($model, 'areacode', array('class' =>'form-control', 'placeholder' => '','readonly'=>'true','check-type' => 'required')); ?>
            <span class="help-block"><a id="search_areacode" href="javascript:void(0);">选择地区</a></span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="button" id="submit1" onclick="javascript:$('#form1').submit()" class="btn btn-primary btn-lg">保存</button>
            <button type="reset" class="btn btn-default btn-lg" style="margin-left: 10px">重置</button>
        </div>
    </div>
<?php $this->endWidget(); ?>
<script src="js/plugins/input-mask/jquery.inputmask.js"></script>
<script src="js/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>

<script type="text/javascript">

    var show_popover = function(){
        $("#search_code").popover('show');
    }

    var hide_popover = function(id){
        $("#"+id).popover('toggle');
    }

    var set_code = function(code){
        $("#Institute_parent").val(code);
        hide_popover('search_code');
    }

    var set_areacode = function(code){
        $("#Institute_areacode").val(code);
        hide_popover('search_areacode');
    }

    var search_code = function(){
        var v=$("#search").val();
        if(!v){return;}
        $("#popover-content").html("正在搜索...");
        $("#popover-content").load("./index.php?r=sys/institute/search&q[name]="+v);
    }
    var load_areacode = function(v){
        $("#popover-content-areacode").html("正在加载...");
        $("#popover-content-areacode").load("./index.php?r=area/district&"+v);
    }

    jQuery(document).ready(function() {
        $("#Institute_valid_time").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
        $("#form1").validation(function(obj,params){
            if(obj.id=='pw2'&& $("#pw2").val()!=$("#pw1").val()){
                params.err = '两次输入的密码不一至';
                params.msg = "两次输入的密码不一至！";
            }
        });

        $('#search_code').popover({
            trigger:"click",
            placement:'bottom',
            title:'单击选择',
            template:'<div class="popover" style="min-width:320px;min-height:300px;"  role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><input type="text" style="margin:13px;" id="search" placeholder="请输入机构名称"><i class="fa fa-fw fa-search" onclick="search_code()" style="margin-right:13px;cursor:pointer"></i><div class="popover-content" id="popover-content"></div></div>',
            content:''
        });
        $('#search_areacode').popover({
            trigger:"click",
            placement:'top',
            title:'单击选择',
            template:'<div class="popover" style="min-width:320px;min-height:400px;" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content" id="popover-content-areacode"></div></div>',
            content:''
        });

        $('#search_areacode').on('shown.bs.popover', function () {
            load_areacode('level=1');
        })

        $('#search_code').on('shown.bs.popover', function () {
            $("#search").bind("keydown", function (event) {
                event.stopPropagation();
                if(event.keyCode==13){
                    search_code();
                }
            });
        })
    });
</script>