
<script type="text/javascript">
    function setTimezone (){
        if("<?php echo $timezone_offset; ?>".length==0){
            var visitortime = new Date();
            console.log(visitortime);
            var visitortimezoneoffset = -visitortime.getTimezoneOffset()*60;
            $.ajax({
                url: SYS.baseUrl + "users/setInSessionUserTimeZone",
                type: "POST",
                dataType: "json",
                data: $.param({timezone_offset:visitortimezoneoffset}),
            });
        }
    };
</script>

<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>
<?php if($action == 'view') : ?>
        <div class="span3">
          <a href="#" class="thumbnail">
            <img src="<?php echo Helper_Image::instance()->getCachePatch('user.avatars', $me->avatar, 'large') ?>" alt="">
          </a>
        </div>
        <div class="span10">
                  <table class="table">
                          <tr>
                                  <th>First Name</th>
                                  <td><?php echo $me->firstname ? $me->firstname : '---' ?></td>
                          </tr>
                          <tr>
                                  <th>Last Name</th>
                                  <td><?php echo $me->lastname ? $me->lastname : '---' ?></td>
                          </tr>
                          <tr>
                                  <th>Email</th>
                                  <td><?php echo $me->email ? $me->email : '---' ?></td>
                          </tr>
                          <tr>
                                  <th>Last Login</th>
                                  <td><?php echo Helper_Output::siteDate($me->last_login)?></td>
                          </tr>
                          <tr>
                                  <th>State</th>
                                  <td><?php echo $me->state ? $me->state : '---'  ?></td>
                          </tr>
                          
                          <tr>
                                  <th>City</th>
                                  <td><?php echo $me->city ? $me->city : '---'  ?></td>
                          </tr>
                          
                          <tr>
                                  <th>Address</th>
                                  <td><?php echo $me->address ? $me->address : '---'  ?></td>
                          </tr>
                          
                          <tr>
                                  <th>Zip</th>
                                  <td><?php echo $me->zip ? $me->zip : '---'  ?></td>
                          </tr>
                          
                          <tr>
                                  <th>Primary Phone</th>
                                  <td><?php echo $me->primary_phone ? $me->primary_phone : '---' ?></td>
                          </tr>
                          
                          <tr>
                                  <th>Secondary Phone</th>
                                  <td><?php echo $me->secondary_phone ? $me->secondary_phone : '---' ?></td>
                          </tr>
                          
                          <tr>
                                  <th>Date of Birthday</th>
                                  <td><?php echo Helper_Output::siteDateForOldDates($me->dob) ?></td>
                          </tr>
                  </table>
                  <div class="buttons">
                      <a class="btn btn-primary" href="<?php echo URL::site('users/profile/edit') ?>"><i class="icon-pencil icon-white" ></i> Edit Profile</a>
                  </div>
        </div>
<?php endif; ?>
<?php if($action == 'edit') : ?>
        <div class="span3">
          <a href="#" class="thumbnail">
            <img src="<?php echo Helper_Image::instance()->getCachePatch('user.avatars', $me->avatar, 'large') ?>" alt="">
          </a>
          <input class="btn-mini" type="file" name="image">
        </div>
        <div class="span10">
          <form action="" method="post">
                  <input type="hidden" name="avatar" value="<?php echo $me->avatar ?>">
                  <table class="table">
                          <tr>
                                  <th>First Name</th>
                                  <td><input type="text" name="firstname" value="<?php echo $me->firstname ?>"></td>
                          </tr>
                          <tr>
                                  <th>Last Name</th>
                                  <td><input type="text" name="lastname" value="<?php echo $me->lastname ?>"></td>
                          </tr>
                          <tr>
                                  <th>Email</th>
                                  <td><?php echo $me->email ?></td>
                          </tr>
                          <tr>
                                  <th>State</th>
                                  <td><input type="text" name="state" value="<?php echo $me->state ?>"></td>
                          </tr>
                          <tr>
                                  <th>City</th>
                                  <td><input type="text" name="city" value="<?php echo $me->city ?>"></td>
                          </tr>
                          <tr>
                                  <th>Zip</th>
                                  <td><input type="text" name="zip" value="<?php echo $me->zip ?>"></td>
                          </tr>
                          <tr>
                                  <th>Address</th>
                                  <td><textarea name="address"><?php echo $me->address ?></textarea></td>
                          </tr>
                          <tr>
                                  <th>Primary Phone</th>
                                  <td><input type="text" name="primary_phone" value="<?php echo $me->primary_phone ?>"></td>
                          </tr>
                          <tr>
                                  <th>Secondary Phone</th>
                                  <td><input type="text" name="secondary_phone" value="<?php echo $me->secondary_phone ?>"></td>
                          </tr>
                          <tr>
                                  <th>Date of Birthday</th>
                                  <td>
                                    <div class="input-append date"  id="dp3" data-date="<?php echo Helper_Output::siteDateForOldDates($me->dob) ?>" data-date-format="mm-dd-yyyy">
                                      <input class="span2" name="dob" size="16" type="text"  value="<?php echo Helper_Output::siteDateForOldDates($me->dob) ?>" >
                                      <span class="add-on"><i class="icon-calendar"></i></span>
                                    </div>
                                  </td>
                          </tr>
                  </table>
                  <div class="form-actions">
                    <a class="btn" href="<?php echo URL::site('users/profile') ?>">Cancel</a>
                    <input type="submit" class="btn btn-primary" value="Save">
                  </div>
                  
          </form>
        </div>
<?php endif; ?>