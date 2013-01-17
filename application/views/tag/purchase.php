<div class="row">
  <img src="<?php echo Helper_Image::instance()->getCachePatch('pets.pictures', $pet->picture, 'medium', $pet->id.'/') ?>" alt="">
  <p><?php echo $pet->name ?></p>
  <div class="span11">
  <form class="form-horizontal" action="" method="POST">
            <h3>Contact Info</h3>
            <input type="hidden" name="stripe_token" value="">
            <div class="control-group">
              <label class="control-label">Address</label>
              <div class="controls" id="status-container">
                <input placeholder="Address" type="text" name="user[address]" value="<?php echo $me->address ?>">
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label">City</label>
              <div class="controls" id="status-container">
                <input placeholder="City" type="text" name="user[city]" value="<?php echo $me->city ?>">
              </div>
            </div>
          
            <div class="control-group">
              <label class="control-label">State</label>
              <div class="controls" id="status-container">
                <input placeholder="State" type="text" name="user[state]" value="<?php echo $me->state ?>">
              </div>
            </div>
          
            <div class="control-group">
              <label class="control-label">Zip</label>
              <div class="controls" id="status-container">
                <input placeholder="Zip" type="text" name="user[zip]" value="<?php echo $me->zip ?>">
              </div>
            </div>
          
            <div class="control-group">
              <label class="control-label">Primary Phone</label>
              <div class="controls" id="status-container">
                <input placeholder="Primary Phone" type="text" name="user[primary_phone]" value="<?php echo $me->primary_phone ?>">
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label">Secondary Phone</label>
              <div class="controls" id="status-container">
                <input placeholder="Secondary Phone" type="text" name="user[secondary_phone]" value="<?php echo $me->secondary_phone ?>">
              </div>
            </div>
  </form>
            <h3>Payment Info</h3>
            <div class="control-group">
                <label class="control-label">Card Number</label>
                <div class="controls" id="status-container">
                  <input type="text" size="20" autocomplete="off" placeholder="" class="card-number"/>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">Card Name</label>
                    <div class="controls" id="status-container">
                <select class="card-name">
                  <option value="Visa">Visa</option>
                  <option value="American Express">American Express</option>
                  <option value="Master Card">Master Card</option>
                </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">CVC</label>
                <div class="controls" id="status-container">
                    <input type="text" size="4" autocomplete="off" class="card-cvc"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Expiration (MM/YYYY)</label>
                <div class="controls" id="status-container">
                  <input type="text" size="2" value="" class="card-expiry-month"/>
                  <span> / </span>
                  <input type="text" size="4" value="" class="card-expiry-year"/>
                </div>
            </div>
            <div class="buttons">
                <button type="submit"  class="btn submit-button">Order</button>
            </div>
       
  </div>
</div>