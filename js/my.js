var displayLoadingLayer=function(){$("#loading").show();};
var hideLoadingLayer=function(){$("#loading").hide();};

function TBModal(){}
TBModal.prototype={
    title:'',
    url:'',
    width:'',
    loader:"<div class='ajax-loading-img'></div>",
    modal:function(){
        $('#modal-title').html(this.title);
        $('#compose-modal').modal();
        $("#content-body").html(this.loader);
        $("#content-body").load(this.url);
        $("#compose-modal").children("div").css("width",this.width);
    },
    close:function(){
        $("#modal-close").click();
    }
};