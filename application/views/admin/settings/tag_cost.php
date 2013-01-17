<div class="page-header">
	<h1>Tag Cost</h1>
</div>
<?php Helper_Output::renderErrors() ?>
<form class="form-horizontal" action="" method="POST">
    <div class="span5">
        <div class="control-group">
          <label class="control-label" >$</label>
          <div class="controls">
            <input type="text" maxlength="3" name="dollars" value="<?php echo $dollars ?>">
          </div>
        </div>
      
        <div class="control-group">
          <label class="control-label" >&#162;</label>
          <div class="controls">
            <input type="text"  maxlength="2" name="cents" value="<?php echo $cents ?>">
          </div>
        </div>
      
        <div class="form-actions">
          <a class="btn" href="<?php @$back ?>">Cancel</a>
          <input type="submit" class="btn btn-primary" value="Save">
        </div>
    </div>
</form>
