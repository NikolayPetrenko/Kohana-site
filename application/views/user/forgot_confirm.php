<h1><?php echo $text ?> </h1>
<?php Helper_IO::grab('js') ?>
<script type="text/javascript">
	setTimeout(function() {
          window.location.href = "<?php echo URL::base().'/users/login' ?>";
	}, 4000);
</script>
<?php Helper_IO::stop() ?>