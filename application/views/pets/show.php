<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>
<div class="row">
    <div class="span3">
          <a href="#" class="thumbnail">
            <img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $pet->picture, 'large', $pet->id.'/') ?>" alt="">
          </a>
    </div>
    <div class="span10">
        <form action="" method="post">
            <table class="table">
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
            <div class="buttons">
                <a class="btn" href="<?php echo URL::site('users/'.$pet->owner->id)?>">Owner Profile</a>
            </div>
        </form>
    </div>
</div> 
