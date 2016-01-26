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

session_start();
include('functions.php');
checkLogin();
  
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$_POST['ad_modified_dt'] = date('Y-m-d H:i:s');
  $_POST['ad_images'] = '';
  if (!empty($_POST['image'])) {
      $_POST['ad_images'] = json_encode($_POST['image']);
  }
  $_POST['ad_questions'] = '';
  if (!empty($_POST['question'])) {
    $arr = array();
    foreach ($_POST['question'] as $k => $v) {
        $arr[] = array('question' => $v, 'options' => isset($_POST['option'][$k]) ? $_POST['option'][$k] : array());
    }
    $_POST['ad_questions'] = json_encode($arr);
  }

  $_POST['uid'] = $_SESSION['user']['id'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO donationforforex_ads (`uid`, ad_title, ad_desc, ad_images, ad_questions, ads_lat, ads_lon, ad_address, ad_modified_dt, ad_comments) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['uid'], "text"),
                       GetSQLValueString($_POST['ad_title'], "text"),
                       GetSQLValueString($_POST['ad_desc'], "text"),
                       GetSQLValueString($_POST['ad_images'], "text"),
                       GetSQLValueString($_POST['ad_questions'], "text"),
                       GetSQLValueString($_POST['lat'], "double"),
                       GetSQLValueString($_POST['lng'], "double"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['ad_modified_dt'], "date"),
                       GetSQLValueString($_POST['ad_comments'], "text"));

  mysql_select_db($database_connMain, $connMain);
  $Result1 = mysql_query($insertSQL, $connMain) or die(mysql_error());

  $id = mysql_insert_id();
  $insertGoTo = "business_manage_add_confirm.php?id=".$id;
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

?>
<!DOCTYPE HTML>
<html><!-- InstanceBegin template="/Templates/right-sidebar.dwt.php" codeOutsideHTMLIsLocked="false" -->
	<head>
  <meta charset="UTF-8">
	<!-- InstanceBeginEditable name="doctitle" -->
	<title>Ad Manager</title>
	<!-- InstanceEndEditable -->
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,700,500,900' rel='stylesheet' type='text/css'>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script src="themes/templated-linear/js/skel.min.js"></script>
		<script src="themes/templated-linear/js/skel-panels.min.js"></script>
		<script src="themes/templated-linear/js/init.js"></script>
		<!-- InstanceBeginEditable name="head" -->
    <script src="js/jquery.steps/jquery.steps.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet" type="text/css"/>
<link href="../cash/css/wizard.css" rel="stylesheet"/>
<style>
    #map-canvas {
        height: 100%;
        width: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
<script language="javascript">
var geocoder;
var map;
var marker;
function showaddress(lat, lng)
{
  filllatlng(lat,lng)
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        //console.log(results);
        var len = (results[0].address_components).length;
        var zip =  results[0].address_components[(len-1)].short_name;
        var country =  results[0].address_components[(len-2)].short_name;
        var state =  results[0].address_components[(len-3)].short_name;
        var county =  results[0].address_components[(len-4)].short_name;
        var city =  results[0].address_components[(len-5)].short_name;
        //document.title = lat + "|" + lng + "|" + results[0].formatted_address + "|" + zip + "|" + city + "|" + state + "|" + country + "|" + county;
        $('#address').val(results[0].formatted_address);
      } else {
        //alert("Geocoder failed due to: " + status);
      }
    });
}
function codeAddress() {
  var address = document.getElementById('address').value;
  if (!address) {
    return false;
  }
  deleteMarkers();
  geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      /*map.setCenter(results[0].geometry.location);
      marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location
      });*/
      showmap(results[0].geometry.location.lat(),results[0].geometry.location.lng());
    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}

