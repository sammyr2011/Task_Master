<?php
require_once 'message_class.php';
require_once 'user_class.php';
require_once 'db_connect.php';

$out = array();

if(isset($_SESSION['userid']))
{
	$messages = array();
	
	$dbhandle = db_connect();
	$stmt = $dbhandle->stmt_init();

	$stmt->prepare("SELECT MessageID, Time, SenderID, ReadFlag FROM Messages WHERE (ReceiverID=?)");
	$stmt->bind_param("i",$_SESSION['userid']);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($messagedid, $temptime, $senderid, $readflag);	

	while($stmt->fetch())
	{
		$message = new message();
		$message->getFromDB($messageid);
		$sender = new user();
		$sender->getFromDB($message['SenderID']);
		$message['SenderUsername']=$sender->username;

		array_push($out,$message);
	}

	$stmt->close();
	$dbhandle->close();
}
else
{
	$out['error']=true;
}

echo json_encode($out);


?>
