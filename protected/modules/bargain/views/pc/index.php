<div style="margin-top:20px;" xmlns="http://www.w3.org/1999/html"></div>
<?php if(count($rows)):?>
    <?php foreach($rows as $row):?>
        <div style="background:none;border:none;">
            <div class="panel panel-default"  style="background:none;border:none;">
                <div class="panel-body"  style="background:none;border:none;">
                    <a href="./?r=mobile/goods/index&id=<?=$row['id']?>"><img width="100%" style="max-height:400px;" src="img/pc_bg.jpg" ></a>
                </div>
                <div class="panel-footer"  style="background:none;border:none;">
                    <table style="width:100%;"  style="background:none;border:none;">
                        <tr style="width:100%;">
                            <td style="width:22%;">
                                <span style="font-size:20px;font-weight:bolder;color: #ffffff;"><?=mb_substr($row['name'],0,12)?></span>
                    <span id="price_now" class="text-warning pull-right">
                        <span  style="font-size:25px;font-weight:bolder;vertical-align:middle;color:yellowgreen;">
                            ￥<?= number_format($row['price'] / 100, 2) ?>
                        </span>
                    </span>
                                <img src="img/qrcode.png" id="<?=$row['id']?>_qrcode" width="250px" height="250px"/>
                            </td>
                            <td  style="width:78%;">
                                <p><input type="text" id="<?=$row['id']?>_number" maxlength="11" placeholder="请输入手机号"></p>
                                <p><button type="button" onclick="qr_code(this,'<?=$row['id']?>')" data-loading-text="正在生成二维码"  class="btn btn-warning">我要参加活动</button></p>
                                <p style="color: #ffffff;font-size:20px;font-weight:bolder;"><input type="checkbox"  id="accepted" style="width:15px;height:15px;">我已经阅读<a href="./?r=mobile/goods/desc&id=<?=$row['id']?>" style="color: yellowgreen">活动规则</a></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    <?php break; endforeach;?>
<?php endif;?>
<script>
    function qr_code(obj,id){
        var number=$("#"+id+"_number").val();
        var qrcode="#"+id+"_qrcode";
        var accepted= document.getElementById("accepted").checked;
        if (number.length != 11) {
            alert("输入手机号");
            return;
        }
        if (!accepted) {
            alert("请选择“我已阅读活动规则”");
            return;
        }
        $.ajax({
            type: "post",
            data: {number:number,id:id,qrcode:true},
            dataType: "json",
            url: "./?r=mobile/goods/generate",
            beforeSend: function (XMLHttpRequest) {
                $(obj).button('loading');
            },
            success: function (data, textStatus) {
                alert(data.desc);
                if (data.status == 0) {
                    $(qrcode).attr("src",data.img);
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
</script>