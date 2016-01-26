
<!--header-->

<script type='text/javascript' src='<?php echo HTTPPATH; ?>/scripts/autocomplete/jquery.autocomplete.js'></script>
<link href="<?php echo HTTPPATH; ?>/scripts/autocomplete/jquery.autocomplete.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
$().ready(function() {
 	$("#city").autocomplete("<?php echo APIDIR; ?>/findcityStr.php", {
		minChars: 3,
    max: 200,
		width: 320,
		selectFirst: false,
		formatItem: function(data, i, total) {
			return data[1];
		},
		formatResult: function(data, value) {
			return data[1];
		}
	});

	$("#city").result(function(event, data, formatted) {
		if (data) {
			$('#city_id').val(formatted);
		}
	});
});
</script>
<!-- Search City -->
<form id="formsearch" name="formsearch" method="get" action="<?php echo HTTPPATH; ?>/locations/cityDetail">
<input type="text" name="city" id="city" size="20" required />
<input type="hidden" name="city_id" id="city_id" value="" /> 
<input type="submit" name="go" id="go" value="Go" /><br />
</form>