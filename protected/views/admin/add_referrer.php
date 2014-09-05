<div class="panel panel-default" style="width: 800px;">
    <div class="panel-heading" style="text-align: center;font-weight: bold;">Add New Referrer</div>
    <div class="panel-body" style="text-align: center;">
        <?php echo CHtml::beginForm('','post',array('id'=>'add_form')); ?>
        <div class="row">
            <div class="col col-sm-10" style="margin: 10px auto;position: relative;float: none;">
                <?php echo CHtml::textField('name',isset($data)? $data['name']:'',array('class'=>'form form-control required','placeholder'=>'Name')); ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-10" style="margin: 10px auto;position: relative;float: none;">
                <?php echo CHtml::textField('phone',isset($data)? $data['phone']:'',array('class'=>'form form-control required','placeholder'=>'Phone')); ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-10" style="margin: 10px auto;position: relative;float: none;">
                <?php echo CHtml::textField('email',isset($data)? $data['email']:'',array('class'=>'form form-control required email','placeholder'=>'Email')); ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-10" style="margin: 10px auto;position: relative;float: none;">
                <input type="submit" value="<?php echo isset($data)? 'Edit':'Add'?> Referrer" class="btn btn-primary btn-lg btn-block"/>
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#add_form').validate();
    });
</script>