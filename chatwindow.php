<?php 
	session_start();
	//echo $_GET["cid"];
	
	if(isset($_GET["cid"]))
	{
		$chatroomid = $_GET["cid"];
		$doc = new DOMDocument("1.0","utf-8");
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true; 
		//load XML
		$doc->load("chat.xml");
		$chats = $doc->getElementsByTagName("chat");
		
		//reffered notes of Joanna Kommala &  https://www.php.net/manual/en/class.domxpath.php for xpath
		$xpath = new DOMXpath($doc);
		$messages = $xpath->query("//chat[@chatid='".$chatroomid."']/messages");
		//print_r($elements);
	}
	
	if(isset($_POST["messagesubmitBtn"]))
		{
			$usrmsg = $_POST["usermessage"];
			
			$xpath = new DOMXpath($doc);			
			$rootElement = $xpath->query("//chat[@chatid='".$chatroomid."']");
			
			
			//Create new item element
			$messages = $doc->createElement("messages");
			
			$username = $doc->createElement("username",$_SESSION["username"]);
			$usermessage = $doc->createElement("message",$usrmsg);
			$chatdatetime = $doc->createElement("chatDateTime",time());
			
			//create the userid attribute
			$usrnameattr = $doc->createAttribute("userid");
			$usrnameattr->value = $_SESSION["userid"];
			
			// Appending attribute to username
			$username->appendChild($usrnameattr);
			
			//Appending newly created elements to messages
			$messages->appendChild($username);
			$messages->appendChild($usermessage);
			$messages->appendChild($chatdatetime);
			
			$rootElement[0]->appendChild($messages);
			//Saving changes
			$doc->save("chat.xml");

			//Reload page				
			header("location:chatwindow.php?cid=".$_GET["cid"]); 
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
							<li><a href="chatrooms.php">Dashboard</a></li>
							<li><a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a>
						</ul>
					</nav>
				</div>
			</div>
			<hr/>
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">	
				
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div id="chatwindow">
						<?php
							$str="";
							$n=0;
							foreach($messages as $msg)
							{				
								$str.="<div>
												".$msg->childNodes[0]->nodeValue." : ".$msg->childNodes[1]->nodeValue."
											</div>";
							}
							
							echo $str;
						?>
					</div>
					<div id="usermessagearea">
						<form method="post" action="#">
							<div class="row">	
								<div class="col">
									<div>
										<input type="text" class="form-control addchatcontrol" id="usermessage" name="usermessage" placeholder="Enter Message" required>
									</div>
									<div id="btnSubmit">
										<input type='Submit' class="btn btn-primary addchatcontrol" name="messagesubmitBtn" id="messagesubmitBtn" value="Send">
									</div>
									<div class="clear">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">	
				
				</div>
			</div>
		</main>
		
		<script>
			window.onload = function(){
				var element= document.getElementById("chatwindow");
				element.scrollTop = element.scrollHeight;
			}
		</script>
	</body>	
</html>     