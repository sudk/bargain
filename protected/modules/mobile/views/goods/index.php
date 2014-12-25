<div style="position:fixed; z-index:2147483647;top:10px;display:block;;width:100%;display:none;" id="share">
    <img src="img/share.png" style="width:100%">
</div>
<div class="row well well-sm" style="padding-top:0px;margin-bottom:0px;">
    <h3 class="text-center"><?= $model['name'] ?></h3>

    <div class="col-xs-12" style="padding:0px;">
        <img width="100%" style="max-height:500px;"
             src="<?= Yii::app()->params['upload_file_path'] . "/" . $model['img_url'] ?>"
             alt="..." class="img-rounded pull-left">
    </div>
</div>
<div id="list">
    <div class="list-group">
        <a class="list-group-item" id="price">
            <span id="price_now" class="text-warning" style="font-size:20px;font-weight:bolder;vertical-align:middle">
                        ￥<?= number_format($model['price'] / 100, 2) ?>
                    </span>
            <del id="price_pass"></del>
        </a>
        <a class="list-group-item">
            <ul class="list-unstyled">
                <?php if (!$_GET['s_n']): ?>
                    <li id="number_li">
                        <small class="text-muted">输入手机号生成链接让好友帮你砍价</small>
                        <div class="row">
                            <div class="col-xs-8">
                                <input type="text" id="number" style="width:100%;font-size:18px;border-color:gainsboro;"
                                       maxlength="11"
                                       name="number" placeholder="请输入手机号"/>
                            </div>
                            <div class="col-xs-4">
                                <button type="button" onclick="generate(this)" style="vertical-align:text-bottom;font-size:16px;" data-loading-text="请稍候" class="btn btn-warning pull-left">
                                    确定
                                </button>
                            </div>
                        </div>
                    </li>
                    <?php else: ?>
                    <li id="number_li">
                        <small class="text-muted">输入手机号帮他砍价吧</small>
                        <div class="row">
                            <div class="col-xs-8">
                                <input type="number" id="number" style="width:100%;font-size:18px;border-color:gainsboro;"
                                       maxlength="11"
                                       name="number" placeholder="请输入手机号"/>
                            </div>
                            <div class="col-xs-4">
                                <button type="button" onclick="captcha(this)"
                                        style="vertical-align:text-bottom;font-size:16px;" data-loading-text="请稍候"
                                        class="btn btn-warning pull-left">
                                    确定
                                </button>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>
                <li id="captcha_li"  style="display:none">
                    <div class="row">
                        <div class="col-xs-5">
                            <input type="number" id="captcha" style="width:100%;font-size:18px;border-color:gainsboro;"
                                   maxlength="6"
                                   name="captcha" placeholder="验证码"/>
                        </div>
                        <div class="col-xs-7">
                            <button type="button" onclick="check_captcha(this)"
                                    style="vertical-align:text-bottom;font-size:16px;" data-loading-text="请稍候"
                                    class="btn btn-warning pull-left">
                                确定
                            </button>
                            &nbsp;
                            <button type="button" style="vertical-align:text-bottom;font-size:16px;" onclick="captcha(this)"  data-loading-text="请稍候" class="btn btn-info" id="resend">60</button>
                        </div>
                    </div>
                </li>
                <li id="link_li" style="display:none">
                    分享链接：
                    <p>
                        <small class="text-muted" id="link_t">正在加载...</small>
                        <span class="text-info" onclick="share_prompt();">分享</span></p>
                </li>
                <li id="bargain_li" style="display:none">
                    <small class="text-muted">好友砍价记录</small>
                    <ul class="list-unstyled" id="bargains">
                        <li>正在加载...</li>
                    </ul>
                </li>
            </ul>
        </a>
        <a class="list-group-item">
            <div class="text-info">
                开始时间：<?= $model['start_time'] ?>
            </div>
            <div class="text-info">
                结束时间：<?= $model['end_time'] ?>
            </div>
        </a>
        <a class="list-group-item row">
            <ul class="list-unstyled">
                <li>
                    <small class="text-muted">商品详情</small>
                </li>
                <li><?= $model['desc']; ?></li>
            </ul>
        </a>
    </div>
