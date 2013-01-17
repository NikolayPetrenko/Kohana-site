<?php if(count($alerts)): ?>
    <?php foreach($alerts as $alert):?>
        <?php if($alert->status == 'lost'): ?>
        <?php $pet = ORM::factory('pet', $alert->about_id);?>
        <tr>
          <td>
            <a href="<?php echo URL::site($pet->id) ?>"><img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $pet->picture, 'thumb', $pet->id.'/') ?>"> </a>
          </td>
          <td>
            <?php echo $pet->name?>
          </td>
          <td>
            <a href="<?php echo URL::site('users/'.$pet->owner->id);?>"><?php echo $pet->owner->firstname . ' ' . $pet->owner->lastname; ?></a>
          </td>
          <td>
            <span class="label label-important">Lost</span>
          </td>
          <td>
            <a class="btn btn-mini" href="<?php echo URL::site('alerts/details/'.$alert->status.'/'.$alert->about_id) ?>"> Show</a>
          </td>
        </tr> 
        <?php elseif ($alert->status == 'find'):  ?>
        <?php $pet_find = ORM::factory('pet_find', $alert->about_id);?>
        <tr>
          <td>
            <a href="<?php echo URL::site($pet_find->pet->id) ?>"><img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $pet_find->pet->picture, 'thumb', $pet_find->pet->id.'/') ?>"> </a>
          </td>
          <td>
            <?php echo $pet_find->pet->name?>
          </td>
          <td>
            <a href="<?php echo URL::site('users/'.$pet_find->pet->owner->id);?>"><?php echo $pet_find->pet->owner->firstname . ' ' . $pet_find->pet->owner->lastname; ?></a>
          </td>
          <td>
            <span class="label label-success">Find</span>
          </td>
          <td>
            <a class="btn btn-mini" href="<?php echo URL::site('alerts/details/'.$alert->status.'/'.$alert->about_id) ?>"> Show</a>
          </td>
        </tr> 
        <?php elseif ($alert->status == 'unknown'):  ?>
        <?php $pet_unknown = ORM::factory('user_unknown', $alert->about_id) ?>
        <tr>
          <td>
            <a href="<?php echo URL::site($pet_unknown->id) ?>"><img src="<?php echo Helper_Image::instance()->getCachePatch('unknown.pets.pics', $pet_unknown->picture, 'thumb') ?>"> </a>
          </td>
          <td>
            <p>Unknown</p>
          </td>
          <td>
            <a href="<?php echo URL::site('users/'.$pet_unknown->user->id);?>"><?php echo $pet_unknown->user->firstname . ' ' . $pet_unknown->user->lastname; ?></a>
          </td>
          <td>
            <span class="label label-inverse">Unknown</span>
          </td>
          <td>
            <a class="btn btn-mini" href="<?php echo URL::site('alerts/details/'.$alert->status.'/'.$alert->about_id) ?>">Show</a>
          </td>
        </tr> 
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif;?>