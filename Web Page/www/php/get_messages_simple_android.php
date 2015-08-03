<?php
require_once 'message_class.php';
require_once 'user_class.php';
require_once 'db_connect.php';

$out = array();

if(isset($_SESSION['userid']))
{
	$dbhandle = db_connect();
	$stmt = $dbhandle->stmt_init();

	$stmt->prepare("SELECT MessageID, Time, SenderID, ReadFlag, Content, TaskID FROM Messages WHERE (ReceiverID=?)");
	$stmt->bind_param("i",$_SESSION['userid']);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($messageid, $temptime, $senderid, $readflag, $content, $taskid);	

	$i=0;
	while($stmt->fetch())
	{
		$sender = new user();
		$sender->getFromDB($senderid);
		
		$message = array();
		$message['MessageID']=$messageid;
		$message['Time']=$temptime;
		$message['SenderID']=$senderid;
		$message['SenderUsername']=$sender->username;
		$message['Read']=$readflag;
		$message['Content']=$content;
		$message['TaskID']=$taskid;
		
		$out[$i]=$message;
		$i++;
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
