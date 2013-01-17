<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>

<div class="row">
    <div class="span3">
          <a href="#" class="thumbnail">
            <img src="<?php echo Helper_Image::instance()->getCachePatch('location.pictures', $location->picture, 'large') ?>" alt="">
          </a>
    </div>
    <div class="span10">
        <form action="" method="post">
            <table class="table">
                <tr>
                    <th>Name</th>
                    <td><?php echo $location->name ? $location->name : '---' ?></td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td><?php echo $location->category->name ? $location->category->name : '---' ?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?php echo $location->phone ? $location->phone : '---' ?></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><?php echo $location->description ? $location->description : '---'  ?></td>
                </tr>
                <tr>
                    <th>Confirm</th>
                    <td><?php echo $location->isConfirm ? 'Yes' : 'No' ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo $location->address ?></td>
                </tr>
            </table>
            <div class="buttons">
                <a class="btn" href="<?php echo URL::site('map')?>">Back to Map</a>
            </div>
        </form>
    </div>
</div> 



<?php if(count($check_in_pets)): ?>

<h3>Check In Pets</h3>

<table class="table table-condensed">
    <thead>
        <tr>
            <th>Photo</th>
            <th>Category</th>
            <th>Name</th>
            <th>Owner</th>
            <th>Breed</th>
        </tr>
    </thead>
    <tbody>
          <?php foreach($check_in_pets as $pet):?>
              <tr>
                <td>
                  <a href="<?php echo URL::site($pet->id) ?>" ><img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $pet->picture, 'thumb', $pet->id.'/') ?>" alt="<?php echo $pet->name ?>"></a>
                </td>
                <td>
                  <?php echo $pet->type->type?> 
                </td>
                <td>
                  <a href="<?php echo URL::site($pet->id) ?>" ><?php echo $pet->name ?></a>
                </td>
                <td>
                  <a href="<?php echo URL::site('users/'.$pet->owner->id) ?>" ><?php echo $pet->owner->firstname . ' ' . $pet->owner->lastname ?></a>
                </td>
                <td>
                  <?php echo $pet->breed->breed?> 
                </td>
              </tr> 
          <?php endforeach; ?>
    </tbody>
</table>
<?php endif;?>