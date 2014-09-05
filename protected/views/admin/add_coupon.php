<div class="panel panel-default" style="width: 800px;">
    <div class="panel-heading" style="text-align: center;font-weight: bold;">Add New Coupon</div>
    <div class="panel-body" style="text-align: center;">
        <?php echo CHtml::beginForm('','post',array('id'=>'add_product')); ?>
        <div class="row">
            <div class="col col-sm-10" style="margin: 10px auto;position: relative;float: none;">
                <?php echo CHtml::textArea('text','',array('class'=>'form form-control required','placeholder'=>'Text')); ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-10" style="margin: 10px auto;position: relative;float: none;">
                <?php echo CHtml::dropDownList('type','',array('0'=>'For All','1'=>'For Current User'),array('id'=>'pType','class'=>'form form-control required','placeholder'=>'Text')); ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-10" id="user_div" style="margin: 10px auto;position: relative;float: none;display: none;">
                <?php echo CHtml::dropDownList('user_id','',array('0'=>'select_user'),array('id'=>'selectUser','class'=>'form form-control required','placeholder'=>'Text')); ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-10" style="margin: 10px auto;position: relative;float: none;overflow: hidden;">
                <div id="file-uploader" style="float: left;"></div>
                <div id="cont" style="float: right;">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-10" style="margin: 10px auto;position: relative;float: none;">
                <input type="submit" value="Add Coupon" class="btn btn-primary btn-lg btn-block"/>
            </div>
        </div>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#add_product').validate();

        $('#pType').on('change',function(){
            var val = $(this).val();
            if(val == 1){
                $.ajax({
                    url: '<?php echo Yii::app()->baseUrl; ?>/admin/getUsers',
                    dataType: 'json',
                    success: function (data) {
                        $('#selectUser').html('<option value="">Select user</option>');
                        $.each(data,function(key,val){
                            $('#selectUser').append('<option value="'+val.id+'">'+val.name+' '+val.last_name+' : '+val.email+'</option>');
                        });
                        $('#user_div').fadeIn('slow');
                    },
                    error: function (data) {
                        alert('Something Went Wrong . Please Try Again');
                    }
                });
            }else{
                $('#selectUser').html('<option value="0">Select user</option>');
                $('#user_div').fadeOut('slow');
            }
        });

        var count = 0;
        var img;
        var alt;
        var check;
        var picName;
        var uploader = new qq.FileUploader({
            element: document.getElementById('file-uploader'),
            'action': '<?php echo Yii::app()->baseUrl; ?>/admin/uploader',
            'debug': false,
            multiple: false,
            //sizeLimit: 0, // max size
            // minSizeLimit: 0, // min size
            onSubmit: function(id, fileName){
                picName = $('#pictureName').val();
                $('#cont').html('<div class="progress progress-striped active" style="width: 400px;">'
                    +'<div id="progress-bar" class="progress-bar" style="width: 0%"></div>'
                    +'</div>');
            },
            onProgress: function(id, fileName, loaded, total){
                $('#progress-bar').css({width: Math.round(loaded / total * 100)+'%'});
            },
            onComplete: function(id, fileName, responseJSON){
                count = count + 1;

                $('#cont').html('');
                if(responseJSON.success ==true){

                    if(typeof picName != 'undefined'){

                        $.ajax({
                            url: '<?php echo Yii::app()->baseUrl; ?>/admin/remove_photo?pic='+picName,
                            success:function(data){  },
                            error: function(data){
                                alert('Error try again');
                            }
                        });
                    }

                    $('#cont').append('<div id="img"></div>');
                    $('#img').html('<img src="<?php echo Yii::app()->baseUrl;?>/images/reminders/'+responseJSON.fileName+'" width="'+responseJSON.width+'px" height="'+responseJSON.height+'" /><input type="hidden" name="image" value="'+responseJSON.name+'"><input type="hidden" id="pictureName" name="real_image" value="'+responseJSON.fileName+'" />');

                    check = 0;

                    $('#ManualEntryManualFile').val(responseJSON.fileName);
                    $('.qq-upload-list li').remove();
                    if($('#2').length !== 0 && $('#3').length !== 0){
                        $('#file-uploader3').removeClass('hid');
                    }
                }else{
                    alert('Allowed only png, jpg, gif, jpeg');
                    $('#load').empty();
                }
            },
            onCancel: function(id, fileName){$('.qq-upload-button').removeClass('.qq-upload-button-visited')},
            messages: {
                // error messages, see qq.FileUploaderBasic for content
            },
            showMessage: function(message){alert(message);}
        });
    });
</script>