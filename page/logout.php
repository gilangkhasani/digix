<div class="row hr-container-content">
	<?php
	
	$user = new stdClass;
	$user->name_roles = 'anonim';
	$user->roles = 'anonim';
	$user->name = 'anonim';
	$user->username = 'anonim';
	unset($_SESSION[$sessionname]);
	$_SESSION[$sessionname] = $user;
	echo '<section id="main" class="bg-one">';
	echo status('Logout');
	echo '</section>';
	echo redirect('home');
	?>
</div>