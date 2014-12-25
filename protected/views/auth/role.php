<form role="form">
    <div class="form-group">
        <h5 for="exampleInputEmail1">角色</h5>
    </div>
    <div class="checkbox">
        <?php foreach($roles as $k=>$v):?>
            <label class="col-lg-2">
                <input type="checkbox" name="auth_id[]" value="<?=$k?>" onchange="change(this)"  <?=isset($auths[$k])?'checked':''?> >
                <?=$v['description']?>
            </label>
        <?php endforeach;?>
    </div>
</form>
<script type="text/javascript">
    var change=function(obj){
        var auth_id=$(obj).val();
        var url="";
        if(obj.checked){
            url="./index.php?r=auth/add";
        }else{
            url="./index.php?r=auth/del";
        }
        var data={auth_id:auth_id,login_name:'<?=$_GET['operator_id']?>'}
        $.ajax({
            type:"POST",
            data:data,
            url:url,
            success:function(data){
                if(!data){
                    alert("操作失败！请重试");
                }
            }
        });
    }
</script>