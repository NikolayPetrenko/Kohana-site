<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>
<div class="span9">
  <fieldset>
    <legend></legend>
      <div class="control-group">
        <label class="control-label" for="facebook">Facebook ID <img width="25px" height="25px" src="<?php echo URL::base().'/img/facebook_icon.png'?>"></label>
        <div class="controls">
          <span>
            <?php if(empty($me->facebook_token)):?>
              <a href="<?php echo URL::site('settings/link_facebook') ?>"><i class="icon-off"></i> Connect</a>
            <?php else: ?>
              <input type="text" class="input-xlarge" id="facebook" disabled="disabled" value="<?php echo $facebook_user['name']?>">
              <a href="<?php echo URL::site('settings/unlink_facebook') ?>"><i class="icon-off"></i> Disconect</a>
            <?php endif; ?>
          </span>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label" for="twitter">Twitter ID <img src="<?php echo URL::base().'/img/twitter_logo.png'?>"></label>
        <div class="controls">
          <span>
            <?php if(empty($me->twitter_token)):?>
              <a href="<?php echo URL::site('settings/link_twitter') ?>"><i class="icon-off"></i> Connect</a>
            <?php else: ?>
              <input type="text" class="input-xlarge" id="firstname" disabled="disabled" name="twitter" value="<?php echo $twitter_user['screen_name']?>">
              <a href="<?php echo URL::site('settings/unlink_twitter') ?>"><i class="icon-off"></i> Disconect</a>
            <?php endif; ?>
          </span>
        </div>
      </div>
      <div class="form-actions">
      </div>
  </fieldset>
</div>