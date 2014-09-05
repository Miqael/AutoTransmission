<div class="panel panel-default">
    <div class="panel-heading" style="text-align: left;font-weight: bold;overflow: hidden;">
        <i class="fa fa-list" style="margin-right: 20px;"></i>Current Projects List
        <a class="btn btn-primary addProject" style="float: right;">Add Project</a>
    </div>
    <div class="panel-body" style="text-align: center;" id="panel_body">
        <?php foreach($result as $val) { ?>
            <div class="col-lg-2 text-center">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align: right;">
                        <button class="btn btn-danger deleteProjects" data-id="<?php echo $val['id'];?>">X</button>
                    </div>
                    <div class="panel-body">
                        <img class="make_zoom" src="<?=Yii::app()->baseUrl."/images/timthumb.php?src=".Yii::app()->baseUrl."/images/projects/".$val['image']."&w=200&h=auto"; ?>">
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="panel-footer" style="overflow: hidden;">
        <?php $this->widget('CLinkPager', array(
            'pages' => $pages,
            'header' => '',
            'prevPageLabel' => '< prev',
            'firstPageLabel' => '<< first',
            'nextPageLabel' => 'next >',
            'lastPageLabel' => 'last >>',
            'selectedPageCssClass' => 'active',
            'htmlOptions' => array(
            'class' => 'pagination pull-left',
            ),
        )) ?>
    </div>
</div>

<div class="modal fade" id="add_image" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Project</h4>
            </div>
            <div class="modal-body" style="overflow: hidden;">
                <div class="row">
                    <div class="col-lg-11" >
                        <div id="file-uploader" style="float: left;"></div>
                        <div id="cont" style="float: right;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="closeImage">Close</button>
                <button type="button" class="btn btn-primary" id="addImage">Add</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(document).on('click','.deleteProjects',function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var deleteApp = confirm('Are you sure');
            if(deleteApp){
                window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/delete_from?table=projects&id='+id;
            }
        });

        $('.addProject').on('click',function(e){
            e.preventDefault();
            $('#add_image').modal('show');
        });

        var picName;
        var uploader = new qq.FileUploader({
            element: document.getElementById('file-uploader'),
            'action': '<?php echo Yii::app()->baseUrl; ?>/admin/upload_projects',
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

                $('#cont').html('');
                if(responseJSON.success ==true){

                    if(typeof picName != 'undefined'){

                        $.ajax({
                            url: '<?php echo Yii::app()->baseUrl; ?>/admin/remove_projects?pic='+picName,
                            success:function(data){  },
                            error: function(data){
                                alert('Error try again');
                            }
                        });
                    }

                    $('#cont').append('<div id="img"></div>');
                    $('#img').html('<img src="<?php echo Yii::app()->baseUrl;?>/images/projects/'+responseJSON.fileName+'" width="'+responseJSON.width+'px" height="'+responseJSON.height+'" /><input type="hidden" name="image" value="'+responseJSON.name+'"><input type="hidden" id="pictureName" name="real_image" value="'+responseJSON.fileName+'" />');

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

        $('#addImage').on('click',function(e){
            e.preventDefault();
            var pic = $('#pictureName').val();
            if(typeof pic != 'undefined'){
                $.ajax({
                    url: '<?php echo Yii::app()->baseUrl; ?>/admin/add_project?pic='+pic,
                    dataType: 'json',
                    success:function(data){
                        $('#cont').html('');
                        $('#add_image').modal('hide');
                        $('#panel_body').prepend('<div class="col-lg-2 text-center">'
                            +'<div class="panel panel-default">'
                            +'<div class="panel-heading" style="text-align: right;">'
                            +'<button class="btn btn-danger deleteProjects" data-id="'+data+'">X</button>'
                            +'</div>'
                            +'<div class="panel-body">'
                            +'<img class="make_zoom" src="<?=Yii::app()->baseUrl."/images/timthumb.php?src=".Yii::app()->baseUrl."/images/projects/"; ?>'+pic+'&w=200&h=auto">'
                            +'</div>'
                            +'</div>'
                            +'</div>');
                    },
                    error: function(data){
                        alert('Error try again');
                    }
                });
            }
        });

        $('#add_image').on('hidden.bs.modal', function (e) {
            var picName = $('#pictureName').val();
            if(typeof picName != 'undefined'){
                $.ajax({
                    url: '<?php echo Yii::app()->baseUrl; ?>/admin/remove_projects?pic='+picName,
                    success:function(data){  },
                    error: function(data){
                        alert('Error try again');
                    }
                });
            }
        });

    });
</script>