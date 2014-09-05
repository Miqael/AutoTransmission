<div class="panel panel-default">
    <div class="panel-heading" style="text-align: left;font-weight: bold;overflow: hidden;">
        <i class="fa fa-list" style="margin-right: 20px;"></i>Referrers List
        <a href="<?php echo Yii::app()->baseUrl; ?>/admin/add_referrer/" class="btn btn-primary" style="float: right;">Add Referrer</a>
    </div>
    <div class="panel-body" style="text-align: center;">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($result as $val){ ?>
                <tr>
                    <td><?php echo $val['id']; ?></td>
                    <td><?php echo $val['name']; ?></td>
                    <td><?php echo $val['phone']; ?></td>
                    <td><?php echo $val['email']; ?></td>
                    <td><?php echo $val['created']; ?></td>
                    <td>
                        <div class="btn-group">
                            <a href="<?php echo Yii::app()->baseUrl; ?>/admin/add_referrer?ref_id=<?php echo $val['id']; ?>" class="btn btn-primary " data-id="<?php echo $val['id']; ?>">Edit</a>
                            <button class="btn btn-danger deleteRef" data-id="<?php echo $val['id']; ?>">Delete</button>
                        </div>
                    </td>
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
        $('.deleteRef').on('click',function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var deleteApp = confirm('Are you sure');
            if(deleteApp){
                window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/delete_from?table=referrers&id='+id;
            }
        });
    });
</script>