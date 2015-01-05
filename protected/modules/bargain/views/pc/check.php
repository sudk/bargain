<div style="margin-top:20px;" xmlns="http://www.w3.org/1999/html"></div>

<div class="col-lg-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <table style="width:100%;">
                <tr style="width:100%;">
                    <td style="width:80%;">
                        <input type="text" id="number" maxlength="11" placeholder="请输入手机号">
                    </td>
                    <td  style="width:20%;">
                        <button type="button" onclick="get_price(this)" data-loading-text="正在查询"  class="btn btn-info">查询</button>
                    </td>
                </tr>
                <tr>
                    <td id="price_rs" colspan="2">

                    </td>
                </tr>
                <tr style="width:100%;">
                    <td style="width:80%;">
                        <input type="text" id="b_code" maxlength="11" placeholder="营业代码">
                    </td>
                    <td  style="width:20%;">
                        <button type="button" onclick="set_bcode(this)" data-loading-text="正在处理"  class="btn btn-info">办理</button>
                    </td>
                </tr>
                <tr>
                    <td id="bcode_rs" colspan="2">

                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
    function get_price(obj){
        var number=$("#number").val();

        $.ajax({
            type: "post",
            data: {number:number},
            dataType: "json",
            url: "./?r=bargain/pc/get_price",
            beforeSend: function (XMLHttpRequest) {
                $(obj).button('loading');
            },
            success: function (data, textStatus) {
                if (data.status == 0) {
                    $("#price_rs").html(data.desc);
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
    function set_bcode(obj){
        var number=$("#number").val();
        var bcode=$("#b_code").val();
        $.ajax({
            type: "post",
            data: {number:number,bcode:bcode},
            dataType: "json",
            url: "./?r=bargain/pc/set_bcode",
            beforeSend: function (XMLHttpRequest) {
                $(obj).button('loading');
            },
            success: function (data, textStatus) {
                if (data.status == 0) {
                    $("#bcode_rs").html(data.desc);
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