<?php
$MODULES = loadModules();
?><!DOCTYPE html>
<html>
	
	<head>
		<meta charset='utf-8'/>
		<title><?php echo $APPNAME; ?></title>
		<link rel="icon" type="image/png" href="./skin/icon.png" />
		<link rel='stylesheet' type='text/css' href='./skin/home.css'/>
		<?php echo $MODULES['styles']; ?>
	</head>
	
	<body>
		<div id='LOADING_OVERLAY'></div>
		
		<section id='desktop'></section>
		
		<nav id='taskbar'>
			<div id='OpenDoor'></div>
			<div id='trail-list'></div>
		</nav>
		
		<iframe id='IFRAME' style="display:none;"></iframe>
		
		<script src='./scripts/jQuery.js'></script>
		<script src='./scripts/sakado.js'></script>
		<script src='./scripts/input.js'></script>
		<script src='./scripts/templates.js'></script>
		<script src='./scripts/contextmenu.js'></script>
		<?php echo $MODULES['scripts']; ?>
		<script src='./scripts/init.js'></script>
	</body>
</html>