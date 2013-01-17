<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<title><?php echo $title ?></title>
	<meta charset="UTF-8">
        <link href="/favicon.ico" rel="icon" type="image/x-icon" />
	<script type="text/javascript">
		var SYS = {
			baseUrl      : '<?php echo URL::base() ?>',
                        total_list   : '<?php echo Kohana::$config->load('config')->get('lists.count') ?>'
		}
	</script>
	<?php echo Helper_Output::renderCss(); ?>
</head>


<body>
    <div class="navbar">
        <div class="navbar-inner">
          <div class="container">
            <a class="brand" href="<?php echo URL::base() ?>">Unleashed</a>
            <div class="nav-collapse">
              <?php Helper_MainMenuHelper::render(); ?>
              <ul class="nav pull-right">
                  <?php if(Auth::instance()->logged_in()): ?>
                    <li class="divider-vertical"></li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class=" icon-cog"></i> Howdy, <?php echo Helper_iAuth::instance()->getLoggedUser()->firstname ?>!<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                          <li><a href="<?php echo URL::base() . 'users/profile' ?>"><i class="icon-user"></i> My Account</a></li>
                          <?php if(Helper_iAuth::instance()->getLoggedUser()->roles->order_by('role_id', 'desc')->find()->name == 'admin'): ?>
                            <li><a href="<?php echo URL::base() . 'admin/users/list' ?>"><i class="icon-lock"></i> Administration</a></li>
                          <?php endif; ?>
                          <li class="divider"></li>
                          <li><a href="<?php echo URL::base() . 'users/logout' ?>"><i class="icon-off"></i> Logout</a></li>
                        </ul>
                      </li>
                <?php else: ?>
                      <li><a href="<?php echo URL::base() . 'users/login' ?>">Login</a></li>
                      <li><a href="<?php echo URL::base() . 'users/registrate' ?>">Registration</a></li>
                <?php endif; ?>
              </ul>
            </div><!-- /.nav-collapse -->
          </div>
        </div><!-- /navbar-inner -->
    </div>
    <div class="container maincontainer">
            <?php Helper_Alert::get_flash(); ?>
            <?php echo $content ?>
          <div id="content-clear"></div>    
    </div>
    <footer class="footer">
      <div style="text-align:center"><i class="icon-thumbs-up"></i> <?php echo ORM::factory('setting', array('key' => 'footer_text'))->value ?></div>
    </footer>

    <?php echo Helper_Output::renderJs(); ?>
    <?php echo Helper_IO::get('js') ?>
</body>


</html>