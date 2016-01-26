<?php
session_start();
include_once('../functions.php');

if (empty($_SESSION['user'])) {
  header("Location: ../user/login.php");
  exit;
}
$url = 'http://world.mkgalaxy.com/api/help/websites/view?uid='.$_SESSION['user']['id'].'&token=&cache=0';
$r = curlget($url);
$result = json_decode($r, 1);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Administration</title>
</head>

<body>
<h1>Administration</h1>
<p><a href="new.php?&token=">Create New Website
</a></p>
<?php if (!empty($result['data'])) { ?>
<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td><strong>Domain</strong></td>
    <td><strong>Name</strong></td>
    <td><strong>Edit</strong></td>
    <td><strong>Delete</strong></td>
  </tr>
  <?php foreach ($result['data'] as $res) {
    $detail = json_decode($res['content'], 1);
  ?>
  <tr>
    <td><?php echo $detail['domain']; ?></td>
    <td><?php echo $detail['name']; ?></td>
    <td><a href="new.php?website_id=<?php echo $res['id']; ?>&token=">Edit</a></td>
    <td><a href="delete.php?website_id=<?php echo $res['id']; ?>&token=">Delete</a></td>
  </tr>
  <?php } ?>
</table>
<?php } ?>
<p>&nbsp;</p>
</body>
</html>