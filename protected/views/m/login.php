<div class="form-box" id="login-box">
    <div class="header">
        <?php if(isset($mode)):?>
            <?php if($mode->logo):?>
                <img src="img/logo/<?=$mode->logo?>" style="width:100px;height:100px;">
            <?php endif;?>
            <span><?=$mode->name?></span>
        <?php else:?>
            <span>砍价管理系统</span>
        <?php endif;?>
    </div>
    <form action="index.php?r=m/login" method="post">
        <div class="body bg-gray">
            <div class="form-group">
                <span class="label label-warning"><?=$message?></span>
            </div>
            <div class="form-group">
                <input type="text" name="LoginForm[username]" class="form-control" id="userName" placeholder="用户名"/>
            </div>
            <div class="form-group">
                <input type="password" name="LoginForm[passwd]" class="form-control" id="userPwd" placeholder="密码"/>
            </div>
            <div class="form-group row">
                <div class="col-lg-5"><input type="text" name="LoginForm[captcha]" class="form-control" id="userPwd" placeholder="验证码"/></div>
                <div class="col-lg-4"><img src="index.php?r=m/captcha&1254274355" style="vertical-align:middle;width:76px;height:34px;cursor:pointer" onclick="captcha(this)" /></div>
            </div>
        </div>
        <div class="footer">
            <button type="submit" class="btn btn-primary btn-block">登录</button>
               <p><a href="#">忘记密码？</a></p>
        </div>
    </form>
</div>
<script type="text/javascript">
    var captcha=function(obj){
        $(obj).attr("src","index.php?r=m/captcha&"+new Date().getTime());
    }
    $(document).ready(function(){
        $('#btnSubmit').click(function(){
            $('#loginForm').submit();
        })
        $('input:text:first').focus();
        $('input').live("keypress", function(e) {
            /* ENTER PRESSED*/
            if (e.keyCode == 13) {
                /* FOCUS ELEMENT */
                var inputs = $(this).parents("form").eq(0).find(":input");
                var idx = inputs.index(this);

                if($(this).attr("name")=='LoginForm[captcha]') { //if (idx == inputs.length - 1) { // if($(this).attr("name")=='submit') {
                    //inputs[0].select();
                    $('#loginForm').submit();
                    return true;
                } else {
                    inputs[idx + 1].focus(); //  handles submit buttons
                    inputs[idx + 1].select();
                }
                return false;
            }
        });
    });
</script>
