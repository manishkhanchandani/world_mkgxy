<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Paypal</title>
</head>

<body>
<form name="_xclick" action="https://www.paypal.com/us/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo !empty($_REQUEST['to']) ? $_REQUEST['to'] : 'renu09@live.com'; ?>">
<input type="hidden" name="currency_code" value="USD">
<INPUT TYPE="hidden" name="charset" value="utf-8">
<INPUT TYPE="hidden" NAME="return" value="http://world.mkgalaxy.com/push/paypal/success.php">
<INPUT TYPE="hidden" NAME="cancel_return" value="http://world.mkgalaxy.com/push/paypal/cancel.php">
<INPUT TYPE="hidden" NAME="callback_url" value="http://world.mkgalaxy.com/push/paypal/ipn.php">
<input type="hidden" name="item_name" value="<?php echo !empty($_REQUEST['item_name']) ? $_REQUEST['item_name'] : ''; ?>">
<input type="hidden" name="item_number" value="<?php echo !empty($_REQUEST['item_number']) ? $_REQUEST['item_number'] : ''; ?>">
<input type="hidden" name="amount" value="<?php echo !empty($_REQUEST['amount']) ? $_REQUEST['amount'] : ''; ?>">
<input type="hidden" name="custom" value="<?php echo !empty($_REQUEST['custom']) ? $_REQUEST['custom'] : ''; ?>">
<input type="image" src="http://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form>
</body>
</html>