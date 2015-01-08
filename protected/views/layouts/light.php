<!DOCTYPE html>
<html style="background-color:#ca1946">
    <head>
        <meta charset="UTF-8">
        <title><?=Yii::app()->name?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- common style -->
        <link href="css/common.css" rel="stylesheet" type="text/css" />

        <!-- jQuery 2.1.1 -->
        <script src="js/jquery-2.1.1.min.js"></script>

    </head>
    <body style="background:none;">
        <?php echo $content; ?>
        <!-- Bootstrap -->
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
    </body>
    <!-- COMPOSE MESSAGE MODAL -->
    <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" id="modal-close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modal-title"></h4>
                </div>
                <div class="modal-body" id="content-body" style="min-height:100px">
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</html>