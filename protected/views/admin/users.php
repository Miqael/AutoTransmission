<div class="panel panel-default">
    <div class="panel-heading" style="text-align: left;font-weight: bold;overflow: hidden;">
        <i class="fa fa-list" style="margin-right: 20px;"></i>Users List
    </div>
    <div class="panel-body" style="text-align: center;">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone number</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($result as $val){ ?>
                <tr>
                    <td><?php echo $val['id']; ?></td>
                    <td><?php echo $val['name']; ?></td>
                    <td><?php echo $val['last_name']; ?></td>
                    <td><?php echo $val['email']; ?></td>
                    <td><?php echo $val['phone']; ?></td>
                    <td><?php echo $val['created']; ?></td>
                    <td><button class="btn btn-danger deleteUser" data-id="<?php echo $val['id']; ?>">Delete</button></td>
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
        $('.deleteUser').on('click',function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var deleteApp = confirm('Are you sure');
            if(deleteApp){
                window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/delete_from?table=user&id='+id;
            }
        });
    });
</script>
