<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/sb-admin.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/font-awesome.min.css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div id="wrapper">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo Yii::app()->request->baseUrl; ?>/admin">Real Body Shop Admin</a>
        </div>
    </nav>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <?=$content?>
            </div>
        </div><!-- /.row -->
    </div><!-- /#page-wrapper -->

</div><!-- /#wrapper -->
</body>
</html>
