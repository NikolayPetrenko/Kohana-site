<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>


<h3> Dialog with <?php echo $companion->firstname . ' ' . $companion->lastname?> </h3>
<hr/>
<?php if(isset($dialog) && count($dialog)):?>
    <?php foreach ($dialog as $text): ?>  
    <div class="row">
        <div class="span-6">
          <a href="<?php echo URL::site('users/'.$text->owner->id) ?>"><img src="<?php echo Helper_Image::instance()->getCachePatch('user.avatars', $text->owner->avatar, 'thumb') ?>"></a>
        </div>
        <div class="span-7">
            <blockquote>
                <p><?php echo $text->message ?></p>
                <small><?php echo $text->owner->firstname . ' ' . $text->owner->lastname . ' (' . $text->date_create . ')'?></small>
                <?php if ($text->user_id == $me->id): ?>
                <small class="remove-items" style="display: none" >
                  <a class="btn btn-mini btn-inverse" data-toggle="modal" href="<?php echo '#'.$text->id?>" >Remove</a>
                </small>
                <?php endif ?>
            </blockquote>
        </div>
      
      <div class="modal hide" id="<?php echo $text->id?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h3>Remove This Message</h3>
        </div>
        <div class="modal-body">
          <p><?php echo $text->message?></p>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn" data-dismiss="modal">No</a>
          <a href="#" class="btn btn-primary" onclick="javascript:dialog.removeItem('<?php echo $text->id?>', this)" data-dismiss="modal" >Yes</a>
        </div>
      </div>
      
    </div>
    
    <?php endforeach; ?>
<?php endif; ?>


<form  class="form-horizontal" method="POST" >
  <input type="hidden" name="user_id" value="<?php echo $me->id ?>">
  <input type="hidden" name="addressee_id" value="<?php echo $companion->id ?>">
  <div class="control-group">
    <label class="control-label" for="input01">Send Message</label>
    <div class="controls">
      <textarea name="message"></textarea>
    </div>
  </div>
  
  <div class="form-actions">
    <a class="btn" href="<?php echo URL::site('messages') ?>">Cancel</a>
    <input type="submit" class="btn btn-primary" value="Send">
  </div>
</form>

