<?php
if (!empty($_REQUEST['to'])) {
  mail($_REQUEST['to'], $_REQUEST['subject'], $_REQUEST['message'], "From:<admin@mkgalaxy.com>");
}
echo 'Success';
?>