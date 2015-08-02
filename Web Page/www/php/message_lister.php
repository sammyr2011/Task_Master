<?php

if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}

include_once 'db_connect.php';
include_once 'user_class.php';
include_once 'message_class.php';

//Returns an array of users that the current user has a conversation with.
//Sorted by most recent message.
function getConversationList()
{
	if (isset($_SESSION['userid']))
	{
		$users = array();
		
		$userid;
		
		$dbhandle = db_connect();
		$stmt = $dbhandle->stmt_init();
		
		//We must search both people who messaged you AND people you messaged
		//Ensure no duplicates
		$stmt->prepare("SELECT Distinct UserID FROM(
						(SELECT SenderID AS UserID, Time FROM Messages WHERE ReceiverID = ?)
						UNION ALL
						(SELECT ReceiverID AS UserID, Time FROM Messages WHERE SenderID = ?)
						ORDER BY Time desc
						)t ");
		$stmt->bind_param("ii", $_SESSION['userid'], $_SESSION['userid']);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($userid);
		
		//Add each resulting user to the array to return
		while($stmt->fetch())
		{
			$user = new user();
			$user->getFromDB($userid);
			
			array_push($users, $user);
		}
		
		$stmt->close();
		$dbhandle->close();
		
		return $users;
	}
}

//Returns an array of messages that are sent to/from this userid.
//Called when opening a conversation
function getReadMessages($inUserID)
{
	if (isset($_SESSION['userid']))
	{
		$messages = array();
		
		$messageid;
		
		$dbhandle = db_connect();
		$stmt = $dbhandle->stmt_init();
		
		//Get all messages from/to this user that are read
		$stmt->prepare("SELECT MessageID FROM Messages WHERE (SenderID=? AND ReceiverID=? AND ReadFlag=1) OR (ReceiverID=? AND SenderID=?)");
		$stmt->bind_param("iiii", $inUserID, $_SESSION['userid'], $inUserID, $_SESSION['userid']);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($messageid);
		
		//Add each resulting message to the array to return
		while($stmt->fetch())
		{
			$message = new message();
			$message->getFromDB($messageid);
			
			array_push($messages, $message);
		}
		
		$stmt->close();
		$dbhandle->close();
		
		return $messages;
	}
}

//Returns array of new messages sent from this userid.
//This is called by ajax for live updates instead of retrieving the entire convo.
function getUnreadMessages($inUserID)
{
	if (isset($_SESSION['userid']))
	{
		$messages = array();
		
		$messageid;
		
		$dbhandle = db_connect();
		$stmt = $dbhandle->stmt_init();
		
		//Get all messages from/to this user that are unread
		$stmt->prepare("SELECT MessageID FROM Messages WHERE SenderID=? AND ReceiverID=? WHERE ReadFlag = 1");
		$stmt->bind_param("ii", $inUserID, $_SESSION['userid']);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($messageid);
		
		//Add each resulting message to the array to return
		//Mark the message as read
		while($stmt->fetch())
		{
			$message = new message();
			$message->getFromDB($messageid);
			$message->markRead();
			
			array_push($messages, $message);
		}
		
		$stmt->close();
		$dbhandle->close();
		
		return $messages;
	}
}

?>