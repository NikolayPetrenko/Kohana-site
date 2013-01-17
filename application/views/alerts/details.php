<?php if($details): ?>
  <script type="text/javascript">
      var details_info = {
                            status   : '<?php echo $type?>',
                            latitude : '<?php echo $details->point->latitude?>',
                            longitude: '<?php echo $details->point->longitude?>'
                          };
  </script>

  <?php if($type == 'find'):?>
    <h1>Find Info</h1>
  <?php elseif ($type == 'lost') : ?>
    <h1>Lost Info</h1>
  <?php endif; ?>

  <div class="row">
      <div class="span3">
            <a href="#" class="thumbnail">
              <img src="<?php echo @$details->picture ? $details->picture : URL::base().'img/260x180.gif'  ?>" alt="">
            </a>
      </div>
      <div class="span10">
          <form action="" method="post">
              <table class="table">
                  <tr>
                      <th>Name</th>
                      <td><?php echo @$details->name ? $details->name : 'Unknown' ?></td>
                  </tr>
                  <tr>
                      <th>Type</th>
                      <td><?php echo @$details->type ? $details->type : 'Unknown' ?></td>
                  </tr>
                  <tr>
                      <th>Breed</th>
                      <td><?php echo @$details->breed ? $details->breed : 'Unknown' ?></td>
                  </tr>
                  <tr>
                      <th>Age</th>
                      <td><?php echo @$details->age ? $details->age : 'Unknown' ?></td>
                  </tr>
                  <?php if($type == 'find' || $type == 'lost'):?>
                  <tr>
                      <th>Owner</th>
                      <td><a href="<?php echo URL::site('users/'.$details->owner_id)?>"><?php echo $details->owner ?></a></td>
                  </tr>
                  <?php endif; ?>
                  <tr>
                      <th>Description</th>
                      <td><?php echo $details->description ? $details->description : '---'  ?></td>
                  </tr>
                  <?php if($type == 'find' || $type == 'unknown'):?>
                  <tr>
                      <th>Finder Name</th>
                      <td><?php echo $details->finder_name ? $details->finder_name : '---'  ?></td>
                  </tr>
                  <?php endif; ?>
                  <tr>
                      <th>Find Address</th>
                      <td><?php echo $details->address ? $details->address : '---'  ?></td>
                  </tr>
                  <tr>
                      <th>Position</th>
                      <td><div id="location-map"></div></td>
                  </tr>

              </table>
              <div class="buttons">
                  <a class="btn" href="<?php echo URL::site('alerts')?>">Alerts List</a>
              </div>
          </form>
      </div>
  </div> 
    <?php else: ?>
    <h1>No Information</h1>
    <?php endif; ?>

