<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title><?php echo $title;?></title>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="zh-hk" />
<!-- a -->
<?php foreach($styles as $file => $type) { echo HTML::style($file, array('media' => $type)), "\n"; }?>
<!-- b -->
<?php foreach($scripts as $file) { echo HTML::script($file, NULL, TRUE), "\n"; }?>
</head>
<body>
<!-- c -->
<?php echo $content ?>
</body>
</html>
