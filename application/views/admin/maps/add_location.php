<?php Helper_Output::renderErrors() ?>
<div class="span3">
  <a href="#" class="thumbnail">
    <img src="<?php Helper_Image::instance()->getCachePatch('location.pictures', $location->picture, 'large') ?>" alt="">
  </a>
  <input type="file" name="image">
</div>
<div class="span3">
<form class="form-horizontal" method="POST" action="<?php echo URL::site('admin/maps/add_location') ?>">
    <input type="hidden" name="id" value="<?php echo $location->id?>">
    <input type="hidden" name="picture" value="<?php echo $location->picture?>">
    
    <fieldset>
      <legend>Add New Location</legend>
        
          <div class="control-group">
            <label class="control-label" for="description">Status</label>
            <div class="controls">
              <select name="status" class="input-xlarge">
                <option <?php echo $location->status == 1 ? 'selected="selected"' : '' ?> value="1">Active</option>
                <option <?php echo $location->status == 0 ? 'selected="selected"' : '' ?> value="0">Inactive</option>
              </select>
            </div>
          </div>
        
          <div class="control-group">
            <label class="control-label" for="name">Name</label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="email" name="name" value="<?php echo $location->name?>">
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="category_id">Categories</label>
            <div class="controls">
              <select id="main_categories" name="category_id" class="input-xlarge">
              <?php foreach($categories as $category): ?>
                    <option <?php echo $location->category_id == $category->id ? 'selected="selected"' : '' ?> value="<?php echo $category->id?>"><?php  echo $category->name ?></option>
              <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="description">Description</label>
            <div class="controls">
              <textarea name="description" class="input-xlarge" id="description"><?php echo $location->description?></textarea>
            </div>
          </div>
        
         <div class="control-group">
            <label class="control-label" for="address">Address</label>
            <div class="controls">
              <textarea name="address" class="input-xlarge" id="address"><?php echo $location->address ?></textarea>
            </div>
          </div>
          <?php $phone = Helper_Output::buildUSAPhoneForInputs($location->phone); ?>
          <div class="control-group">
            <label class="control-label" for="phone">Phone</label>
            <div class="controls">
              <input type="text"  class="input-mini phone_js" name="phone[]" rel="1" maxlength="3" autocomplete="off" value="<?php echo $phone[0]?>" > -
              <input type="text"  class="input-mini phone_js" name="phone[]" rel="2" maxlength="3" autocomplete="off" value="<?php echo $phone[1]?>" > -
              <input type="text"  class="input-mini phone_js" name="phone[]" rel="3" maxlength="4" autocomplete="off" value="<?php echo $phone[2]?>" >
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="latitude">Latitude</label>
            <div class="controls">
              <input type="text"  class="input-xlarge" name="latitude" value="<?php echo $location->point->latitude?>">
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="longitude">Longitude</label>
            <div class="controls">
              <input type="text"  class="input-xlarge" name="longitude" value="<?php echo $location->point->longitude?>">
            </div>
          </div>

            <div class="control-group">
              <label class="control-label" for="lastname">Position</label>
              <div class="controls">
                <div id="location-map"></div>
              </div>
            </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Location</button>
            <a class="btn" href="<?php echo URL::site('admin/users/list') ?>">Cancel</a>
          </div>
    </fieldset>
</form>
  </div>