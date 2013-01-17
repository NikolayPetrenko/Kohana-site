<?php foreach($followers['users'] as $key => $follower): ?>
        <tr>
            <td><img src="<?php echo $follower['avatar'] ?>" /></td>
            <td><?php echo $follower['name'] ?></td>
            <td>
              <?php if($follower['unleashed_id'] !== null): ?>
                  <?php if($follower['friendship'] === null): ?>
                      <a class="btn btn-mini" href="javascript:void(0)" onclick="javascript:twitter.addTWTFriend(<?php echo $follower['id'] ?>, this)"><i class="icon-plus-sign"></i> Add in Friends</a>
                  <?php elseif ($follower['friendship'] == 0):?>
                      <a class="btn btn-mini disabled" href="javascript:void(0)"><i class="icon-question-sign"></i> Waiting...</a>
                  <?php elseif ($follower['friendship'] == 1):?>
                      <a class="btn btn-mini btn-success disabled" href="javascript:void(0)"><i class="icon-ok-sign"></i> Already in Friends</a>
                  <?php endif; ?>
              <?php else:?>
                      <a class="btn btn-mini" href="javascript:void(0)" onclick="javascript:twitter.invite('<?php echo $follower['url'] ?>', this)" ><i class="icon-envelope"></i> Send Invite</a>
              <?php endif; ?>
            </td>
        </tr>
<?php endforeach; ?>