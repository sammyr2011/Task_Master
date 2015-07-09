<?php

/**
This script is called from a Github WebHook whenever there is a push to the repo.

This causes the server to pull from the repo and update the website.
**/

$REPO_PULL_LOC = "/var/www/gitpull2";
$WEB_FOLDER_NAME = "Web\ Page/www";
$COPY_LOC = "/var/www/html";
$REPO = "https://github.com/sammyr2011/Task_Master.git";

if ( isset($_POST['payload']) )
{
	//Pull from repo
	shell_exec("cd {$REPO_PULL_LOC} && git pull");
	
	//Copy web folder contents to actual web folder on server
	shell_exec("cd {$REPO_PULL_LOC}/{$WEB_FOLDER_NAME} && rsync -r . {$COPY_LOC}");

	exit("Received payload and updated server");
}
else
	exit("Rejected; no payload");

?>
