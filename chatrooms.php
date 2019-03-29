<?php
	session_start();
	
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
			header("location:chatrooms1.php"); 
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
				<div class="col-lg-7 col-md-5 col-sm-12 col-xs-12">
				</div>
				<div class="col-lg-1 col-md-3 col-sm-12 col-xs-12">
					<a href="index.php?logout=1">Logout</a>
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
											<a href='chatwindow.php?cid=".$chatroom->getAttribute('id')."'>
											<div class='chatroomdetails'>
												<div class='crname' style='color:white;background-color:black'>
													".$chatroom->childNodes[0]->nodeValue."
												</div>
												<div class='crusers' style='color:#78281F'>
													Number of Users:".$chatroom->childNodes[2]->nodeValue."
												</div>
												<div class='crdesc' style='color:#2E4053'>
													Description: ".substr($chatroom->childNodes[1]->nodeValue,0,150)."...
												</div>
											</div>
											</a>
										</div>";
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