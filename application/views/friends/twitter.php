<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>
<?php if(count($followers)) : ?>
        <script type="text/javascript">
          var twitter_cursor = '<?php echo $followers['next_cursor'] ?>';
        </script>
	<table class="table table-condensed">
		<thead>
			<tr>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Actions</th>
			</tr>
		</thead>
		<tbody>
                  <?php echo View::factory('friends/partial/twitter_friends')->set('followers', $followers)->render() ?>
		</tbody>
	</table>
<?php endif; ?>
<?php if( $followers['next_cursor'] != 0):?>
  <div class="row">
    <div id ="controls" class="span9 pull-right">
      <a class="btn btn-large btn-info" id="twitter-followers" href="javascript:void(0)"  >
          <i class="icon-download icon-white"></i> More Friends
      </a>
      <img id="spinner" src="<?php echo URL::base().Kohana::$config->load('config')->get('scpinner.url') ?>" style="display: none;">
    </div>
  </div>
<?php endif; ?>