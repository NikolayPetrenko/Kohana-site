<?php if($count): ?>
  <select id="sub-categories" name="category_id" class="input-xlarge">
    <?php foreach($sub_categories as $category): ?>
      <option <?php echo @$current_category != 0 && $current_category == $category->id ? 'selected="selected"' : '' ?> value="<?php echo $category->id?>"><?php echo $category->name ?></option>
    <?php endforeach; ?>
  </select>
<?php endif; ?>