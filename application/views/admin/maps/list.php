<?php Helper_Output::renderErrors() ?>


<div class="container">
 <div class="row">
   <div class="span12">
     <table id="locations" class="table table-condensed">
              <thead>
                      <tr>
                              <th>ID</th>
                              <th>Name</th>
                              <th>Category</th>
                              <th>Phone</th>
                              <th style="width: 10%" >Status</th>
                              <th style="width: 10%">Confirm</th>
                              <th style="width: 10%">Users Confirms</th>
                              <th style="width: 20%">Actions</th>
                      </tr>
              </thead>
      </table>
   </div>
   </div>



<div class="buttons">
  <a class="btn btn-primary" href="<?php echo URL::site('admin/maps/add_location') ?>">Add new Location</a>
</div>
    </div>