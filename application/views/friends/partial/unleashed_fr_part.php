<?php foreach($friends as $key => $friend): ?>
    <tr>
        <td><a href="<?php echo URL::site('users/'.$friend->id)?>"><img src="<?php echo Helper_Image::instance()->getCachePatch('user.avatars', $friend->avatar, 'thumb') ?>" alt=""></a></td>
        <td><a href="<?php echo URL::site('users/'.$friend->id)?>"><?php echo $friend->firstname ?></a></td>
        <td>
            <a class="btn btn-mini btn-success" href="<?php echo URL::site('messages/dialog/'.$friend->id) ?>"><i class="icon-envelope icon-white"></i> Send Message</a>
            <a class="btn btn-mini btn-danger" href="javascript:void(0)" onclick="javascript:unleashed.removeFromFriends(<?php echo $friend->id ?>, this)"><i class="icon-remove-sign icon-white"></i> Remove From Friends</a>
        </td>
    </tr>
<?php endforeach; ?>