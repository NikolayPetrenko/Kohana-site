            <hr/>
                <h3>Broadcast</h3>
            <hr/>

             <div class="control-group">
              <label class="control-label" for="facebook_broadcast">Facebook Friends</label>
              <div class="controls">
                <select name="lost[facebook_broadcast]" >
                  <option value="1"> YES
                  <option value="0"> No
                </select>
              </div>
            </div>
              
            <div class="control-group">
              <label class="control-label" for="lost[twitter_broadcast]">Twitter Followers</label>
              <div class="controls">
                <select name="twitter_broadcast" >
                  <option value="1"> YES
                  <option value="0"> No
                </select>
              </div>
            </div>
            
            
            

            <hr/>
                <h3>Lost Details</h3>
            <hr/>
            <?php if($pet->lost->pdf): ?>
            <div class="control-group">
              <label class="control-label">PDF</label>
              <div class="controls">
                <a onclick="if(confirm('You want download?')){return true}else{return false}" href="<?php echo URL::base().substr(Kohana::$config->load('config')->get('pdf.path'), 2).$pet->lost->pdf.'#page=1' ?>" target=\"_blank\">
                  <img src="<?php echo URL::base().'img/pdf_icon.gif' ?>">
                </a>
              </div>
            </div>
            <?php endif; ?>
            <div class="control-group">
              <label class="control-label">Address</label>
              <div class="controls">
                <input type="text" disabled="disabled" value="<?php echo $me->address ?>">
                <?php if(!$me->address): ?>
                <label class="error"><a target="_blank" href="<?php echo URL::site('users/profile/edit')?>">Please, set your address and phone in  profile. This help to contact with you</a></label>
                <?php endif; ?>
              </div>
            </div>
            

            <div class="control-group">
              <label class="control-label">Primary Phone</label>
              <div class="controls">
                <input type="text" disabled="disabled" value="<?php echo $me->primary_phone ?>">
              </div>
            </div>

            <div class="control-group">
              <label class="control-label">Secondary Phone</label>
              <div class="controls">
                <input type="text" disabled="disabled" value="<?php echo $me->secondary_phone ?>">
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label" for="lost[last_seen]">Last Seen</label>
              <div class="controls">
                <input type="text" id="lost-address" name="lost[last_seen]" value="<?php echo $pet->lost->last_seen ?>">
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label" for="lost[latitude]">Latitude</label>
              <div class="controls">
                <input type="text" id="latitude" name="lost[latitude]" value="<?php echo $pet->lost->getPoint()->latitude ?>">
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label" for="lost[longitude]">Longitude</label>
              <div class="controls">
                <input type="text" id="longitude" name="lost[longitude]" value="<?php echo $pet->lost->getPoint()->longitude ?>">
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label" >Location</label>
              <div class="controls">
                <div id="location-map"></div>
              </div>
            </div>