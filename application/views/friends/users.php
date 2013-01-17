<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>
<div class="row">
    <div class="span9">
        <?php if(count($users)) : ?>
                <table class="table table-condensed">
                        <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                        </thead>
                        <tbody>
                          <?php echo View::factory('friends/partial/users_fr_part')->set('users', $users)->render() ?>
                        </tbody>
                </table>
        <?php else: ?>
                <h2>You don't have Twitter Friends yet</h2>
        <?php endif; ?>
        <?php echo View::factory('friends/partial/more_controls')->set('count', $users_count)->set('control_id', 'unleashed-users')->render() ?>
    </div>
    <div class="span3">
      <input type="text" name="q" placeholder="Search Friend By E-mail">
    </div>
</div>
