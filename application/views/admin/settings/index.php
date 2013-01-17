<div class="page-header">
	<h1>Site Settings</h1>
</div>

<form class="form-horizontal" action="" method="POST">
    <div class="span11">
        <?php foreach ($settings as $key => $setting): ?>
            <?php if($setting->type == 'input'): ?>
              <?php if($setting->key == 'tag_cost'){ continue;}  //need refactor settngs ?>
              <div class="control-group">
                <label class="control-label" for="<?php echo $setting->key?>"><?php echo $setting->label?></label>
                <div class="controls">
                  <input type="text" style="width: 957px;" name="<?php echo $setting->key?>" value="<?php echo $setting->value ?>">
                </div>
              </div>
            <?php endif; ?>
            <?php if($setting->type == 'textarea'): ?>
              <div class="control-group">
                <label class="control-label" for="<?php echo $setting->key?>"><?php echo $setting->label?></label>
                <div class="controls">
                  <textarea id="<?php echo $key == 1 ? 'input' : ''?>"  style="width: 957px;height: 75px;" name="<?php echo $setting->key?>" ><?php echo $setting->value ?></textarea>
                </div>
              </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="form-actions">
          <a class="btn" href="<?php @$back ?>">Cancel</a>
          <input type="submit" class="btn btn-primary" value="Save">
        </div>
    </div>
</form>
