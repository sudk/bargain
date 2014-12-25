<?php

/**
 *
 * @author Su Dunkuai <sudk@trunkbow.com>
 * @since 1.0
 */
class MobilePager extends CBasePager
{

    public $id;
    public $page_num;
    public $page;
    public $total_num;
    public $num_of_page;
    public $condition;
    public $order;
    public $url;

    /**
     * Initializes the pager by setting some default property values.
     */
    public function init()
    {
      if(isset($_GET['q'])){
          foreach($_GET['q'] as $k=>$v){
            $this->condition.="q[$k]=".urlencode($v)."&";
          }
      }
        $this->page=$_GET['page'];
    if($this->page < ceil($this->total_num/$this->num_of_page)&&$this->total_num!=0){
        echo <<<EOF
    <a class="list-group-item row" id='page-toolbar' onclick='{$this->id}.refresh({$this->page})' >
        <h5  class="text-center" id='load-more'>加载更多</h5>
    </a>
EOF;
            $this->buildScript();
        }



    }

    public function buildScript(){
        echo <<<EOF
<script type="text/javascript">

if({$this->id}=== undefined||!{$this->id}.hasOwnProperty('url')) {

		var {$this->id} = {};
		    {$this->id}.page = $this->page;
		    {$this->id}.condition = "$this->condition";
		    {$this->id}.order = "$this->order";
		    {$this->id}.url = "$this->url";
		    {$this->id}.refresh = function(page) {

			this.page=page;

			url = this.url+"&page="+this.page+"&"+this.condition+"&q_order="+this.order;

			url=encodeURI(url);
			jQuery.ajax({
			type: "get",
			url: url,
			beforeSend: function(XMLHttpRequest){
				$('#load-more').html('我在努力加载...');
			},
			success: function(data, textStatus){
			    $('#page-toolbar').remove();
				jQuery("#$this->id").append(data);
			},
			complete: function(XMLHttpRequest, textStatus){
				//hideLoadingLayer();
			},
			error: function(){
				alert("请求失败");
			}
			});
		};
	}else{
	    $this->id.page = $this->page;
	    $this->id.order = '$this->order';
	}
</script>
EOF;

    }
}
