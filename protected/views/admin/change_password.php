<div class="panel panel-default" style="width: 800px;">
    <div class="panel-heading" style="text-align: center;font-weight: bold;">Change Password</div>
    <div class="panel-body" style="text-align: center;">
        <?php echo CHtml::beginForm('','post',array('id'=>'change_password')); ?>
        <div class="row">
            <div class="col col-sm-10" style="margin: 10px auto;position: relative;float: none;">
                <?php echo CHtml::passwordField('pass','',array('class'=>'form form-control required','placeholder'=>'Type New Password')); ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-10" style="margin: 10px auto;position: relative;float: none;">
                <?php echo CHtml::passwordField('c_pass','',array('class'=>'form form-control required','placeholder'=>'Confirm Password')); ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-10" style="margin: 10px auto;position: relative;float: none;">
                <input type="submit" value="Change Password" class="btn btn-primary btn-lg btn-block"/>
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#change_password').validate();
    });
</script>