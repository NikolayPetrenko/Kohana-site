<?php foreach($requests_for_me as $key => $friend): ?>
        <tr>
            <td><a href="<?php echo URL::site('users/'.$friend->id)?>"><img src="<?php echo Helper_Image::instance()->getCachePatch('user.avatars', $friend->avatar, 'thumb') ?>" alt=""></a></td>
            <td><a href="<?php echo URL::site('users/'.$friend->id)?>"><?php echo $friend->firstname ?></a></td>
            <td>
                <a class="btn btn-mini btn-success" href="javascript:void(0)" onclick="javascript:unleashed.acceptFriend(<?php echo $friend->id ?>, this)"><i class="icon-ok-sign icon-white"></i> Accept Friendship</a>
                <a class="btn btn-mini btn-danger" href="javascript:void(0)" onclick="javascript:unleashed.cancelFriendRequest(<?php echo $friend->id ?>, this)"><i class="icon-remove-sign icon-white"></i> Cancel Friendship</a>
            </td>
        </tr>
<?php endforeach; ?>