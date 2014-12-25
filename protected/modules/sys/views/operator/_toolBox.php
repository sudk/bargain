<div class="row">
    <div class="col-xs-6">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form" method="get" action="javascript:itemQuery(0);">
                <input type="text" class="" name="q[id]" placeholder="用户名">
                <input type="text" class="" name="q[name]" placeholder="真实姓名">
                <a style="cursor:pointer;color:gray;" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> 搜索</a>
            </form>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="dataTables_filter" >
            <label>
                <!--<button class="btn btn-primary btn-flat">Primary</button>  -->
                <a href="./index.php?r=sys/operator/new"><button class="btn btn-primary btn-sm">添加操作员</button></a>
            </label>
        </div>
    </div>
</div>
<script type="text/javascript">
    var itemQuery = function () {
        var length=arguments.length;
        if(length==1){
            <?=$this->gridId?>.page = arguments[0];
        }
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        <?php echo $this->gridId; ?>.
        condition = url;
        <?php echo $this->gridId; ?>.
        refresh();
    }
</script>