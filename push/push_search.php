<?php require_once('../Connections/connMain.php'); ?>
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
try {
if (empty($_REQUEST['module'])) {
  throw new Exception('Empty Module');
}
$table = 'push_ref_'.$_REQUEST['module'];
include_once($table.'.php');

$query = '';

$rowRS = array();
if (!empty($_GET['id'])) {
  $queryTmp = ' AND push.uid = '.GetSQLValueString($_GET['id'], 'text');
  
  if (!empty($_GET['module'])) {
    $queryTmp .= ' AND push.module = '.GetSQLValueString($_GET['module'], 'int');
  }

  $queryRS = "SELECT push.*, g.*, ".$table.".*, push.push_id FROM push LEFT JOIN google_auth as g ON push.uid = g.uid LEFT JOIN  $table ON $table.push_id = push.push_id WHERE 1 $queryTmp GROUP BY push.push_id";
  $rsView = mysql_query($queryRS, $connMain) or die(mysql_error());
  $rowRS = mysql_fetch_assoc($rsView);
  if ($table == 'push_ref_4') {
    $tmpData = json_decode($rowRS['push_value'], 1);
    if (!empty($tmpData['fromage']) && is_numeric($tmpData['fromage'])) {
      $query .= ' AND ('.$table.'.age >= '.GetSQLValueString($tmpData['fromage'], 'int').')';
    }//end if
    if (!empty($tmpData['toage']) && is_numeric($tmpData['toage'])) {
      $query .= ' AND ('.$table.'.age <= '.GetSQLValueString($tmpData['toage'], 'int').')';
    }//end if
    if (!empty($rowRS['looking_for_male']) || !empty($rowRS['looking_for_female'])) {
      if ($rowRS['looking_for_male'] == 'true' && $rowRS['looking_for_female'] == 'true') {
        $query .= ' AND ('.$table.'.my_gender = "Male" OR '.$table.'.my_gender = "Female")';
      } else if ($rowRS['looking_for_male'] == 'true') {
        $query .= ' AND ('.$table.'.my_gender = "Male")';
      } else if ($rowRS['looking_for_female'] == 'true') {
        $query .= ' AND ('.$table.'.my_gender = "Female")';
      }
    }//end if looking for
    if (!empty($tmpData['radius'])) {
      $_GET['radius'] = $tmpData['radius'];
    }
  }//end if table = push ref 4
}//end if id

if (!empty($_GET['k']) && !empty($_GET['v'])) {
  if (is_array($_GET['k']) && is_array($_GET['v'])) {
    $joint = 'AND';
    if (!empty($_GET['joint']) && $_GET['joint'] == 'OR') {
      $joint = $_GET['joint'];
    }
    $query .= ' AND (';
    $tmp = array();
    foreach ($_GET['k'] as $k => $v) {
      $tmp[] = '('.$table.'.'.$v.' = '.GetSQLValueString($_GET['v'][$k], 'text').')';
    }
    $query .= implode(' '.$joint.' ', $tmp);
    $query .= ')';
  } else {
    $query .= ' AND ('.$table.'.'.$_GET['k'].' = '.GetSQLValueString($_GET['v'], 'text').')';
  }
}
if (!empty($_GET['kor']) && !empty($_GET['vor'])) {
  if (is_array($_GET['kor']) && is_array($_GET['vor'])) {
    $joint = 'OR';
    if (!empty($_GET['joint']) && $_GET['joint'] == 'AND') {
      $joint = $_GET['joint'];
    }
    $query .= ' AND (';
    $tmp = array();
    foreach ($_GET['kor'] as $k => $v) {
      $tmp[] = '('.$table.'.'.$v.' = '.GetSQLValueString($_GET['vor'][$k], 'text').')';
    }
    $query .= implode(' '.$joint.' ', $tmp);
    $query .= ')';
  } else {
    $query .= ' AND ('.$table.'.'.$_GET['kor'].' = '.GetSQLValueString($_GET['vor'], 'text').')';
  }
}
//custom for push_ref_4
if (!empty($_GET['agefrom']) && is_numeric($_GET['agefrom'])) {
  $query .= ' AND ('.$table.'.age >= '.GetSQLValueString($_GET['agefrom'], 'int').')';
}
if (!empty($_GET['ageto']) && is_numeric($_GET['ageto'])) {
  $query .= ' AND ('.$table.'.age <= '.GetSQLValueString($_GET['ageto'], 'int').')';
}

//custom for push_ref_4

if (!empty($_GET['module'])) {
  $query .= ' AND push.module = '.GetSQLValueString($_GET['module'], 'int');
}
if (!empty($_GET['push_id'])) {
  $query .= ' AND push.push_id = '.GetSQLValueString($_GET['push_id'], 'int');
}
if (!empty($_GET['uid'])) {
  $query .= ' AND push.uid = '.GetSQLValueString($_GET['uid'], 'text');
}
if (!empty($_GET['id'])) {
  $query .= ' AND push.uid != '.GetSQLValueString($_GET['id'], 'text');
}
$location = '';
$distance = '';
$orderby = '';
if (!empty($_GET['lat']) && !empty($_GET['lon'])) {
  $distance = ', (ROUND(DEGREES(ACOS(SIN(RADIANS('.GetSQLValueString($_GET['lat'], "double").')) * SIN(RADIANS(push.lat)) + COS(RADIANS('.GetSQLValueString($_GET['lat'], "double").')) * COS(RADIANS(push.lat)) * COS(RADIANS('.GetSQLValueString($_GET['lon'], "double").' -(push.lon)))))*60*1.1515,2)) as distance';
  $orderby = 'order by distance asc';
  if (!empty($_GET['radius'])) {
    $r = !empty($_GET['radius']) ? $_GET['radius'] : 100000;
    $location = ' AND ((ROUND(DEGREES(ACOS(SIN(RADIANS('.GetSQLValueString($_GET['lat'], "double").')) * SIN(RADIANS(push.lat)) + COS(RADIANS('.GetSQLValueString($_GET['lat'], "double").')) * COS(RADIANS(push.lat)) * COS(RADIANS('.GetSQLValueString($_GET['lon'], "double").' -(push.lon)))))*60*1.1515,2)) <= '.GetSQLValueString($r, "int").')';
  }
}

$currentPage = 'http://world.mkgalaxy.com.'.$_SERVER["PHP_SELF"];

$maxRows_rsView = 200;
$pageNum_rsView = 0;
if (isset($_GET['pageNum_rsView'])) {
  $pageNum_rsView = $_GET['pageNum_rsView'];
}
$startRow_rsView = $pageNum_rsView * $maxRows_rsView;

mysql_select_db($database_connMain, $connMain);
$query_rsView = "SELECT push.*, g.*, ".$table.".*, push.push_id $distance FROM push LEFT JOIN google_auth as g ON push.uid = g.uid LEFT JOIN  $table ON $table.push_id = push.push_id WHERE push.status = 1 $query $location GROUP BY push.push_id $orderby";
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

$data = array();
$from = '';
$to = '';
$qs = $query_rsView;
$url = array('first' => '', 'previous' => '', 'next' => '', 'last' => '');
$total = $totalRows_rsView;

if ($totalRows_rsView > 0) {
  do {
    $data[] = $row_rsView;
  } while ($row_rsView = mysql_fetch_assoc($rsView));
  //added new modules
  if (function_exists('process')) {
    $data = process($data, $rowRS);
  }
  //special case
  if ($table == 'push_ref_4') {
    list($matched, $unmatched, $fav, $blocked) = filter($data, $rowRS);
  }
  //special case ends
  //module ends here

  $from = $startRow_rsView + 1;
  $to = min($startRow_rsView + $maxRows_rsView, $totalRows_rsView);
  if ($pageNum_rsView > 0) {
    $url['first'] = sprintf("%s?pageNum_rsView=%d%s", $currentPage, 0, $queryString_rsView);
    $url['previous'] = sprintf("%s?pageNum_rsView=%d%s", $currentPage, max(0, $pageNum_rsView - 1), $queryString_rsView);
  }
  if ($pageNum_rsView < $totalPages_rsView) {
    $url['next'] = sprintf("%s?pageNum_rsView=%d%s", $currentPage, min($totalPages_rsView, $pageNum_rsView + 1), $queryString_rsView);
    $url['last'] = sprintf("%s?pageNum_rsView=%d%s", $currentPage, $totalPages_rsView, $queryString_rsView);
  }

}

mysql_free_result($rsView);
  $return = array('success' => 1, 'msg' => '', 'data' => $data, 'from' => $from, 'to' => $to, 'url' => $url, 'total' => $total, 'query_rsView' => $query_rsView);
  //special case
  if ($table == 'push_ref_4') {
    $return['matched'] = !empty($matched) ? $matched : array();
    $return['unmatched'] = !empty($unmatched) ? $unmatched : array();
    $return['fav'] = !empty($fav) ? $fav : array();
    $return['blocked'] = !empty($blocked) ? $blocked : array();
  }
  $return['rowRS'] = $rowRS;
  //special case ends
} catch (Exception $e) {
  $return = array('success' => 0, 'msg' => $e->getMessage());
}

$return['get'] = $_GET;
$return['post'] = $_POST;
$content = json_encode($return);
if (!empty($_GET['jsoncallback'])) {
	echo $_GET["jsoncallback"]."(".$content.")";
} else {
	echo $content;
}
?>