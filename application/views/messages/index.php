<?php if(isset($threads) && count($threads)):?>
<h3> Dialogs </h3>
<hr/>
    <?php foreach ($threads as $tread): ?>  
        <div class="row">
            <div class="span-6">
                <a href="<?php echo URL::site('users/'.$tread->tread_user_id) ?>"><img src="<?php echo Helper_Image::instance()->getCachePatch('user.avatars', $tread->avatar, 'thumb') ?>"></a>
            </div>
            <div class="span-7">
                <blockquote>
                    <p><a href="<?php echo URL::site('messages/dialog/'.$tread->tread_user_id)?>"><?php echo $tread->last_message ?></a></p>
                    <small><?php echo $tread->firstname . ' ' . $tread->lastname . ' (' . $tread->last_message_date . ')'?></small>
                </blockquote>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


