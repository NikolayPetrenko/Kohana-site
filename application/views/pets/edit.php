<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>
<?php if(!$pet->tag->qrcode): ?>
  <?php echo View::factory('tag/alert')->set('pet', $pet)->render() ?>
<?php endif; ?>

<form class="form-horizontal" action="<?php echo URL::site('pets/edit') ?>" method="POST">
        <div class="span3">
          <a href="#" class="thumbnail">
            <img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $pet->picture, 'large', $pet->id.'/') ?>" alt="">
          </a>
          <input type="file" name="image">
          <?php if($pet->tag->qrcode): ?>
            <img src="<?php echo Helper_Image::instance()->getClearCachePatch('pets.tags', $pet->tag->qrcode, 'medium', $pet->id.'/')?> " alt=''>
          <?php endif; ?>
        </div>
          
  
        <div class="span11">
            <input type="hidden" name="id" value="<?php echo $pet->id?>">
            <input type="hidden" name="picture" value="<?php echo $pet->picture?>">
        
            
            
            <div class="control-group">
              <label class="control-label" for="name">Status</label>
              <div class="controls" id="status-container">
                <a href="javascript:void(0)" onclick="javascript:edit.changeStatusObserver(this)"><?php echo $pet->text_status ? $pet->text_status : 'change status' ?></a>
              </div>
            </div>
            
            
            <div class="control-group">
              <label class="control-label" for="name">Name</label>
              <div class="controls">
                <input type="text" name="name" value="<?php echo $pet->name ?>">
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="type">Type</label>
              <div class="controls">
                <select name="type">
                  <?php foreach($types as $type ):?>
                      <option <?php echo $pet->type->id == $type->id ? 'selected="selected"' : '' ?>  value="<?php echo $type->id ?>"> <?php echo $type->type ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="breed">Breed</label>
              <div class="controls">
                <input type="text" id="autocomplete" autocomplete="off" name="breed" value="<?php echo $pet->breed->breed ?>">
              </div>
            </div>

            <div class="control-group">
              <label class="control-label" for="input01">Description</label>
              <div class="controls">
                <textarea name="description"><?php echo $pet->description ?></textarea>
              </div>
            </div>
          
            <div class="control-group">
              <label class="control-label" for="dob">Date of Birthday</label>
              <div class="controls">
                <div class="input-append date"  id="dp3" data-date="<?php echo Helper_Output::siteDateForOldDates($pet->dob) ?>" data-date-format="mm-dd-yyyy">
                  <input class="span2" name="dob" size="16" type="text"  value="<?php echo Helper_Output::siteDateForOldDates($pet->dob)?>" >
                  <span class="add-on"><i class="icon-calendar"></i></span>
                </div>
              </div>
            </div>
          
            <div class="control-group">
              <label class="control-label" for="isLost">Status</label>
              <div class="controls">
                <input type="radio" name="isLost" <?php echo $pet->lost->pet_id ? '' : 'checked="checked"' ?> value="0"> Home
                <input type="radio" name="isLost" <?php echo $pet->lost->pet_id ? 'checked="checked"' : '' ?> value="1"> Lost
              </div>
            </div>
            <div id="lost-form-container">
                <?php if($pet->lost->pet_id):?>
                    <?php echo View::factory('pets/partial/lost_form')->set('pet', $pet)->set('me', $me)->render() ?>
                <?php endif; ?>
            </div>
          
            <div class="form-actions">
              <a class="btn" href="<?php echo URL::site('pets/list') ?>">Cancel</a>
              <input type="submit" class="btn btn-primary" value="Save">
            </div>
            
        </div>
</form>