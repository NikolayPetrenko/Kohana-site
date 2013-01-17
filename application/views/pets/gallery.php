<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>


<div class="tabbable tabs-right">
  <ul class="nav nav-tabs">
    <li ><a href="#tab1" data-toggle="tab">Gallery</a></li>
    <li class="active"><a href="#tab2" data-toggle="tab">Photo Manager</a></li>
  </ul>
    <div class="tab-content">
      <div class="tab-pane" id="tab1">
        <?php if(isset($photos) && count($photos)):?>
          <div id="myCarousel" class="carousel slide">
          <!-- Carousel items -->
          <div class="carousel-inner">
                <?php foreach ($photos as $key => $photo):?>
                    <div class="<?php echo $key == 0 ? 'active' : ''?> item">
                        <img width="600px" height="400px" src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $photo->name, 'large', $photo->pet_id.'/') ?>" >
                    </div>
                <?php endforeach;?>
          </div>
          <!-- Carousel nav -->
          <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
          <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>

          </div>
        <?php endif; ?>
    </div>
    <div class="tab-pane active" id="tab2">
      <div class="row">
        <div id ="controls" class="span9 pull-right">
          <input type="file" name="image">
        </div>
      </div>
      <br/>

      <?php if(isset($photos) && count($photos)):?>
          <div class="row" id="photo-container">
              <?php foreach ($photos as $photo):?>
                  <span class="span3 photo-item">
                      <img src="<?php Helper_Image::instance()->getCachePatch('pets.pictures', $photo->name, 'medium', $photo->pet_id.'/') ?>" >
                      <a class="btn btn-mini btn-inverse" id="more-feeds" href="javascript:void(0)" onclick="javascript:gallery.removePhoto('<?php echo $photo->id ?>', this)"  >
                          <i class="icon-remove icon-white"></i> Remove Photo
                      </a>
                  </span>
              <?php endforeach; ?>
          </div>
      <?php endif; ?>

      <hr/>
    </div>
  </div>  
</div>  
      

<!--<div class="row">
    <div id ="controls" class="span9 pull-right">
        <a class="btn btn-small btn-inverse" id="more-feeds" href="javascript:void(0)"  >
            <i class="icon-download icon-white"></i> More Photos
        </a>
        <img id="spinner" src="<?php //echo URL::base().Kohana::$config->load('config')->get('scpinner.url') ?>" style="display: none;">
    </div>
</div>-->