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
			$rootElement = $doc->getElementsByTagName("chatRooms")[0];
					//Create new item element
			$chatroomElement = $doc->createElement("chatRoom");
			$chatroomName = $doc->createElement("chatRoomName",$_POST["roomName"]);
			$description = $doc->createElement("chatRoomDescription",$_POST["roomDescription"]);
			$users = $doc->createElement("chatRoomUsers",0);
			$createdon = $doc->createElement("chatRoomCreatedOn",time());

			//create the id attribute
			$attr = $doc->createAttribute("id");
			$attr->value = time();
			
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
		<!-- <link rel="stylesheet" href="css/style.css">  -->
	</head>
	<body>
		<main>
		
			<!-- <div class="row">	
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="col-auto">
						<h2 id="form-title-text">Add a new Chat Room</h2>
						<form method="post" action="" id="addChatRoom">
							<div>
								<label class="form-label-text">Name </label>
								<input type="text" id="chatRoomName" name="chatRoomName" placeholder="Enter chat room name" required>
							</div>
							<div>
								<label class="form-label-text">Description </label>
								<input type="text" id="chatRoomDescription" name="chatRoomDescription" placeholder="Enter chat room description" required>
							</div>
							<div>
								<input type='Submit' name="submitBtn" id="submitBtn" value="Create">
							</div>
						</form>
					</div>						
				</div>						
			</div> -->
			
			<div>
				<h2 id="form-title-text">Add a new Chat Room</h2>
				<form method="post" action="" id="addChatRoom">
					<div class="row">	
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="col-auto">
								<div>
									<label class="input-group-text">Name </label>
									<input type="text" class="form-control" id="chatRoomName" name="chatRoomName" placeholder="Enter chat room name" required>
								</div>
							</div>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
							<div class="col-auto">
								<div class="input-group mb-2">
								<div class="input-group-prepend">
									<label class="input-group-text">Description </label>
								</div>
									<input type="text" class="form-control" id="chatRoomDescription" name="chatRoomDescription" placeholder="Enter chat room description" required>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
							<input type='Submit' class="btn btn-primary" name="submitBtn" id="submitBtn" value="Create new Chat Room">
						</div>
					</div>
				</form>					
			</div>
			<hr/>
			<div class="row">
			<?php 

				$str="";
				$n=0;
				foreach($chatrooms as $chatroom)
				{
					$n++;
					/* $colorIndex=rand(0,9);
					if($n==4)
					{
						$n=0;
						$str."</div><div class='row'>";
					} */
					/* $str.="<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
							<div class='chatroomBlock' style='border:5px solid ".$colorArray[$colorIndex]."'>
								<div class='chatroomName' style='color:white;background-color:".$colorArray[$colorIndex]."'><i class='fas fa-comments'></i>".$chatroom->childNodes[1]->nodeValue."
							</div>
							<div class='chatroomParticipants' style='color:".$colorArray[$colorIndex]."'><i class='fas fa-users'></i>".$chatroom->childNodes[3]->nodeValue."
							</div>
							<div class='chatroomDescription' style='color:".$colorArray[$colorIndex]."'>".substr($chatroom->childNodes[2]->nodeValue,0,150)."...</div>
							<div class='chatroomLastUpdate'><i class='far fa-clock'></i><em>Last Activity: ".gmdate("d-M-Y h:i:s",$chatroom->childNodes[4]->nodeValue)."</em></div>
						</div>
					</div>"; */
					
					/* $str.="<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
								<div>
									<fieldset>
										<legend>".$chatroom->childNodes[1]->nodeValue."</legend>
										Number of Users: ".$chatroom->childNodes[2]->nodeValue."<br>
										Description: ".$chatroom->childNodes[3]->nodeValue."
									</fieldset>
								</div>
							</div>"; */
					
					$str.="<div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
								<div style='border:5px solid'>
									<div id='crname' style='color:white;background-color:black'>
										".$chatroom->childNodes[0]->nodeValue."
									</div>
									<div id='crusers' style='color:#78281F'>
										Number of Users:".$chatroom->childNodes[2]->nodeValue."
									</div>
									<div id='crdesc' style='color:#2E4053'>
										Description: ".$chatroom->childNodes[1]->nodeValue."
									</div>
								</div>
							</div>";
				}
				
				echo $str;
			?>		
			</div>
		</main>
	</body>	
</html>                                                  