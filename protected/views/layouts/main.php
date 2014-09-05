<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/sb-admin.css" />
    <!--    <link rel="stylesheet" type="text/css" href="--><?php //echo Yii::app()->baseUrl; ?><!--/css/font-awesome.min.css" />-->
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/chosen.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/style.css" />

    <script src="<?php echo Yii::app()->baseUrl; ?>/js/jquery-1.10.2.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/js/bootstrap.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/js/chosen.jquery.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/js/fileuploader.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.validate.js"></script>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div id="wrapper">

    <!-- Sidebar -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo Yii::app()->baseUrl; ?>/admin/index">Real Body Shop Admin</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li class="<?php if($this->req_action == 'admin/coupons') echo 'active';?>">
                    <a href="<?php echo Yii::app()->baseUrl;?>/admin/coupons"><i class="fa fa-list"></i> Coupons </a>
                </li>
                <li class="<?php if($this->req_action == 'admin/users') echo 'active';?>">
                    <a href="<?php echo Yii::app()->baseUrl;?>/admin/users"><i class="fa fa-users"></i> Users list </a>
                </li>
                <li class="<?php if($this->req_action == 'admin/referrer') echo 'active';?>">
                    <a href="<?php echo Yii::app()->baseUrl;?>/admin/referrer"><i class="fa fa-users"></i> Referrers </a>
                </li>
                <li class="<?php if($this->req_action == 'admin/cars') echo 'active';?>">
                    <a href="<?php echo Yii::app()->baseUrl;?>/admin/cars"><i class="fa fa-car"></i> Cars List </a>
                </li>
                <li class="<?php if($this->req_action == 'admin/fender_bender') echo 'active';?>">
                    <a href="<?php echo Yii::app()->baseUrl;?>/admin/fender_bender"><i class="fa fa-car"></i> Fender Bender List </a>
                </li>
                <li class="<?php if($this->req_action == 'admin/current_projects') echo 'active';?>">
                    <a href="<?php echo Yii::app()->baseUrl;?>/admin/current_projects"><i class="fa fa-image"></i> Current Projects </a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right navbar-user">
                <li class="dropdown user-dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Admin <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <!--                        <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>-->
                        <!--                        <li><a href="#"><i class="fa fa-envelope"></i> Inbox <span class="badge">7</span></a></li>-->
                        <li><a href="<?php echo Yii::app()->baseUrl;?>/admin/changePassword"><i class="fa fa-gear"></i> Settings</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo Yii::app()->baseUrl; ?>/admin/logout"><i class="fa fa-power-off"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">
        <?php
        foreach(Yii::app()->user->getFlashes() as $key => $message) {
            echo '<div class="alert alert-' . $key.'"><button class="close" data-dismiss="alert" type="button">×</button>' . $message . "</div>\n";
        }
        ?>
        <div class="row">
            <div class="col-lg-12">
                <?=$content?>
            </div>
        </div><!-- /.row -->

    </div><!-- /#page-wrapper -->

</div><!-- /#wrapper -->

<!--<footer class="clearfix">-->
<!--    Copyright &copy; VTG software<br/>-->
<!--    All Rights Reserved.<br/>-->
<!--</footer>-->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalBiga" aria-hidden="true" id="zoom_all_images_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="text-align: center;">
            <img src="" id="zoom_all_images" style="max-width: 900px;margin: 0 auto;"/>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(document).on('click','.make_zoom',function(){
            var src = $(this).attr('src').split('=')[1].split('&')[0];
            if(typeof src != 'undefined'){
                $('#zoom_all_images').attr('src',src);
                $('#zoom_all_images_modal').modal('show');
            }
        });
    });
</script>
</body>
</html>
