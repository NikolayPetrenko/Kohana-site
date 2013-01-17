<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>


<div id="categories" class="btn-group" data-toggle="buttons-radio">
  <button data-rel="0" class="btn active">All Categories</button>
  <?php foreach($location_categories as $key => $category):?>
  <button data-rel="<?php echo $category->id ?>" class="btn"><?php echo $category->name ?></button>
  <?php endforeach; ?>
</div>

<hr/>

<div id="location-map">
</div>
