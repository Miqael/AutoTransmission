<div class="panel panel-default">
    <div class="panel-heading" style="text-align: left;font-weight: bold;overflow: hidden;">
        <i class="fa fa-list" style="margin-right: 20px;"></i>Fender Bender List
    </div>
    <div class="panel-body" style="text-align: center;">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Id</th>
                <th>From(User)</th>
                <th>Description</th>
                <th>Image 1</th>
                <th>Image 2</th>
                <th>Image 3</th>
                <th>Created</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($result as $val){ ?>
                <tr>
                    <td><?php echo $val['id']; ?></td>
                    <td><?php echo $val['user']; ?></td>
                    <td><?php echo $val['description']; ?></td>
                    <td><?php if($val['image_1']){ ?>
                            <img class="make_zoom" src="<?=Yii::app()->baseUrl."/images/timthumb.php?src=".Yii::app()->baseUrl."/images/quotes/".$val['image_1']."&w=200&h=auto"; ?>">
                        <?php } ?>
                    </td>
                    <td><?php if($val['image_2']){ ?>
                            <img class="make_zoom" src="<?=Yii::app()->baseUrl."/images/timthumb.php?src=".Yii::app()->baseUrl."/images/quotes/".$val['image_2']."&w=200&h=auto"; ?>">
                        <?php } ?>
                    </td>
                    <td><?php if($val['image_3']){ ?>
                            <img class="make_zoom" src="<?=Yii::app()->baseUrl."/images/timthumb.php?src=".Yii::app()->baseUrl."/images/quotes/".$val['image_3']."&w=200&h=auto"; ?>">
                        <?php } ?>
                    </td>
                    <td><?php echo $val['created']; ?></td>
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
        $('.deleteCar').on('click',function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var deleteApp = confirm('Are you sure');
            if(deleteApp){
                window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/delete_from?table=cars&id='+id;
            }
        });
    });
</script>