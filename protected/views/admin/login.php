<div style="position: fixed;left: 0;width: 100%;">
    <div class="panel panel-default" style="margin: 200px auto;width: 600px;">
        <div class="panel-heading" style="text-align: center;font-weight: bold;">Welcome To Real Body Shop</div>
        <div class="panel-body">
            <?php echo CHtml::beginForm(); ?>
            <div class="row">
                <?php echo CHtml::activeTextField($model,'username',array('class'=>'form form-control','placeholder'=>'Username','style'=>'width:500px;margin: 10px auto;','required'=>'required')); ?>
            </div>
            <div class="row">
                <?php echo CHtml::activePasswordField($model,'password',array('class'=>'form form-control','placeholder'=>'Password','style'=>'width:500px;margin: 10px auto;','required'=>'required')); ?>
            </div>
            <div class="row" style="width: 500px;margin: 0 auto;">
                <?php echo CHtml::submitButton('Login',array('class'=>'btn btn-primary btn-lg btn-block','style'=>'margin-top:5px;')); ?>
            </div>
            <?php echo CHtml::endForm(); ?>
        </div>
    </div>
</div>


