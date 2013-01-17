<div class="row">
  <div id ="controls" class="span9 pull-right">
    <a class="btn btn-inverse" href="javascript:void(0)" onclick="javascript:index.addMoreAlerts(this, '<?php echo $status?>')" >
        <i class="icon-download icon-white"></i> More Alerts
    </a>
    <img id="spinner" src="<?php echo URL::base().Kohana::$config->load('config')->get('scpinner.url') ?>" style="display: none;">
  </div>
</div>