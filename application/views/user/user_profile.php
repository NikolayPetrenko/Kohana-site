<?php if($logget_user):?>
    <?php Helper_Tab::render() ?>
<?php endif; ?>
<?php Helper_Output::renderErrors() ?>
<div class="span3">
  <a href="#" class="thumbnail">
    <img src="<?php echo Helper_Image::instance()->getCachePatch('user.avatars', $profile->avatar, 'large') ?>" alt="">
  </a>
</div>
<div class="span7">
    <table class="table">
        <tr>
                <th>First Name</th>
                <td><?php echo $profile->firstname ? $profile->firstname : '---' ?></td>
        </tr>
        <tr>
                <th>Last Name</th>
                <td><?php echo $profile->lastname ? $profile->lastname : '---' ?></td>
        </tr>
        <tr>
                <th>Email</th>
                <td><?php echo $profile->email ? $profile->email : '---' ?></td>
        </tr>
        <tr>
                <th>Last Login</th>
                <td><?php echo Helper_Output::siteDate($profile->last_login)?></td>
        </tr>
        <tr>
                <th>Address</th>
                <td><?php echo $profile->address ? $profile->address : '---'  ?></td>
        </tr>
        <tr>
                <th>Phone Number</th>
                <td><?php echo $profile->primary_phone ? $profile->primary_phone : '---' ?></td>
        </tr>
        <tr>
                <th>Date of Birthday</th>
                <td><?php echo Helper_Output::siteDateForOldDates($profile->dob) ?></td>
        </tr>
    </table>
    <div class="buttons">
        <?php if($logget_user):?>
            <?php if($profile->id != $logget_user->id):?>
                <?php if($relationship === null): ?>
                    <a class="btn btn-primary" href="javascript:void(0)" onclick="javascript:user_profile.addINFriend(<?php echo $profile->id ?>, this)"><i class="icon-plus-sign icon-white"></i> Add in Friends</a>
                <?php endif; ?>
            <?php endif;?>
        <?php else:?>
                    <a class="btn btn-primary" href="<?php echo URL::site('users/registrate')?>" ><i class="icon-plus-sign icon-white"></i> Registration</a>
        <?php endif; ?>
    </div>
</div>
<div class="span3">
  <?php if(count($suggested_friends)):?>
      <h3>My Friends</h3>
      <?php foreach ($suggested_friends as $friend):?>
      <a href="<?php echo URL::site($friend->id)?>"><img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $friend->picture, 'thumb', $friend->id.'/') ?>" alt="<?php echo $friend->name ?>" ></a>
      <?php endforeach; ?>
  <?php endif; ?>  
</div>

<?php if(count($pets)): ?>
<table class="table table-condensed">
    <thead>
        <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Category</th>
            <th>Breed</th>
        </tr>
    </thead>
    <tbody>
          <?php foreach($pets as $pet):?>
              <tr>
                <td>
                  <a href="<?php echo URL::site($pet->id) ?>" ><img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $pet->picture, 'thumb', $pet->id.'/') ?>" alt="<?php echo $pet->name ?>"></a>
                </td>
                <td>
                  <a href="<?php echo URL::site($pet->id) ?>" ><?php echo $pet->name ?></a>
                </td>
                <td>
                  <?php echo $pet->type->type?> 
                </td>
                <td>
                  <?php echo $pet->breed->breed?> 
                </td>
              </tr> 
          <?php endforeach; ?>
    </tbody>
</table>
<?php endif;?>



