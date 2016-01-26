<?php require_once('../../Connections/connMain.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsView = 5;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

$userId_rsView = "-1";
if (isset($_GET['uid'])) {
  $userId_rsView = $_GET['uid'];
}
mysql_select_db($database_connMain, $connMain);
$query_rsView = sprintf("SELECT *, (select count(up) FROM law_ratings as ru WHERE ru.id = c.id and ru.up = 1) as up, (select count(down) from law_ratings as rd WHERE rd.id = c.id and rd.down = 1) as down, (select uu.up from law_ratings as uu INNER JOIN google_auth as g ON uu.uid = g.uid WHERE uu.id = c.id AND uu.uid = %s) as user_up, (select ud.down from law_ratings as ud INNER JOIN google_auth as g ON ud.uid = g.uid WHERE  ud.id = c.id AND ud.uid = %s) as user_down FROM law_content as c LEFT JOIN google_auth as g1 ON c.uid = g1.uid WHERE c.status = 'New' ORDER BY c.created DESC", GetSQLValueString($userId_rsView, "int"),GetSQLValueString($userId_rsView, "int"));
$query_limit_rsView = sprintf("%s LIMIT %d, %d", $query_rsView, $startRow_rsView, $maxRows_rsView);
$rsView = mysql_query($query_limit_rsView, $connMain) or die(mysql_error());
$row_rsView = mysql_fetch_assoc($rsView);

if (isset($_GET['totalRows_rsView'])) {
  $totalRows_rsView = $_GET['totalRows_rsView'];
} else {
  $all_rsView = mysql_query($query_rsView);
  $totalRows_rsView = mysql_num_rows($all_rsView);
}
$totalPages_rsView = ceil($totalRows_rsView/$maxRows_rsView)-1;

$queryString_rsView = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsView") == false && 
        stristr($param, "totalRows_rsView") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsView = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsView = sprintf("&totalRows_rsView=%d%s", $totalRows_rsView, $queryString_rsView);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Law Content</title>
<link href="css/core.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>

<body>
<div style="padding:10px;" class="scroll">
<table border="1" cellpadding="5" cellspacing="0">
	<?php do { ?>
		<tr>
			<td valign="top"><?php echo $row_rsView['law']; ?><br />
			<?php 
				if ($row_rsView['user_up'] == 1 || $row_rsView['user_down'] == 1) {
					$out  = '<div class="rateWrapper">';
					$out .= '<span class="rateDone rateUp';
					$out .= $row_rsView['user_up'] == 1 ? ' active' : null;
					$out .= '" data-item="';
					$out .= $row_rsView['id'];
					$out .= '" data-uid="';
					$out .= $userId_rsView;
					$out .= '"><span class="rateUpN">';
					$out .= intval($row_rsView['up']);
					$out .= '</span></span>';
					$out .= '<span class="rateDone rateDown';
					$out .= $row_rsView['user_down'] == 1 ? ' active' : null;
					$out .= '" data-item="';
					$out .= $row_rsView['id'];
					$out .= '" data-uid="';
					$out .= $userId_rsView;
					$out .= '"><span class="rateDownN">';
					$out .= intval($row_rsView['down']);
					$out .= '</span></span>';
					$out .= '</div><br style="clear:both;"/>';
					echo $out;
				} else {
					$out  = '<div class="rateWrapper">';
					$out .= '<span class="rate rateUp" data-item="';
					$out .= $row_rsView['id'];
					$out .= '" data-uid="';
					$out .= $userId_rsView;
					$out .= '"><span class="rateUpN">';
					$out .= intval($row_rsView['up']);
					$out .= '</span></span>';
					$out .= '<span class="rate rateDown" data-item="';
					$out .= $row_rsView['id'];
					$out .= '" data-uid="';
					$out .= $userId_rsView;
					$out .= '"><span class="rateDownN">';
					$out .= intval($row_rsView['down']);
					$out .= '</span></span>';
					$out .= '</div><br style="clear:both;"/>';
					echo $out;
				}
	?>
			</td>
			<td valign="top">- Law Created By<br><?php echo $row_rsView['name']; ?>
			<br>
			<img src="<?php echo $row_rsView['picture']; ?>" width="100" height="100" /></td>
		</tr>
		<tr>
			<td colspan="2" align="center"></td>
		</tr>
		<?php } while ($row_rsView = mysql_fetch_assoc($rsView)); ?>
</table>
<?php if ($pageNum_rsView < $totalPages_rsView) { // Show if not last page ?>
<a href="<?php printf("%s?pageNum_rsView=%d%s", $currentPage, min($totalPages_rsView, $pageNum_rsView + 1), $queryString_rsView); ?>">Next Page</a>
<?php } // Show if not last page ?>
</div>
<script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="js/core.js" type="text/javascript"></script>
<script src="jscroll/jquery.jscroll.min.js" type="text/javascript"></script>
<script language="javascript">
$('.scroll').jscroll();
</script>
</body>
</html>
<?php
mysql_free_result($rsView);
?>
