<?php
	session_start();
	
	require_once("config.php");
	if(isset($_GET['code'])){
		
		$token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
		$_SESSION['access_token'] = $token;
		
		$oAuth = new Google_Service_Oauth2($gClient);
		$userData = $oAuth->userinfo_v2_me->get();  
		$_SESSION['email'] = $userData['email'];
		$_SESSION['userid'] = "user_".$userData['id']; 
		$_SESSION['username'] = $userData['givenName']; 
		$_SESSION['displayname'] = $userData['givenName']; 
	}
	
	if(isset($_GET['cid'])){
		
		//update number of users in chatroom
		
			$doc = new DOMDocument("1.0","utf-8");
			$doc->preserveWhiteSpace = false;
			$doc->formatOutput = true; 
			//load XML
			$doc->load("chatroom.xml");
			//reffered notes of Joanna Kommala &  https://www.php.net/manual/en/class.domxpath.php for xpath
			$xpath = new DOMXpath($doc);
			$numberofusers = $xpath->query("//chatRoom[@id='".$_GET['cid']."']/chatRoomUsers");
			$currentusers = ($numberofusers[0]->childNodes[0]->nodeValue);
			$numberofusers[0]->childNodes[0]->nodeValue = ($currentusers-1);
			
			$doc->save("chatroom.xml");
				
			//refereed to remove a specific parameter from url https://www.dreamincode.net/forums/topic/259491-remove-a-specific-get-variable-from-url/
			$parameterToRemove = array("cid");
			$finalurl = "?";
			foreach($_GET as $index => $get)
			{
				if(!in_array($index, $parameterToRemove))
				{
					$finalurl .= $index.'='.$get.'&';
				}
			}
			header('location: ' . $finalurl);

	}
	
	if(isset($_SESSION["username"]))
	{
		$doc = new DOMDocument("1.0","utf-8");
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true; 
		//load XML
		$doc->load("chatroom.xml");
		$chatrooms = $doc->getElementsByTagName("chatRoom");

		
		if(isset($_POST["submitBtn"]))
		{
			$crn = $_POST["chatRoomName"];
			$crd = $_POST["chatRoomDescription"];
			
			$rootElement = $doc->getElementsByTagName("chatRooms")[0];
			
			//Create new item element
			$chatroomElement = $doc->createElement("chatRoom");
			$chatroomName = $doc->createElement("chatRoomName",$crn);
			$description = $doc->createElement("chatRoomDescription",$crd);
			$users = $doc->createElement("chatRoomUsers",0);
			$createdon = $doc->createElement("chatRoomCreatedOn",time());

			//create the id attribute
			$attr = $doc->createAttribute("id");
			$attr->value = 'chatroom_'.time();
			
			//Appending newly created elements to channel 
			$chatroomElement->appendChild($attr);
			$chatroomElement->appendChild($chatroomName);
			$chatroomElement->appendChild($description);
			$chatroomElement->appendChild($users);
			$chatroomElement->appendChild($createdon);
			
			$rootElement->appendChild($chatroomElement);
			//Saving changes
			$doc->save("chatroom.xml");

			//Reload page				
			header("location:chatrooms.php"); 
		}
	}
	else
	{
		header("location:index.php");
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Lab5 Assignment XML</title>
		<meta name="description" content="Assignment 2 - Amandeep Singh">
		<link rel="stylesheet" href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<main>
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<?php  
						if(isset($_SESSION["username"]))
							{
								echo "<h2>Welcome ".$_SESSION["displayname"]."</h2>";
							}
					?>
				</div>	
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<nav id="main-menu">
						<h3 class="hidden">Main navigation</h3>
						<ul class="menu">
							<li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a>
						</ul>
					</nav>
				</div>
			</div>
			<hr/>
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" id="addchatroomarea">
					<h4 id="form-title-text">Add a new Chat Room</h4>
					<form method="post" action="" id="addChatRoom">
						<div class="row">	
							<div class="col">
								<div>
									<input type="text" class="form-control addchatcontrol" id="chatRoomName" name="chatRoomName" placeholder="Enter chat room name" required>
								</div>
								<div>
									<input type="text" class="form-control addchatcontrol" id="chatRoomDescription" name="chatRoomDescription" placeholder="Enter chat room description" required>
								</div>
								<div id="btnSubmit">
									<input type='Submit' class="btn btn-primary addchatcontrol" name="submitBtn" id="submitBtn" value="Create new Chat Room">
								</div>
							</div>
						</div>
					</form>	
				</div>
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<div class="row">
						<?php 

							$str="";
							$n=0;
							foreach($chatrooms as $chatroom)
							{
								//echo $chatroom->getAttribute('id');
								$n++;
								$str.="<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12'>
											<div class='chatroomdetails'>
												<div class='crname' style='color:white;background-color:black'>
													".$chatroom->childNodes[0]->nodeValue."<a href='#'><span id='deletechatroom'>x</span></a>
												</div>
												<a href='chatwindow.php?cid=".$chatroom->getAttribute('id')."&is_first=1'>
												<div class='crusers' style='color:#78281F'>
													Number of Users:".$chatroom->childNodes[2]->nodeValue."
												</div>
												<div class='crdesc' style='color:#2E4053'>
													Description: ".substr($chatroom->childNodes[1]->nodeValue,0,150)."...
												</div>
												</a>
											</div>
										</div>";
								
								/* $str.="<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12'>
											<a href='chatwindow.php?cid=".$chatroom->getAttribute('id')."&is_first=1'>
											<div class='chatroomdetails'>
												<div class='crname' style='color:white;background-color:black'>
													".$chatroom->childNodes[0]->nodeValue."<span id='deletechatroom'>x</span>
												</div>
												<div class='crusers' style='color:#78281F'>
													Number of Users:".$chatroom->childNodes[2]->nodeValue."
												</div>
												<div class='crdesc' style='color:#2E4053'>
													Description: ".substr($chatroom->childNodes[1]->nodeValue,0,150)."...
												</div>
											</div>
											</a>
										</div>"; */
							}
							echo $str;
						?>
					</div>
				</div>
			</div>
			
			<div class="row">
			
			</div>
		</main>
	</body>	
</html>                                                  