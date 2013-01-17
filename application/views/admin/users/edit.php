<?php Helper_Output::renderErrors() ?>
<form class="form-horizontal" method="POST" action="<?php echo URL::site('admin/users/edit') ?>">
  <div class="span3">
    <a href="#" class="thumbnail">
      <img src="<?php echo Helper_Image::instance()->getCachePatch('user.avatars', $user->avatar, 'large') ?>" alt="">
    </a>
    <input type="file" name="image">
  </div>
  
  <div class="span9">
    <input type="hidden" name="id" value="<?php echo $user->id?>">
    <input type="hidden" name="avatar" value="<?php echo $user->avatar?>">
    <fieldset>
      <legend>Edit user</legend>
        <div class="control-group">
          <label class="control-label" for="email">Email</label>
          <div class="controls">
            <input type="text" class="input-xlarge" id="email" name="email" value="<?php echo $user->email?>">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="firstname">First Name</label>
          <div class="controls">
            <input type="text" class="input-xlarge" id="firstname" name="firstname" value="<?php echo $user->firstname?>">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="lastname">Last Name</label>
          <div class="controls">
            <input type="text" class="input-xlarge" id="lastname" name="lastname" value="<?php echo $user->lastname?>">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="state">State</label>
          <div class="controls">
            <input type="text" class="input-xlarge" id="state" name="state" value="<?php echo $user->state?>">
          </div>
        </div>
        
        <div class="control-group">
          <label class="control-label" for="city">City</label>
          <div class="controls">
            <input type="text" class="input-xlarge" id="city" name="city" value="<?php echo $user->city?>">
          </div>
        </div>
      
        <div class="control-group">
          <label class="control-label" for="address">Address</label>
          <div class="controls">
            <input type="text" class="input-xlarge" id="address" name="address" value="<?php echo $user->address?>">
          </div>
        </div>
      

        <div class="control-group">
          <label class="control-label" for="primary_phone">Primary Phone</label>
          <div class="controls">
            <input type="text" class="input-xlarge" id="primary_phone" name="primary_phone" value="<?php echo $user->primary_phone?>">
          </div>
        </div>
      
        <div class="control-group">
          <label class="control-label" for="secondary_phone">Secondary Phone</label>
          <div class="controls">
            <input type="text" class="input-xlarge" id="secondary_phone" name="secondary_phone" value="<?php echo $user->secondary_phone?>">
          </div>
        </div>
      
        <div class="control-group">
          <label class="control-label" for="role_id">Role</label>
          <div class="controls">
            <select name="role_id">
              <?php foreach($roles as $role):?>
                <option <?php echo $user->roles->order_by('role_id', 'desc')->find()->id == $role->id ? 'selected="selected"' : ''?> value="<?php echo $role->id?>"><?php echo $role->name ?></option> 
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label" for="dob">Date of Birthday</label>
          <div class="controls">
            <div class="input-append date"  id="dp3" data-date="<?php echo Helper_Output::siteDateForOldDates($user->dob) ?>" data-date-format="mm-dd-yyyy">
              <input class="span12" name="dob" size="16" type="text"  value="<?php echo Helper_Output::siteDateForOldDates($user->dob)?>" >
              <span class="add-on"><i class="icon-calendar"></i> </span>
            </div>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Save changes</button>
          <a class="btn" href="<?php echo URL::site('admin/users/list') ?>">Cancel</a>
        </div>
    </fieldset>
  </div>
</form>