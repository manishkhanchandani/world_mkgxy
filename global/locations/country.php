<?php
$pageTitle = 'Select Country';
$url = APIHTTPPATH.'/locations/countries.php';
$countryList = curlget($url);
$countryList = json_decode($countryList, 1);
?>
<!-- body -->
<form method="get" name="form1" id="form1" action="<?php echo HTTPPATH; ?>/locations/state">
<select name="country" id="country">
<option value="">Select---</option>
<?php if (!empty($countryList)) {
  foreach ($countryList as $country) {
?>
<option value="<?php echo $country['id']; ?>"><?php echo $country['country']; ?></option>
<?php
  }
}
?>
</select>
<input type="submit" name="submit" id="submit" value="Go">
</form>