<?php
    session_start();
	
	if(isset($_POST["submitBtn"]))
	{
		$username = $_POST["userName"];
		$displayname = $_POST["userDisplayName"];

		$flag= 0;
		
		$doc = new DOMDocument("1.0","utf-8");
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true; 
		//load XML
		$doc->load("users.xml");
		$xpath = new DOMXpath($doc);
		$user = $xpath->query("//user[name/text()='".$username."']");
		print_r($user);
		
		if (!is_null($user)) {
			foreach ($user as $u) {
				$uname = $u->childNodes[1]->nodeValue;
				if($uname == $username)
				{
					$flag=1;

					$_SESSION["username"] = $username;
					$_SESSION["displayname"] = $displayname;			
					$_SESSION["userid"] = $u->childNodes[0]->nodeValue;			

					header("location:chatrooms.php");
				} 
			}
		}
		
		if($flag == 0)
		{
			$doc = new DOMDocument("1.0","utf-8");
			$doc->preserveWhiteSpace = false;
			$doc->formatOutput = true; 
			//load XML
			$doc->load("users.xml");
			
			//get root element
			$rootElement = $doc->getElementsByTagName("users")[0];
			
			//Create new item element
			$userElement = $doc->createElement("user");
			$userid = $doc->createElement("userid","user_".time());
			$userName = $doc->createElement("name",$username);
			$userdisplayname = $doc->createElement("displayname",$displayname);
			$userPassword = $doc->createElement("password",$username);
			
			//Appending newly created elements to channel 
			$userElement->appendChild($userid);
			$userElement->appendChild($userName);
			$userElement->appendChild($userdisplayname);
			$userElement->appendChild($userPassword);
			
			$rootElement->appendChild($userElement);

			//Saving changes
			$doc->save("users.xml");
			
			$_SESSION["username"] = $username;
			$_SESSION["displayname"] = $displayname;			
			$_SESSION["userid"] = "user_".time();			
			
			header("location:chatrooms.php");
		} 
	}
	
	if(isset($_GET["logout"]))
	{
		$logout= $_GET["logout"];
		if( $logout == 1 )
		{
			// destroy session values
			session_destroy();
			//Reload page
			header("location:index.php");
		}
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
				
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				
					<!-- logo image reffered from https://www.freepik.com/free-vector/chat-bubble_2900821.htm -->
					<div id="chatlogo">
						<img src="images/chatlogo.jpg" alt="Chat"/>
					</div>
					<div>			
						<h2 id="form-title-text">Welcome to Chat World</h2>
						<form method="post" action="" >
							<div>
								<!--
								<label class="form-label-text">User Name </label>
								<input type="text" id="userName" class="form-control" name="userName" placeholder="Enter User Name" required>
								-->
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text">Username</div>
									</div>
									<input type="text" id="userName" class="form-control" name="userName" placeholder="Enter User Name" required>
								</div>
							</div>
							<div>
								<!--
								<label class="form-label-text">Display Name </label>
								<input type="text" id="userDisplayName" class="form-control" name="userDisplayName" placeholder="Enter User Display Name" required>
								-->
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text">Display Name</div>
									</div>
									<input type="text" id="userDisplayName" class="form-control" name="userDisplayName" placeholder="Enter User Display Name" required>
								</div>
							</div>
							<div id="btnSubmit">
								<input type='Submit'  class="btn btn-primary"  name="submitBtn" id="submitBtn" value="Enter to Join">
							</div>
						</form>			
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				
				</div>
			</div>			
		</main>
	</body>	
</html>                                                  