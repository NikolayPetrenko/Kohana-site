<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>
<?php if($fb_friends_count) : ?>
	<table class="table table-condensed">
		<thead>
			<tr>
                                <th>Avatar</th>
				<th>Name</th>
                                <th>Actions</th>
			</tr>
		</thead>
		<tbody>
                  <?php echo View::factory('friends/partial/facebook_friends')->set('fb_friends', $fb_friends)->render() ?>
		</tbody>
	</table>
<?php endif; ?>
<?php echo View::factory('friends/partial/more_controls')->set('count', $fb_friends_count)->set('control_id', 'facebook-friends')->render() ?>
