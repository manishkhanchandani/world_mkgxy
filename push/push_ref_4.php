<?php
include_once('Kundali.php');
function pr($d) {
  echo '<pre>';
  print_r($d);
  echo '</pre>';
}
function getXtra($input=array())
{
  $kundali = new kundali();
  $xtra = array();
  $location = $kundali->dd2dms($input['birthPlace']['lat'], $input['birthPlace']['lon']);
  $xtra['lat_h'] = $location[2];
  $xtra['lat_m'] = $location[4];
  $xtra['lat_s'] = ($location[0] == 'S') ? 1 : 0;
  $xtra['lon_h'] = $location[3];
  $xtra['lon_m'] = $location[5];
  $xtra['lon_e'] = ($location[1] == 'E') ? 1 : 0;
  $zones = $kundali->makeTime(abs($input['birthPlace']['rawOffset']));
  $xtra['zone_h'] = $zones[0];
  $xtra['zone_m'] = $zones[1];
  $tmp = explode('-', $input['dob']);
  $xtra['bday'] = $tmp[2];
  $xtra['bmonth'] = $tmp[1];
  $xtra['byear'] = $tmp[0];
  $xtra['age'] = date('Y') - $xtra['byear'];
  $tmp = explode(':', $input['tob']);
  $xtra['bhour'] = $tmp[0];
  $xtra['bmin'] = $tmp[1];
  $xtra['dst'] = 0;
  if ($input['birthPlace']['rawOffset'] != $input['birthPlace']['dstOffset']) {
    $xtra['dst'] = 1;
  }
  return $xtra;
}

function process($data=array(), $myData=array())
{
  if (empty($data)) return $data;
  if (empty($myData)) return $data;
  $kundali = new kundali();
  $valueMyData = json_decode($myData['push_value'], 1);
  $myXtra = getXtra($valueMyData);
  foreach ($data as $k => $v) {
    if ($v['uid'] == $myData['uid']) {
      unset($data[$k]);
      continue;
    }
    $value = json_decode($v['push_value'], 1);
    $xtra = getXtra($value);
    $data[$k]['details'] = $value;
    $data[$k]['xtra'] = $xtra;
    $data[$k]['calc'] = $kundali->precalculate($data[$k]['xtra']['bmonth'], $data[$k]['xtra']['bday'], $data[$k]['xtra']['byear'], $data[$k]['xtra']['bhour'], $data[$k]['xtra']['bmin'], $data[$k]['xtra']['zone_h'], $data[$k]['xtra']['zone_m'], $data[$k]['xtra']['lon_h'], $data[$k]['xtra']['lon_m'], $data[$k]['xtra']['lat_h'], $data[$k]['xtra']['lat_m'], $data[$k]['xtra']['dst'], $data[$k]['xtra']['lon_e'], $data[$k]['xtra']['lat_s']);
    $data[$k]['my_details'] = $valueMyData;
    $data[$k]['myXtra'] = $myXtra;
    $data[$k]['my_calc'] = $kundali->precalculate($data[$k]['myXtra']['bmonth'], $data[$k]['myXtra']['bday'], $data[$k]['myXtra']['byear'], $data[$k]['myXtra']['bhour'], $data[$k]['myXtra']['bmin'], $data[$k]['myXtra']['zone_h'], $data[$k]['myXtra']['zone_m'], $data[$k]['myXtra']['lon_h'], $data[$k]['myXtra']['lon_m'], $data[$k]['myXtra']['lat_h'], $data[$k]['myXtra']['lat_m'], $data[$k]['myXtra']['dst'], $data[$k]['myXtra']['lon_e'], $data[$k]['myXtra']['lat_s']);
    $data[$k]['from'] = $data[$k]['my_calc'][9];
    $data[$k]['to'] = $data[$k]['calc'][9];
    $data[$k]['fromnaks'] = $data[$k]['my_calc'][7];
    $data[$k]['tonaks'] = $data[$k]['calc'][7];
    $data[$k]['age'] = $xtra['age'];
    $data[$k]['points'] = $kundali->getpoints($data[$k]['from'], $data[$k]['to']);
    unset($data[$k]['details']);
    unset($data[$k]['xtra']);
    unset($data[$k]['calc']);
    unset($data[$k]['my_details']);
    unset($data[$k]['myXtra']);
    unset($data[$k]['my_calc']);
  }
  return $data;
}

function filter($data=array(), $myData=array())
{
  if (empty($data)) return $data;
  if (empty($myData)) return $data;
  $matched = array();
  $unmatched = array();
  $fav = array();
  $blocked = array();
  foreach ($data as $k => $v) {
    if ($v['points'] >= 18) {
      $matched[] = $v;
    } else {
      $unmatched[] = $v;
    }
  }
  return array($matched, $unmatched, $fav, $blocked);
}
?>