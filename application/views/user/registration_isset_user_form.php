<div class="row">
        <div class="span5">
          <a href="#" class="thumbnail">
            <img src="<?php echo Helper_Image::instance()->getCachePatch('user.avatars', $user->avatar, 'large') ?>" alt="">
          </a>
        </div>
        <div class="span10">
            <?php echo $user->firstname.' '.$user->lastname ?>
        </div>
        <p>
            <label>Password</label>
            <input type="password" value="" >
        </p>
  
        <div>
            <a class="btn btn-mini btn-primary" type="submit" href="javascript:void(0)" onclick="javascript:registration_twitter.checkExistUserPassword(this)">Yes</a>
            <a class="btn btn-mini" href="javascript:void(0)" onclick="javascript:registration_twitter.cancelEvent(this)"  type="submit">No</a>
	</div>
  
</div>


