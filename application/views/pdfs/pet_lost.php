<table class="table">
        <tr>
                <th>First Name</th>
                <td><?php echo $user->firstname ? $user->firstname : '---' ?></td>
        </tr>
        <tr>
                <th>Last Name</th>
                <td><?php echo $user->lastname ? $user->lastname : '---' ?></td>
        </tr>
        <tr>
                <th>Email</th>
                <td><?php echo $user->email ? $user->email : '---' ?></td>
        </tr>
        <tr>
                <th>Last Login</th>
                <td><?php echo Helper_Output::siteDate($user->last_login)?></td>
        </tr>
        <tr>
                <th>State</th>
                <td><?php echo $user->state ? $user->state : '---'  ?></td>
        </tr>

        <tr>
                <th>City</th>
                <td><?php echo $user->city ? $user->city : '---'  ?></td>
        </tr>

        <tr>
                <th>Address</th>
                <td><?php echo $user->address ? $user->address : '---'  ?></td>
        </tr>

        <tr>
                <th>Zip</th>
                <td><?php echo $user->zip ? $user->zip : '---'  ?></td>
        </tr>

        <tr>
                <th>Primary Phone</th>
                <td><?php echo $user->primary_phone ? $user->primary_phone : '---' ?></td>
        </tr>

        <tr>
                <th>Secondary Phone</th>
                <td><?php echo $user->secondary_phone ? $user->secondary_phone : '---' ?></td>
        </tr>

        <tr>
                <th>Date of Birthday</th>
                <td><?php echo Helper_Output::siteDateForOldDates($user->dob) ?></td>
        </tr>
</table>

<h3>LOST PET</h3>
<table class="table">
        <tr>
                <img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $pet->picture, 'large', $pet->id.'/') ?>" alt="">
                <?php if($pet->tag->qrcode): ?>
                <img src="<?php echo Helper_Image::instance()->getClearCachePatch('pets.tags', $pet->tag->qrcode, 'thumb', $pet->id.'/')?> " alt=''>
                <?php endif; ?>
        </tr>
        <tr>
                <th>Pet Name</th>
                <td><?php echo $pet->name ? $pet->name : '---' ?></td>
        </tr>
        
        <tr>
                <th>Pet Type</th>
                <td><?php echo $pet->type->type ? $pet->type->type : '---' ?></td>
        </tr>
        <tr>
                <th>Pet Breed</th>
                <td><?php echo $pet->breed->breed ? $pet->breed->breed : '---'?></td>
        </tr>
        
        <tr>
                <th>Pet Description</th>
                <td><?php echo $pet->description ? $pet->description : '---'?></td>
        </tr>
        
        <tr>
                <th>Last Seen</th>
                <td><?php echo $pet->lost->last_seen ? $pet->lost->last_seen  : '---'?></td>
        </tr>
        <tr>
                <th>Date of Birthday</th>
                <td><?php echo Helper_Output::siteDateForOldDates($pet->dob) ?></td>
        </tr>
</table>
