<?php $form = $this->beginWidget('SimpleForm', array(
                                                    'id' => 'form1',
                                                    'enableAjaxSubmit' => false,
                                                    'ajaxUpdateId' => 'form-container',
                                                    'focus' =>null,
                                                    'htmlOptions' => array("enctype2" => "multipart/form-data"),
                                               )); ?>

<table class="formList">
    <tr>
        <td class="name">编号：</td>
        <td class="value">
            <?php echo $model->operator_id; ?>
            <?php echo $form->activeHiddenField($model, 'operator_id', array(), 'required');?>
            <?php echo $form->error($model, 'operator_id'); ?>
        </td>
    </tr>
    <tr>
        <td class="name">姓名：</td>
        <td class="value">
            <?php echo $model->name; ?>
            <?php echo $form->activeHiddenField($model, 'name', array(), 'required');?>
            <?php echo $form->error($model, 'name'); ?>
        </td>
    </tr>
    <tr>
        <td class="name">权限：</td>
        <td class="value">
            <div style="width:420px;">
                <div style=" float:left;width:200px;height:300px; border:1px solid gray;overflow-y:auto;">
                    <div style=" background-color:#F5F5F5;margin-top:0;"><span>已有权限</span></div>
                    <ul id="exitrole">
                        <?php if ($model->list) {
                        //print_r($model->disable_op);
                        foreach ($model->list as $listvalue) {
                            $exitrole[$listvalue['auth_id']] = $listvalue['operator_id'];
                            //if(array_key_exists($listvalue['auth_id'], $model->disable_op)){$style='display:none;';}else{$style="";}
                            if ($listvalue['authtype'] == 0) {
                                ?>
                                <li><span><span class="people_ico"></span>
                                    <?php echo $model->role[$listvalue['auth_id']]['description'];?></span>
                                    <span class="del_ico" style="cursor: pointer;<?php echo $style;?>">
                                    <input type="hidden" authtype="0" name="role[]"
                                           value="<?php echo $listvalue['auth_id'];?>">
                                        </span>
                                </li>
                                <?php } else { ?>
                                <li><span><span
                                    class="password_ico"></span><?php echo $model->task[$listvalue['auth_id']]['description'];?></span>
                                    <span class="del_ico" style="cursor: pointer;<?php echo $style;?>">
                                    <input type="hidden" authtype="1" name="role[]"
                                           value="<?php echo $listvalue['auth_id'];?>">
                                        </span>
                                </li>
                                <?php } ?>
                            <?php
                        }
                    } ?>
                    </ul>
                </div>
                <div style=" float:right;width:200px;height:300px; border:1px solid gray;overflow-y:auto;">
                    <div style=" background-color:#F5F5F5;margin-top:0;"><span>可选权限</span></div>
                    <ul id="avaliblerole">
                        <?php foreach ($model->role as $key => $value) {
                        if ($exitrole) {
                            if (array_key_exists($key, $exitrole))
                                continue;
                        }
                        if (!$value['display']) {
                            continue;
                        }
                        //if(array_key_exists($key, $model->disable_op)){$style='display:none;';$key="";}else{$style="";}
                        ?>
                        <li><span><span class="people_ico"> </span><?php echo $value['description'];?></span>
                            <span class="add_ico" style="cursor: pointer;<?php echo $style;?>">
                                <input type="hidden" authtype="0" name="role[]" value="<?php echo $key;?>">
                            </span>
                        </li>
                        <?php } ?>
                        <?php foreach ($model->task as $key => $value) {
                        if ($exitrole) {
                            if (array_key_exists($key, $exitrole))
                                continue;
                        }
                        if (!$value['display']) {
                            continue;
                        }
                        //if(array_key_exists($key, $model->disable_op)){$style='display:none;';$key="";}else{$style="";}
                        ?>
                        <li><span><span
                            class="password_ico"> </span><?php echo $value['description'];?></span><span
                            class="add_ico" style="cursor: pointer;<?php echo $style;?>">
                            <input type="hidden" authtype="1" name="role[]" value="<?php echo $key;?>">
                            </span>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>
<style type="text/css">
    ul {
        margin-top:10px;
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $(".add_ico").toggle(function () {
            addAuth(this);
        }, function () {
            delAuth(this);
        });
        $(".del_ico").toggle(function () {
            delAuth(this);
        }, function () {
            addAuth(this);
        });
    });
    function addAuth(obj) {
        var inpu = $(obj).children();
        //var url="&StaffAuth[stid]="+$("#StaffAuth_stid").val()+"&StaffAuth[authid]="+inpu.val()+"&StaffAuth[authtype]="+inpu.attr("authtype");
        var data = {Auth:{operator_id:$("#Auth_operator_id").val(),auth_id:inpu.val(),authtype:inpu.attr("authtype")}};
        jQuery.ajax({
            type: 'post',
            dataType:"json",
            url:'./?r=auth/edit',
            data:data,
            beforeSend:function(XMLHttpRequest){displayLoadingLayer("正在处理...")},
            success: function(data, html) {
                if (data.success) {
                    //alert(data.message)
                    $(obj).removeClass("add_ico");
                    $(obj).addClass("del_ico");
                    $("#exitrole").append($(obj).parent());
                }else {
                    $(obj).toggle(function () {
                        addAuth(this);
                    }, function () {
                        delAuth(this);
                    });
                }
            },
            error: function() {
                $(obj).toggle(function () {
                        addAuth(this);
                    }, function () {
                        delAuth(this);
                    });
            },
            complete: function(XMLHttpRequest,textStatus){hideLoadingLayer();}
        })
    }
    function delAuth(obj) {
        var inpu = $(obj).children();
        var data = {operator_id:$("#Auth_operator_id").val(),auth_id:inpu.val(),authtype:inpu.attr("authtype"),confirm:true};
        jQuery.ajax({
            type: 'post',
            dataType:"json",
            url:'./?r=auth/delete',
            data:data,
            beforeSend:function(XMLHttpRequest){displayLoadingLayer("正在处理...")},
            success: function(data, html) {
                if (data.success) {
                    //alert("删除角色成功！")
                    //$(obj).bind('click',function(){addAuth(this);});
                    //$(obj).attr({onclick:"addAuth(this);"});
                    $(obj).removeClass("del_ico");
                    $(obj).addClass("add_ico");
                    $("#avaliblerole").append($(obj).parent());
                } else {
                    $(obj).toggle(function () {
                        delAuth(this);
                    }, function () {
                        addAuth(this);
                    });
                }
            },
            error: function() {
                $(obj).toggle(function () {
                    delAuth(this);
                }, function () {
                    addAuth(this);
                });
            } ,
            complete: function(XMLHttpRequest, textStatus){hideLoadingLayer();}
        })
    }

</script>