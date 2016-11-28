<?php 
	session_start();
	$db = mysqli_connect("localhost", "root", "root", "Security424");//ivan
	//$db = mysqli_connect("localhost", "root", "", "424"); // Steven
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Home Page</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<div class="header">
			<h1>Home Page</h1>
		</div>
	</head>
	<body>
		<div class="mainContent">
			<div>
				<h2>Hi <?php echo $_SESSION['username'];?></h2>
				<?php	
					//session_start(); // Notice alert after login

					$delay=60; 
					header("Refresh: $delay;");
					if(isset($_SESSION["username"]))  
    				{  
			           if((time() - $_SESSION['last_login_timestamp']) > 59)  
			           {  
			                header("location:logout.php");
			                //echo "<meta http-equiv= 'refresh' content='0'>";
			           }  
			           else  
			           {  
			           		$username = $_SESSION['username'];
			           		$sql = "SELECT token FROM users WHERE username= '$username'";
			           		$result = $db->query($sql);
			           		$row = $result->fetch_assoc();
			           		$loginToken = $row['token'];

			           		//echo "<p>Token: ".$loginToken." </p>";
			           		echo "<p>You will be logged out in ".$delay. "seconds.</p>";  
					        if(isset($_POST['auth_btn'])){
								$postToken = $_POST['token'];
					           	$sql = "SELECT token FROM users WHERE username= '$username'";
					           	$result = $db->query($sql);
					           	$row = $result->fetch_assoc();
					           	$loginToken = $row['token'];

					           	//$_SESSION['username'] = $username;	
								if($postToken == $loginToken){
									$sql = "UPDATE users SET token=-1 WHERE username= '$username'";
									$db->query($sql);
									header("Refresh:0");
								}

							}
			           		else if($loginToken == -1){ //registered flag
								$sql = "SELECT loginCount FROM users WHERE username= '$username'";
								$result = $db->query($sql);
								$row = $result->fetch_assoc();
								echo "<p>You have logged in: ".$row['loginCount']." times.</p>"; 
							}
							else {
								$_SESSION['username'] = $username;
								echo "<p>You are not have not been authenticated.</p>";
								echo "<p>Check your email for a authentication token.</p>";
								echo "
								<form method='post' action='home.php'>
									<table>				
										<tr>
											<td>Token:</td>
											<td><input type='token' name='token' class='textInput'></td>
										</tr>
										<tr>
											<td></td>
											<td><input type='submit' name='auth_btn' value='authenticate'></td>
										</tr>
									</table>
								</form>
								";
							}
           				}  
     				}  
			      	else  
			      	{  
			           header('location:login.php');  
			      	}

					//echo "<p>You have logged in: times.".$testing."</p>";
				?>
			</div>
			<div>
				<a href="logout.php">Logout</a>
			</div>
	</body>
</html>
