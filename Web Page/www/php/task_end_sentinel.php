<?php

//Is run by the server periodically to check if a task has ended.
$dbhandle = $db_connect();
$stmt = $dbhandle->stmt_init();

$stmt->prepare("SELECT TaskID, (? - EndDateTime) AS TimeRemaining FROM Tasks WHERE Active=1 AND TimeRemaining<0");
$stmt->bind_param("i", time());
$stmt->execute();

$stmt->store_result();
$stmt->bind_result($taskID, $tmptime);

while ($stmt->fetch())
{
	$task = new task();
	$task->getFromDB($taskID);
	$task->endTask();
	unset($task);
}

?>