<?php foreach($fb_friends as $fb_friend): ?>
        <tr>
                <td><a href="<?php echo 'http://www.facebook.com/profile.php?id='.$fb_friend['id'] ?>" target="_blank" ><img src="<?php echo 'https://graph.facebook.com/'.$fb_friend['id'].'/picture?type=small'; ?>" /></a></td>
                <td><a href="<?php echo 'http://www.facebook.com/profile.php?id='.$fb_friend['id'] ?>" target="_blank" ><?php echo $fb_friend['name'] ?></a></td>
                <td>
                    <?php if($fb_friend['unleashed_id']): ?>
                        <?php if($fb_friend['friendship'] === null): ?>
                            <a class="btn btn-mini" href="javascript:void(0)" onclick="javascript:facebook_list.addFBFriend(<?php echo $fb_friend['id'] ?>, this)"><i class="icon-plus-sign"></i>Add in Friends</a>
                        <?php elseif ($fb_friend['friendship'] == 0):?>
                            <a class="btn btn-mini disabled" href="javascript:void(0)"><i class="icon-question-sign"></i> Waiting...</a>
                        <?php elseif ($fb_friend['friendship'] == 1):?>
                            <a class="btn btn-mini btn-success disabled" href="javascript:void(0)"><i class="icon-ok-sign icon-white"></i> Already in Friends</a>
                        <?php endif; ?>
                    <?php else:?>
                        <a class="btn btn-mini" href="javascript:void(0)" onclick="javascript:facebook_list.invite(<?php echo $fb_friend['id'] ?>, this)"><i class="icon-envelope"></i> Send Invite</a>
                    <?php endif; ?>
                </td>
        </tr>
<?php endforeach; ?>