// Sets the map on all markers in the array.
function setAllMap(map) {
  marker.setMap(map);
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setAllMap(null);
}
// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  marker = null;
}
function filllatlng(lat,lng)
{
  
  $('#latlng').html(lat + "," + lng);
  $('#lat').val(lat);
  $('#lng').val(lng);
}
function showmap(lat,lng)
{
  filllatlng(lat,lng);
  var myLatlng = new google.maps.LatLng(lat,lng);
  var mapOptions = {
    zoom: 17,
    center: myLatlng,
    panControl: true,
    zoomControl: true,
    scaleControl: true
  }
  map = new google.maps.Map(document.getElementById('map-canvas'),
                                mapOptions);
  marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
	  draggable:true,
  });
  
  google.maps.event.addListener(marker, 'dragend', function() {
    // 3 seconds after the center of the map has changed, pan back to the
    // marker.
	  
    showaddress(marker.getPosition().lat(), marker.getPosition().lng());
  });
}
function displayLocation( position ) {
  var lat = position.coords.latitude;
  var lng = position.coords.longitude;
  showaddress(lat, lng);
  showmap(lat,lng);
}
function handleError( error ) {
	var errorMessage = [ 
		'We are not quite sure what happened.',
		'Sorry. Permission to find your location has been denied.',
		'Sorry. Your position could not be determined.',
		'Sorry. Timed out.'
	];

	alert( errorMessage[ error.code ] );
}
function initialize() {
    useragent = navigator.userAgent;
    navigator.geolocation.getCurrentPosition( displayLocation, handleError );
}
google.maps.event.addDomListener(window, 'load', initialize);

</script>
		<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
		<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
		<!-- InstanceEndEditable -->
			<link rel="stylesheet" href="themes/templated-linear/css/skel-noscript.css" />
			<link rel="stylesheet" href="themes/templated-linear/css/style.css" />
			<link rel="stylesheet" href="themes/templated-linear/css/style-desktop.css" />
	</head>
	<body>
	<!-- Header -->
		<div id="header">
			<div id="nav-wrapper"> 
				<!-- Nav -->
				<nav id="nav">
					<?php include('menu.php'); ?>
				</nav>
			</div>
			<div class="container"> 
				
				<!-- Logo -->
				<div id="logo">
					<?php include('submenu.php'); ?>
				</div>
			</div>
		</div>
	<!-- Header --> 

	<!-- Main -->
		<div id="main">
			<div class="container">
				<div class="row">

					<!-- Content -->
					<div id="content" class="8u skel-cell-important">
						<section>
<!-- InstanceBeginEditable name="EditRegion3" -->
							<header>
								<h2>Ad Manager</h2>
								<span class="byline">Create Your Ad</span>
							</header>
