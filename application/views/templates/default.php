<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title><?php echo $title;?></title>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="zh-hk" />
	<?php foreach($styles as $file => $type) { echo HTML::style($file, array('media' => $type)), "\n"; }?>
	<?php foreach($scripts as $file) { echo HTML::script($file, NULL, TRUE), "\n"; }?>
</head>
<body>
	<div id="doc3" class="yui-t7">
		<div id="hd" role="banner"><h1><?php echo $title;?></h1></div>
		<div id="bd" role="main">
			<div class="yui-g">
				<?php echo $content ?>
			</div>
		</div>
		<div id="ft" role="contentinfo"><p>Footer</p></div>
	</div>
</body>
</html>
