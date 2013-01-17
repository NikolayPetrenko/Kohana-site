<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>
<div class="row">
  <div class="span7">
    <?php if(count($pets)):?>
      <?php if(count($pets) > 1):?>
        <div id="petFeedsCarousel" class="carousel slide">
        <div class="carousel-inner">
      <?php endif; ?>  
            <?php foreach ($pets as $key => $pet):?>
                <div class="<?php echo $key== 0 ? 'active' : '' ?> item">
                  <div>
                  <img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $pet->picture, 'thumb', $pet->id.'/') ?>" alt="">
                  <span><?php echo $pet->name?></span>
                  <hr/>
                  
                  <?php if($pet->finds->order_by('date_created', 'desc')->find()->pet_id): ?>
                    <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">x</button>
                      <b> Info!: </b> <a href="<?php echo URL::site('alerts/details/find/'.$pet->finds->order_by('date_created', 'desc')->find()->id) ?>"><?php echo $pet->finds->order_by('date_created', 'desc')->find()->address ?> </a>
                    </div>
                  <?php elseif($pet->lost->pet_id): ?>
                    <div class="alert alert-error">
                      <button type="button" class="close" data-dismiss="alert">x</button>
                      <b> Reminder: </b> <?php echo $pet->name?> was Lost. 
                    </div>
                  <?php elseif(!$pet->tag->qrcode): ?>
                    <?php echo View::factory('tag/alert')->set('pet', $pet)->render() ?>
                  <?php endif; ?>
                  
                  </div>
                </div>
            <?php endforeach;?>
      <?php if(count($pets) > 1):?>
        </div>
        <a class="carousel-control left" href="#petFeedsCarousel" data-slide="prev">&lsaquo;</a>
        <a class="carousel-control right" href="#petFeedsCarousel" data-slide="next">&rsaquo;</a>
      </div>
      <?php endif; ?>  
    <?php endif; ?>
  </div>
  
  <div class="span3">
    <?php if(count($suggested_friends)):?>
      <h3>My Friends</h3>
      <?php foreach ($suggested_friends as $friend):?>
      <a href="<?php echo URL::site($friend->id)?>"><img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $friend->picture, 'thumb', $friend->id.'/') ?>" alt="<?php echo $friend->name ?>" ></a>
      <?php endforeach; ?>
    <?php endif; ?>  
  </div>
  
</div>

<!-- Feeds Container -->
<?php if(isset($feeds) && count($feeds)):?>
    <div class="row">
        <div id="feeds-container" class="span16">
            <hr/>
            <?php echo View::factory('feeds/partial/feeds')->set('feeds', $feeds)->render();?>
        </div>
    </div>
<?php endif; ?>

<?php if( $feeds_count > Kohana::$config->load('config')->get('lists.count')):?>
    <div class="row">
        <div id ="controls" class="span9 pull-right">
            <a class="btn btn-small btn-inverse" id="more-feeds" href="javascript:void(0)"  >
                <i class="icon-download icon-white"></i> More Feeds
            </a>
            <img id="spinner" src="<?php echo URL::base().Kohana::$config->load('config')->get('scpinner.url') ?>" style="display: none;">
        </div>
    </div>
<?php endif; ?>
    




