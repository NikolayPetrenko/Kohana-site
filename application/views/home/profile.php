
<?php if($logget_user): ?>
  <?php Helper_Tab::render() ?>
<?php endif; ?>

<?php Helper_Output::renderErrors() ?>



<div class="row">
    <div class="span3">
          <a href="#" class="thumbnail">
            <img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $pet->picture, 'large', $pet->id.'/') ?>" alt="">
          </a>
          <?php if($pet->tag->qrcode): ?>
            <img src="<?php echo Helper_Image::instance()->getClearCachePatch('pets.tags', $pet->tag->qrcode, 'medium', $pet->id.'/')?> " alt=''>
          <?php endif; ?>
    </div>
    <div class="span10">
        <table class="table">
            <?php if($pet->text_status):?>
            <tr>
                <th>Status</th>
                <td><?php echo $pet->text_status ?></td>
            </tr>
            <?php endif; ?>
          
            <tr>
                <th>Name</th>
                <td><?php echo $pet->name ? $pet->name : '---' ?></td>
            </tr>
            <tr>
                <th>Category</th>
                <td><?php echo $pet->type->type ? $pet->type->type : '---' ?></td>
            </tr>
            <tr>
                <th>Breed</th>
                <td><?php echo $pet->breed->breed ? $pet->breed->breed : '---' ?></td>
            </tr>
            <tr>
                <th>Owner</th>
                <td><a href="<?php echo URL::site('users/'.$pet->owner->id)?>"><?php echo $pet->owner->firstname.' '.$pet->owner->lastname ?></a></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?php echo $pet->description ? $pet->description : '---'  ?></td>
            </tr>
            <tr>
                <th>Date of Birthday</th>
                <td><?php echo Helper_Output::siteDateForOldDates($pet->dob) ?></td>
            </tr>
        </table>
        <?php if($pet->lost->pet_id):?>
          <div id="lost-cotainer">
              <h3>Now <?php echo $pet->name ?> is lost. Help to find</h3> 
              <input type="hidden" name="last_seen" value="<?php echo $pet->lost->last_seen ?>">
              <input type="hidden" name="latitude" value="<?php echo $pet->lost->getPoint()->latitude ?>">
              <input type="hidden" name="longitude" value="<?php echo $pet->lost->getPoint()->longitude ?>">
              <div id="location-map">
              </div>
          </div>
        <?php endif;?>
    </div>
</div> 