<form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
<div id="wizard">
    <h1>Location</h1>
    <div>
    <div id="latlng"></div>
    <input type="hidden" name="lat" id="lat" value="">
    <input type="hidden" name="lng" id="lng" value="">
    <span id="spry_address">
    <input type="text" name="address" id="address" style="width:80%;">
    <span class="textfieldRequiredMsg">Address is required.</span></span>
     <input type="button" value="Find Address" onclick="codeAddress()">
    <div id="map-canvas"></div>
    
    </div>
    <h1>Description</h1>
    <div>
    <div>
      <label for="ad_title"><strong>Ad Title:</strong></label><br />
      <span id="spryTitle">
      <input type="text" name="ad_title" id="ad_title" style="width:90%;">
      <span class="textfieldRequiredMsg">A value is required.</span></span> </div>
    <div>
      <label for="ad_desc"><strong>Ad Description:</strong></label><br />
      <span id="spryDescription">
      <textarea name="ad_desc" id="ad_desc" rows="10" style="width:90%"></textarea>
      <span id="countspryDescription">&nbsp;</span><span class="textareaRequiredMsg">A value is required.</span><span class="textareaMinCharsMsg">Minimum number of characters (#10) not met.</span></span> </div>
    </div>
 
    <h1>Images</h1>
    <div>
      <input type="button" name="addImg" value="Add Image" onClick="addImage();" /><br /><br /><br />
      <div id="imageList">
        
      </div>
    </div>
    <h1>Questions</h1>
    <div>
    <p>
      <input type="button" name="addquestion" value="Add Question" onClick="addNewQuestion();" />
      <div id="questionlist"></div>
    </p>
    </div>
</div>
<input type="hidden" name="uid" value="" />
<input type="hidden" name="ad_images" value="" />
<input type="hidden" name="ad_questions" value="" />
<input type="hidden" name="ad_modified_dt" value="" />
<input type="hidden" name="uid" value="" />
<input type="hidden" name="ad_comments" value="" />
<input type="hidden" name="MM_insert" value="form1">
</form>

<div id="tmp" style="display:none">
  <label><strong>Question:</strong></label>
  <input type="text" name="question[]"> <input type="button" name="addoption" value="Add Option" onClick="addNewOption()" /><br /><div id="optionlist"></div><br /><br />
</div>
<div id="tmp2" style="display:none">
  <label><strong>Option:</strong></label>
  <input type="text" name="option[]"><br />
</div>
<div id="tmp3" style="display:none">
  <input type="text" name="image[]" style="width:90%"><br />
</div>

<script>
  $("#wizard").steps({
    transitionEffect: "slideLeft",
    onFinished: function (event, currentIndex)
    {
        $('#form1').submit();
    }
  });
  var increment = -1;
  function addNewQuestion()
  {
    increment++;
    var html = $('#tmp').html();
    var res = html.replace('question[]', 'question['+increment+']');
    var res2 = res.replace('addNewOption()', 'addNewOption('+increment+')');
    var res3 = res2.replace('optionlist', 'optionlist_'+increment);
    $('#questionlist').append(res3);
  }
  function addNewOption(i)
  {
    var html = $('#tmp2').html();
    var res = html.replace('option[]', 'option['+i+'][]');
    $('#optionlist_'+i).append(res);
  }
  function addImage()
  {
    var html = $('#tmp3').html();
    $('#imageList').append(html);
  }
</script>

            <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("spry_address", "none", {validateOn:["blur"]});
            </script>
<!-- InstanceEndEditable -->
						</section>
					</div>

					<!-- Sidebar -->
					<div id="sidebar" class="4u">
            <!-- InstanceBeginEditable name="EditRegion4" -->
						<?php include('business_manager_menu.php'); ?>
            <script type="text/javascript">
var sprytextfield2 = new Spry.Widget.ValidationTextField("spryTitle", "none", {validateOn:["blur"], hint:"Enter Title"});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("spryDescription", {validateOn:["blur"], hint:"Enter Description", minChars:10, counterId:"countspryDescription", counterType:"chars_count"});
            </script>
            <!-- InstanceEndEditable -->
					</div>
					
				</div>
			</div>
		</div>
	<!-- /Main -->

	<!-- Tweet -->
		<div id="tweet">
			<div class="container">
				<section>
					<blockquote>&ldquo;In posuere eleifend odio. Quisque semper augue mattis wisi. Maecenas ligula. Pellentesque viverra vulputate enim. Aliquam erat volutpat.&rdquo;</blockquote>
				</section>
			</div>
		</div>
	<!-- /Tweet -->

	<!-- Footer -->
		<div id="footer">
			<div class="container">
				<section>
					<header>
						<h2>Get in touch</h2>
						<span class="byline">Integer sit amet pede vel arcu aliquet pretium</span>
					</header>
					<ul class="contact">
						<li><a href="#" class="fa fa-twitter"><span>Twitter</span></a></li>
						<li class="active"><a href="#" class="fa fa-facebook"><span>Facebook</span></a></li>
						<li><a href="#" class="fa fa-dribbble"><span>Pinterest</span></a></li>
						<li><a href="#" class="fa fa-tumblr"><span>Google+</span></a></li>
					</ul>
				</section>
			</div>
		</div>
	<!-- /Footer -->

	<!-- Copyright -->
		<div id="copyright">
			<div class="container">
				Design: <a href="http://templated.co">TEMPLATED</a> Images: <a href="http://unsplash.com">Unsplash</a> (<a href="http://unsplash.com/cc0">CC0</a>)
			</div>
		</div>


	</body>
<!-- InstanceEnd --></html>