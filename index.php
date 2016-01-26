<?php
include_once('config.php');
$title = 'My Home';
if (!empty($_GET['p'])) {
	$page = $_GET['p'];
}
ob_start();
if (!file_exists($page.'.php')) $page = 'home';
include_once($page.'.php');
$content_for_layout = ob_get_clean();
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title; ?></title>
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<link rel="stylesheet" href="<?php echo HTTP_PATH; ?>/styles/main.css" media="screen" type="text/css">
</head>

<body>
</body>
<div>
<?php echo $content_for_layout; ?>
</div>
</html>