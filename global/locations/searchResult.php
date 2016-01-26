<?php
if (empty($_GET['q'])) {
  header("Location: ".HTTPPATH."/locations/country");
  exit;
}
$q = $_GET['q'];
$pageTitle = 'Search Results';
$url = APIHTTPPATH.'/locations/findcity.php?q='.urlencode($q);
$cityList = curlget($url);
$cityList = json_decode($cityList, 1);
?>
<!-- body -->
<h3>Select City</h3>
<?php if (empty($cityList)) { ?>
<div>No Cities Found.</div>
<?php } else { ?>
  <ul>
  <?php foreach ($cityList as $cities) { ?>
  <li><a href="<?php echo makecityurl($cities['id'], $cities['city']); ?>"><?php echo $cities['city']; ?>, <?php echo $cities['statename']; ?>, <?php echo $cities['countryname']; ?></a></li>
  <?php } ?>
  </ul>
<?php } ?>