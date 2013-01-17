<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>

<div class="tabbable tabs-right">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Unleashed friends</a></li>
    <li><a href="#tab2" data-toggle="tab">Invites for me</a></li>
    <li><a href="#tab3" data-toggle="tab">My Invites</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
        <?php if(count($friends)):?>
            <table id="unleashed-friends-table" class="table table-condensed">
                      <thead>
                              <tr>
                                  <th>Avatar</th>
                                  <th>Name</th>
                                  <th>Actions</th>
                              </tr>
                      </thead>
                      <tbody>
                        <?php echo View::factory('friends/partial/unleashed_fr_part')->set('friends', $friends)->render() ?>
                      </tbody>
              </table>
        <?php else: ?>
        <p> You don't have friend yet</p>
        <?php endif; ?>
        <?php echo View::factory('friends/partial/more_controls')->set('count', $friends_count)->set('control_id', 'unleashed-friends')->render() ?>
    </div>
    
    <div class="tab-pane" id="tab2">
       <?php if(count($requests_for_me)):?>
            <table id="invites-for-me-table" class="table table-condensed">
                      <thead>
                              <tr>
                                  <th>Avatar</th>
                                  <th>Name</th>
                                  <th>Actions</th>
                              </tr>
                      </thead>
                      <tbody>
                          <?php echo View::factory('friends/partial/invites_for_me_fr_part')->set('requests_for_me', $requests_for_me)->render() ?>
                      </tbody>
              </table>
        <?php else: ?>
            <p> You don't have friend yet</p>
        <?php endif; ?>
        <?php echo View::factory('friends/partial/more_controls')->set('count', $requests_for_me_count)->set('control_id', 'invites-for-me')->render() ?>    
    </div>
    
    <div class="tab-pane" id="tab3">
        <?php if(count($my_request)):?>
            <table id="my-invites-table" class="table table-condensed">
                      <thead>
                              <tr>
                                  <th>Avatar</th>
                                  <th>Name</th>
                                  <th>Actions</th>
                              </tr>
                      </thead>
                      <tbody>
                              <?php echo View::factory('friends/partial/my_invites_fr_part')->set('my_request', $my_request)->render() ?>
                      </tbody>
              </table>
        <?php else: ?>
            <p> You don't have friend yet</p>
        <?php endif; ?>
        <?php echo View::factory('friends/partial/more_controls')->set('count', $my_request_count)->set('control_id', 'my-invites')->render() ?>    
    </div>
    
  </div>
</div>