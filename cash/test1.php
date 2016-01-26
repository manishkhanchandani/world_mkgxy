<?php
include_once('library/JWT.php');
$sellerIdentifier = '11411111522033196270';
$sellerSecret = 'g5oBRCzFQfphFwbjwHRBEw';
$payload = array(
  "iss" => $sellerIdentifier,
  "aud" => "Google",
  "typ" => "google/payments/inapp/item/v1",
  "exp" => time() + 3600,
  "iat" => time(),
  "request" => array (
    "name" => "Yearly Subscription",
    "description" => "Virtual subscription",
    "price" => "1.99",
    "currencyCode" => "USD",
    "sellerData" => "user_id:1224245,offer_code:3098576987,affiliate:aksdfbovu9j",
  )
);
$cakeToken = JWT::encode($payload, $sellerSecret);
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
<script src="https://sandbox.google.com/checkout/inapp/lib/buy.js"></script>
<script language="javascript">
//Success handler
var successHandler = function(purchaseAction){
  if (window.console != undefined) {
    console.log("Purchase completed successfully.");
  }
}

//Failure handler
var failureHandler = function(purchaseActionError){
  if (window.console != undefined) {
    console.log("Purchase did not complete.");
  }
}

function purchase(){
  var generatedJwt = '<?php echo $cakeToken; ?>';
  google.payments.inapp.buy({
    'jwt'     : generatedJwt,
    'success' : successHandler,
    'failure' : failureHandler
  });
}
</script>
</head>

<body>
<button class="buy-button"
  id="buybutton1" name="buy" type="button"
  onClick="purchase()">
  Buy
</button>
</body>
</html>