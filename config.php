<?php
	require_once('googleapi/vendor/autoload.php');
	
	$gClient = new Google_Client();
	$gClient->setClientId("619041947835-2rue07seus0s55oe50nsp2gnjohapfar.apps.googleusercontent.com");
	$gClient->setClientSecret("bnFKsTwXeSUs0AzP3G9iudzw");
	$gClient->setApplicationName("XML Assignement 3");
	$gClient->setRedirectUri("http://localhost/XMLAssign/assignment2/chatrooms.php");
	
	$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
?>