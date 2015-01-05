<div style="margin-top:20px;" xmlns="http://www.w3.org/1999/html"></div>
<?php if(count($rows)):?>
    <?php foreach($rows as $row):?>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a href="./?r=mobile/goods/index&id=<?=$row['id']?>"><img width="100%" style="max-height:400px;" src="img/pc_bg.jpg" ></a>
                </div>
                <div class="panel-footer">
                    <?=mb_substr($row['name'],0,12)?>
                    <span id="price_now" class="text-warning pull-right">
                        <span  style="font-size:20px;font-weight:bolder;vertical-align:middle;">
                            ￥<?= number_format($row['price'] / 100, 2) ?>
                        </span>
                        <span>
                            每次递减：￥<?= number_format($row['reduce'] / 100, 2) ?>
                        </span>
                    </span>
                </div>
                <div class="panel-footer">
                    <table style="width:100%;">
                        <tr style="width:100%;">
                            <td style="width:40%;">
                                <img src="img/qrcode.png" id="<?=$row['id']?>_qrcode" width="250px" height="250px"/>
                            </td>
                            <td  style="width:60%;">
                                <input type="text" id="<?=$row['id']?>_number" maxlength="11" placeholder="请输入手机号">
                                <button type="button" onclick="qr_code(this,'<?=$row['id']?>')" data-loading-text="正在生成二维码"  class="btn btn-info">生成</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h3><small>内容</small></h3>
                                <div>
                                    <?= $row['desc']; ?>
                                </div>
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
        $.ajax({
            type: "post",
            data: {number:number,id:id,qrcode:true},
            dataType: "json",
            url: "./?r=mobile/goods/generate",
            beforeSend: function (XMLHttpRequest) {
                $(obj).button('loading');
            },
            success: function (data, textStatus) {

                if (data.status == 0) {
                    $(qrcode).attr("src",data.img);
                }else{
                    alert(data.desc);
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