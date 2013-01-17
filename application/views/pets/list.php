<?php Helper_Tab::render() ?>
<?php Helper_Output::renderErrors() ?>
<table class="table table-condensed">
        <thead>
                <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Breed</th>
                        <th>Birthday</th>
                        <th>Tag</th>
                        <th>Lost</th>
                        <th style="width: 25%">Actions</th>
                </tr>
        </thead>
</table>
<div class="buttons">
  <a class="btn btn-primary" href="<?php echo URL::site('pets/edit') ?>">Add new Pet</a>
</div>