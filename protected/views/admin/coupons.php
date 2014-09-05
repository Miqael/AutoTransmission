<div class="panel panel-default">
    <div class="panel-heading" style="text-align: left;font-weight: bold;overflow: hidden;">
        <i class="fa fa-list" style="margin-right: 20px;"></i>Coupons List
        <a href="<?php echo Yii::app()->baseUrl; ?>/admin/add_coupon/" class="btn btn-primary" style="float: right;">Add Coupon</a>
    </div>
    <div class="panel-body" style="text-align: center;">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Id</th>
                <th>Image</th>
                <th>Text</th>
                <th>For</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($coupons as $val){ ?>
                <tr>
                    <td><?php echo $val['id']; ?></td>
                    <td><?php if($val['image']){ ?>
                            <img class="make_zoom" src="<?=Yii::app()->baseUrl."/images/timthumb.php?src=".Yii::app()->baseUrl."/images/reminders/".$val['image']."&w=200&h=auto"; ?>">
                        <?php } ?>
                    </td>
                    <td><?php echo $val['text']; ?></td>
                    <td><?php if($val['name']){ echo $val['name'].' '.$val['last_name']; }else{ echo 'All'; } ?></td>
                    <td><?php echo $val['created']; ?></td>
                    <td><button class="btn btn-primary resend" data-id="<?php echo $val['id']; ?>">Send Again</button></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
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
<script>
    $(document).ready(function(){
        $('.resend').on('click',function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var deleteApp = confirm('Are you sure');
            if(deleteApp){
                window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/resend_coupon?reminder_id='+id;
            }
        });
    });
</script>