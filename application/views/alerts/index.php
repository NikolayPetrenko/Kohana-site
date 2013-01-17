<div class="tabbable"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Lost</a></li>
    <li><a href="#tab2" data-toggle="tab">Find</a></li>
    <li><a href="#tab3" data-toggle="tab">Unknown</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
      
      <table class="table table-condensed">
          <thead>
              <tr>
                  <th>Photo</th>
                  <th>Name</th>
                  <th>User Info</th>
                  <th>Status</th>
                  <th>Details</th>
              </tr>
          </thead>
          <tbody id="lost">
              <?php echo View::factory('alerts/partial/index')->set('alerts', $lost_alerts)->render() ?>
          </tbody>
      </table>

      <?php if( $lost_alerts_count > Kohana::$config->load('config')->get('lists.count')):?>
        <?php echo View::factory('alerts/partial/mode_control')->set('status', 'lost')->render() ?>
      <?php endif; ?>
      
    </div>
    <div class="tab-pane" id="tab2">
      
      <table class="table table-condensed">
          <thead>
              <tr>
                  <th>Photo</th>
                  <th>Name</th>
                  <th>User Info</th>
                  <th>Status</th>
                  <th>Details</th>
              </tr>
          </thead>
          <tbody id="find">
              <?php echo View::factory('alerts/partial/index')->set('alerts', $find_alerts)->render() ?>
          </tbody>
      </table>

      <?php if( $find_alerts_count > Kohana::$config->load('config')->get('lists.count')):?>
        <?php echo View::factory('alerts/partial/mode_control')->set('status', 'find')->render() ?>
      <?php endif; ?> 
      
    </div>
    <div class="tab-pane" id="tab3">
      
      <table class="table table-condensed">
          <thead>
              <tr>
                  <th>Photo</th>
                  <th>Name</th>
                  <th>User Info</th>
                  <th>Status</th>
                  <th>Details</th>
              </tr>
          </thead>
      <tbody id="unknown">
        <?php echo View::factory('alerts/partial/index')->set('alerts', $unknown_alerts)->render() ?>
      </tbody>
      </table>

      <?php if( $unknown_alerts_count > Kohana::$config->load('config')->get('lists.count')):?>
        <?php echo View::factory('alerts/partial/mode_control')->set('status', 'unknown')->render() ?>
      <?php endif; ?>
      
    </div>
  </div>
</div>