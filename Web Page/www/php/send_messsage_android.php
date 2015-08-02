<?php
require_once 'message_class.php';

$message = new message();
$error = $message->send($_POST);

if($error == null)
{
	$error['Success'] = true;
}

echo json_encode($error);

?>
