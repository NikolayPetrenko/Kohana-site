<?php if(count($feeds)):?>
    <?php foreach ($feeds as $feed):?>
        <div class="feed-item">
            <div class="row">
                <div class="span3">
                  <?php echo Helper_Output::siteDateWithClientTimeZone($feed->date_created); ?>
                  <?php //echo date('l, F jS g:i a', strtotime($feed->date_created));?>
                </div>
                <div class="span1">
                  <img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $feed->image, 'thumb', $feed->pet_id.'/') ?>" >
                </div>
                <div class="span10"><?php Helper_Output::getBagesForFeeds($feed->code_name) ?> <?php echo $feed->feed?>
                    
                    <?php if($feed->code_name == 'add_pet'):?>
                      (<a href="<?php echo URL::site($feed->pet_id) ?>">details</a>)
                    <?php else:?>
                      (<a href="<?php echo URL::site('feeds/details/'.$feed->feed_id) ?>">details</a>)
                    <?php endif; ?>
                </div>
            </div>
          <br>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
    