</div>
<a class="fixed_bar_bottom glyphicon glyphicon-home" style="color:darkgray" href="./?r=mobile/goods"></a>
<script>
window.shareData = {
    "imgUrl": "<?=Yii::app()->params['base_host'].Yii::app()->params['upload_file_path'] . "/" . $model['img_url'] ?>",
    "timeLineLink": "",
    "sendFriendLink": "",
    "weiboLink": "",
    "tTitle": "<?= $model['name'] ?>",
    "tContent": "快来帮忙，杀价",
    "fTitle": "<?= $model['name'] ?>",
    "fContent": "快来帮忙，杀价",
    "wContent": "快来帮忙，杀价"
};



    var time_out=0;
    var s_n =<?=$_GET['s_n']?"'".$_GET['s_n']."'":'false'?>;
    var id='<?=$model['id']?>';
    var generate = function (obj) {
        //$(obj).button('loading');
        //$(obj).button('reset');
        var number = $("#number").val();
        if (number.length != 11) {
            alert("输入手机号");
            return;
        }
        $.ajax({
            type: "post",
            data: {number:number,id:id},
            dataType: "json",
            url: "./?r=mobile/goods/generate",
            beforeSend: function (XMLHttpRequest) {
                $(obj).button('loading');
            },
            success: function (data, textStatus) {

                if (data.status == 0) {
                    $("#number_li").hide();
                    s_n=number;
                    link_show();
                    bargains_show();
                    //$("#captcha_li").show();
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

    //显示链接
    function link_show(){
        $.ajax({
            type: "post",
            data: {id:id,s_n:s_n},
            dataType: "json",
            url: "./?r=mobile/goods/link",
            beforeSend: function (XMLHttpRequest) {
                //$(obj).button('loading');
                $("#link_li").show();
            },
            success: function (data, textStatus) {
                if(data.status==0){
                    $("#link_t").html(data.l);
                    window.shareData.sendFriendLink=data.l;
                    window.shareData.timeLineLink=data.l;
                    window.shareData.weiboLink=data.l;
                }else{
                    alert(data.desc);
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


    //显示砍价记录
    function bargains_show(){
        $.ajax({
            type: "post",
            data: {id:id,s_n:s_n},
            dataType: "json",
            url: "./?r=mobile/goods/bargains",
            beforeSend: function (XMLHttpRequest) {
                //$(obj).button('loading');
                $("#bargain_li").show();
            },
            success: function (data, textStatus) {
                if(data.list){
                    $("#bargains").html(data.list);
                }
                if(data.price){
                    $("#price_now").html(data.price.price_now);
                    $("#price_pass").html(data.price.price_pass);
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
//下发验证码
    var captcha = function (obj) {
        //$(obj).button('loading');
        //$(obj).button('reset');
        if(time_out>0){
            return;
        }
        var number = $("#number").val();
        if (number.length != 11) {
            alert("输入手机号");
            return;
        }
        $.ajax({
            type: "post",
            data: {number: number,s_n:s_n},
            dataType: "json",
            url: "./?r=mobile/goods/captcha",
            beforeSend: function (XMLHttpRequest) {
                $(obj).button('loading');
            },
            success: function (data, textStatus) {
                alert(data.desc);
                if (data.status == 0) {
                    $("#number_li").hide();
                    $("#captcha_li").show();
                    reset_time();
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

    function count_time(){
        time_out--;
        if(time_out==0){
            $("#resend").html("重新发送")
            $("#resend").removeClass("disabled");
        }else{
            $("#resend").html(time_out)
            setTimeout(count_time,1000);
        }
    }
    function reset_time(){
        time_out=60;
        $("#resend").html(time_out);
        $("#resend").addClass("disabled");
        setTimeout(count_time,1000);
    }
    //验证码
    var check_captcha = function (obj) {
        var captcha = $("#captcha").val();
        if (captcha.length != 6) {
            alert("输入验证码");
            return;
        }
        $.ajax({
            type: "post",
            data: {captcha:captcha,id:id,s_n:s_n},
            dataType: "json",
            url: "./?r=mobile/goods/check",
            beforeSend: function (XMLHttpRequest) {
                $(obj).button('loading');
            },
            success: function (data, textStatus) {
                alert(data.desc);
                if (data.status == 0||data.status == -2||data.status == -6||data.status == -7) {
                    bargains_show();
                    $("#captcha_li").hide();
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

    //显示更多砍价记录
    function go_log(){
        window.location=".?r=mobile/log/list&q[uid]="+s_n+"&q[goods_id]="+id;
    }

    $(document).ready(function(){
        if(id&&s_n){
            bargains_show();
            link_show();
        }
    })

document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
    share(WeixinJSBridge);
}, false);

function share(Bridge){
    Bridge.on('menu:share:appmessage', function (argv) {
        Bridge.invoke('sendAppMessage', {
            "img_url": window.shareData.imgUrl,
            "img_width": "640",
            "img_height": "640",
            "link": window.shareData.sendFriendLink,
            "desc": window.shareData.fContent,
            "title": window.shareData.fTitle
        }, function (res) {
            //不用处理，客户端会有分享结果提示
        });
    });

    Bridge.on('menu:share:timeline', function (argv) {
        Bridge.invoke('shareTimeline', {
            "img_url": window.shareData.imgUrl,
            "img_width": "640",
            "img_height": "640",
            "link": window.shareData.timeLineLink,
            "desc": window.shareData.tContent,
            "title": window.shareData.tTitle
        }, function (res) {
            //不用处理，客户端会有分享结果提示
        });
    });

    Bridge.on('menu:share:weibo', function (argv) {
        Bridge.invoke('shareWeibo', {
            "content": window.shareData.wContent,
            "url": window.shareData.weiboLink
        }, function (res) {
            //不用处理，客户端会有分享结果提示
        });
    });
}

var share_prompt=function(){
    $("#share").show();
    setTimeout(hide_share,3000);
}
var hide_share=function(){
    $("#share").hide();
}
</script>