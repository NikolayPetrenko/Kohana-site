<div class="alert alert-error">
  <button type="button" class="close" data-dismiss="alert">Dismiss</button>
  <b> Reminder: </b> <?php echo $pet->name?> does not have an Unleshed Tag. 
  <?php if(!$pet->tag->stripe_token): ?>
    <a href="<?php echo URL::site('tag/purchase/'.$pet->id) ?>">Purchase Tag</a>
  <?php else: ?>
    Processing...
  <?php endif; ?>
</div>