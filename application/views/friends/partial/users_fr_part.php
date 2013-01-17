<?php foreach($users as $user): ?>
        <tr>
                <td><a href="<?php echo URL::site('users/'.$user->id)?>"><img src="<?php echo Helper_Image::instance()->getCachePatch('user.avatars', $user->avatar, 'thumb') ?>" /></a></td>
                <td><a href="<?php echo URL::site('users/'.$user->id)?>"><?php echo $user->firstname ?></a></td>
                <td><?php echo $user->email ?></td>
                <td>
                    <?php if($user->friendship === null): ?>
                        <a class="btn btn-mini" href="javascript:void(0)" onclick="javascript:users.addINFriend(<?php echo $user->id ?>, this)"><i class="icon-plus-sign"></i> Add in Friends</a>
                    <?php elseif ($user->friendship == 0):?>
                        <a class="btn btn-mini disabled" href="javascript:void(0)"><i class="icon-question-sign"></i> Waiting...</a>
                    <?php elseif ($user->friendship == 1):?>
                        <a class="btn btn-mini btn-success disabled" href="javascript:void(0)"><i class="icon-ok-sign icon-white"></i> Already in Friends</a>
                    <?php endif; ?>
                </td>
        </tr>
<?php endforeach; ?>