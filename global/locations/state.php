<?php
$pageTitle = 'Selecting State/Province';
if (empty($_GET['country'])) {
  header("Location: ".HTTPPATH."/locations/country");
  exit;
}
$country = $_GET['country'];
$url = APIHTTPPATH.'/locations/states.php?id='.$country ;
$stateList = curlget($url);
$stateList = json_decode($stateList, 1);
?>
<?php //include(SITEDIR.'/locations/searchcity.php'); ?>
<!-- body -->
<form method="get" name="form1" id="form1" action="<?php echo HTTPPATH; ?>/locations/city">
<select name="state" id="state">
<option value="">Select---</option>
<?php if (!empty($stateList)) {
  foreach ($stateList as $state) {
?>
<option value="<?php echo $state['id']; ?>"><?php echo $state['state']; ?></option>
<?php
  }
}
?>
</select>
<input type="submit" name="submit" id="submit" value="Go">
</form>