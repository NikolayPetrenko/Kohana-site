<?php if( $count > Kohana::$config->load('config')->get('lists.count')):?>
  <div class="row">
    <div id ="controls" class="span9 pull-right">
      <a class="btn btn-large btn-info" id="<?php echo $control_id ?>" href="javascript:void(0)"  >
          <i class="icon-download icon-white"></i> More Friends
      </a>
      <img id="spinner" src="<?php echo URL::base().Kohana::$config->load('config')->get('scpinner.url') ?>" style="display: none;">
    </div>
  </div>
<?php endif; ?>