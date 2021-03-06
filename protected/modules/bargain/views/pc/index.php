<div style="margin-top:20px;" xmlns="http://www.w3.org/1999/html"></div>
<?php if (count($rows)): ?>
    <?php foreach ($rows as $row): ?>
        <div style="background:none;border:none;">
            <div class="panel panel-default" style="background:none;border:none;">
                <div class="panel-body" style="background:none;border:none;">
                    <a href="./?r=mobile/goods/index&id=<?= $row['id'] ?>"><img width="100%" style="max-height:400px;"
                                                                                src="img/pc_bg.jpg"></a>
                </div>
                <div class="panel-footer" style="background:none;border:none;">
                    <table style="width:100%;" style="background:none;border:none;">
                        <tr style="width:100%;vertical-align:top;">
                            <td colspan="3">
                                <span style="font-size:20px;font-weight:bolder;color: #ffffff;"><?= mb_substr($row['name'], 0, 12) ?></span>
                                <span id="price">
                        <span id="price_now" class="text-warning" style="font-size:30px;font-weight:bolder;vertical-align:middle;color:yellowgreen;font-weight:bolder;">
                        ￥<?= number_format($row['price'] / 100, 2) ?>
                            </span>
                                    <del id="price_pass"></del>
                                    <span id="price_success" style="font-size:25px;font-weight:bolder;vertical-align:middle"></span>
                            </td>
                        </tr>
                        <tr style="width:100%;vertical-align:top;">
                            <td style="width:22%;">

                                <img src="img/qrcode.png" id="<?= $row['id'] ?>_qrcode" width="250px" height="250px"/>
                            </td>
                            <td style="width:20%;">
                                <p><small class="text-muted" style="color:#ffffff">输入手机号生成链接让好友帮你砍价</small></p>
                                <p><input style="width:195px;" type="text" id="<?= $row['id'] ?>_number" maxlength="11" placeholder="请输入手机号"></p>
                                <p>
                                    <button type="button"  style="width:195px;"  onclick="qr_code(this,'<?= $row['id'] ?>')"
                                            data-loading-text="正在生成二维码" class="btn btn-warning">我要参加活动
                                    </button>
                                </p>
                                <p style="color: #ffffff;font-size:20px;font-weight:bolder;"><input type="checkbox"
                                                                                                    id="accepted"
                                                                                                    style="width:15px;height:15px;">我已经阅读<a
                                        href="./?r=mobile/goods/desc&id=<?= $row['id'] ?>" style="color: yellowgreen">活动规则</a>
                                </p>
                            </td>
                            <td style="width:60%;">
                                <div id="link_div" style="display:none;color:#ffffff">
                                    <p><small class="text-muted" style="color:yellowgreen">砍价连接</small></p>
                                    <p id="link"></p>
                                </div>
                                <div style="display:none;color:#ffffff" id="bargain_div">
                                    <p><small class="text-muted" style="color:yellowgreen">好友砍价记录</small></p>
                                    <p  id="bargains"></p>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <?php break; endforeach; ?>
<?php endif; ?>
<script>
    var s_n ="";
    var id='<?=$rows[0]['id']?>';
    function qr_code(obj,id) {
        var number = $("#" + id + "_number").val();
        var qrcode = "#" + id + "_qrcode";
        var accepted = document.getElementById("accepted").checked;
        if (number.length != 11) {
            alert("输入手机号");
            return;
        }
        if (!accepted) {
            alert("请选择“我已阅读活动规则”");
            return;
        }
        s_n=number;
        $.ajax({
            type: "post",
            data: {number: number, id: id, qrcode: true},
            dataType: "json",
            url: "./?r=mobile/goods/generate",
            beforeSend: function (XMLHttpRequest) {
                $(obj).button('loading');
            },
            success: function (data, textStatus) {
                alert(data.desc);
                if (data.status == 0) {
                    $(qrcode).attr("src", data.img);
                    $("#link_div").show();
                    $("#link").html(data.l);
                    bargains_show();
                }
            },
            complete: function (XMLHttpRequest, textStatus) {
                $(obj).button('reset');
            },
            error: function () {
                alert("请求失败");
            }
        });
    }



    //显示砍价记录
    function bargains_show(){
        $.ajax({
            type: "post",
            data: {id:id,s_n:s_n,is_g:true},
            dataType: "json",
            url: "./?r=mobile/goods/bargains",
            beforeSend: function (XMLHttpRequest) {
                //$(obj).button('loading');
                $("#bargain_div").show();
            },
            success: function (data, textStatus) {
                if(data.list){
                    $("#bargains").html(data.list);
                }
                if(data.price){
                    $("#price").show();
                    $("#price_now").html(data.price.price_now);
                    $("#price_pass").html(data.price.price_pass);
                    if(data.price.price_success){
                        $("#price_success").html(data.price.price_success);
                    }
                }

            },
            complete: function (XMLHttpRequest, textStatus) {
                //$(obj).button('reset');
            },
            error: function () {
                alert("请求失败");
            }
        });
    }

    //显示更多砍价记录
    function go_log(){
        window.location=".?r=mobile/log/list&q[uid]="+s_n+"&q[goods_id]="+id;
    }
</script>