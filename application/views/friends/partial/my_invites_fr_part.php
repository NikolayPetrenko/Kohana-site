<?php if(count($my_request)): ?>
    <?php foreach($my_request as $key => $friend): ?>
            <tr>
                <td><a href="<?php echo URL::site('users/'.$friend->id)?>"><img src="<?php echo Helper_Image::instance()->getCachePatch('user.avatars', $friend->avatar, 'thumb') ?>" alt=""></a></td>
                <td><a href="<?php echo URL::site('users/'.$friend->id)?>"><?php echo $friend->firstname ?></a></td>
                <td>
                    <a class="btn btn-mini btn-danger" href="javascript:void(0)" onclick="javascript:unleashed.removeMyReuest(<?php echo $friend->id ?>, this)"><i class="icon-remove-sign icon-white"></i> Remove my request</a>
                </td>
            </tr>
    <?php endforeach; ?>
<?php endif; ?>