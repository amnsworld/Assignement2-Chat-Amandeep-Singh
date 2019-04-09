<?php
    session_start();
	require_once("config.php");

	$loginURL = $gClient->createAuthUrl();
	
	if(isset($_POST["submitBtn"]))
	{
		$username = $_POST["userName"];
		$displayname = $_POST["userDisplayName"];
		$password=$_POST["userPassword"];
		if($username ==='' || $password === '' || $displayname === '')
		{
			//error 2: Fields empty
			header("location:index.php?e=1");
		}

		
		$flag= 0;
		
		$doc = new DOMDocument("1.0","utf-8");
		$doc->preserveWhiteSpace = false;
		$doc->formatOutput = true; 
		//load XML
		$doc->load("users.xml");
		$xpath = new DOMXpath($doc);
		$user = $xpath->query("//user[name/text()='".$username."']");
		//print_r($user);
		
		if (!is_null($user)) {
			foreach ($user as $u) {
				$uname = $u->childNodes[1]->nodeValue;
				$pwd =  $u->childNodes[3]->nodeValue;
				if($uname == $username)
				{
					$flag=1;
		
					if(password_verify($password,$pwd))
					{

						$_SESSION["username"] = $username;
						$_SESSION["displayname"] = $displayname;			
						$_SESSION["userid"] = $u->childNodes[0]->nodeValue;			

						header("location:chatrooms.php");
					}
					else
					{
						echo "error";
						//error 2: Password not matched
						//header("location:index.php?e=2");
					}
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
			$userPassword = $doc->createElement("password", password_hash($password,PASSWORD_BCRYPT));
			
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
			if(isset($_SESSION['access_token']))
			{
				unset($_SESSION['access_token']);
				$gClient->revokeToken();
				
			}
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
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	</head>
	<body>
		<main>
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				
				</div>
				<div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
				<div id="formContent">
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
								<div class="input-group mb-2">
									<div class="input-group-prepend">
										<div class="input-group-text">Password</div>
									</div>
									<input type="password" id="userPassword" class="form-control" name="userPassword" placeholder="Enter Password" required>
								</div>
							</div>
							<div id="loginButton">
								<div id="btnSubmit">
									<button type='Submit'  class="btn btn-primary"  name="submitBtn" id="submitBtn" >Login <i class="fas fa-sign-in-alt"></i></button>
									
								</div>
								<div id="btnGoogleSubmit">
									<button type='Submit'  class="btn btn-primary"  name="googlesubmitBtn" onclick="window.location='<?php echo $loginURL ?>'" id="googlesubmitBtn" >Login with <i class="fab fa-google-plus-g"></i></button>
								</div>
								
							</div>
							<div>
								<?php 
									if(isset($_GET["e"]))
									{
										if($_GET["e"]==1)
										{
											echo '<div class="alert alert-danger" role="alert"> Enter Required Fields !!!</div>';
										}else if($_GET["e"]==2)
										{
											echo '<div class="alert alert-danger" role="alert"> Invalid Username & Password!!!</div>';
										}
									}
									
								?>
							</div>
						</form>			
					</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
				
				</div>
			</div>			
		</main>
	</body>	
</html>                                                  