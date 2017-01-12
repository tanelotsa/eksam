<?php
	
	require("../functions.php");

	require("../class/Helper.class.php");
	$Helper = new Helper($mysqli);

	require("../class/User.class.php");
	$User = new User($mysqli);

	//kui on sisseloginud, suunan data lehele
	if (isset($_SESSION["userId"])) {
		header("Location: data.php");	
		exit();
	}
	
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	$signupEmailError = "";
	$signupEmail = "";
	$loginEmail = "";
	$loginEmailError = "";
	
	if (isset ($_POST["loginEmail"])) {
		
		
		if(empty($_POST["loginEmail"])) {
			
			$loginEmailError = "Sisesta E-Post !";
		
		} else {
			$loginEmail = $_POST["loginEmail"];
		}
	
	}
	
	
	$loginPasswordError = "";
	
	if (isset ($_POST["loginPassword"])) {
		
		
		if(empty($_POST["loginPassword"])) {
			
			$loginPasswordError = "Sisesta Parool !";
		
		} 
	
	}
		
	
	//kas keegi vajutas nuppu 
	
	if (isset ($_POST["signupEmail"])) {
		
		//on olemas
		//kas email on olemas
		if(empty($_POST["signupEmail"])) {
			
			//on tühi
			$signupEmailError = "Väli on kohustuslik!";
		
		} else {
			$signupEmail = $_POST["signupEmail"];
		}
	
	}
	
	//kas epost on tühi
	
	$signupPasswordError = "";
	
	if (isset ($_POST["signupPassword"])) {
		
		if(empty($_POST["signupPassword"])) {
			
			$signupPasswordError = "Väli on kohustuslik!";
			
		} else { 
		
			if (strlen($_POST["signupPassword"]) < 8 ) {
				
				$signupPasswordError = "* Parool peab olema vähemalt kaheksa märki!";
				}
		
			}	
		
	}
	

	

	
	if ( $signupEmailError == "" &&
		 $signupPasswordError == "" &&

		 
		 isset($_POST["signupEmail"]) &&
		 isset($_POST["signupPassword"])

		) {
	
		//kõik olemas, vigu polnud
		echo "SALVESTAN...<br>";
		echo "email ".$signupEmail."<br>";
		echo "parool ".$_POST["signupPassword"]."<br>";
		
		$password = hash("sha512",$_POST["signupPassword"]);
	
		//echo $password ;

		$User->signup($Helper->cleanInput($signupEmail), $password);
		
		
		}
		
		
		
		$notice = "";
		//kas kasutaja tahab sisse logida
		if ( isset($_POST["loginEmail"]) &&
			 isset($_POST["loginPassword"]) &&
			 !empty($_POST["loginEmail"]) &&
			 !empty($_POST["loginPassword"]) 
		
		) {
			$notice = $User->login($Helper->cleanInput($_POST["loginEmail"]), $Helper->cleanInput($_POST["loginPassword"]));
		
		} 
			 
			 
			 
?>






		<h1>Logi Sisse</h1>
			<p> <?=$notice;?> </p>
				<form method="POST">

				<label>E-Post:</label>


				<input class="form-control" name="loginEmail" type = "email" value="<?=$loginEmail;?>"> <?php echo $loginEmailError ; ?>


				<label>Parool:</label>


				<input class="form-control" name="loginPassword" type = "password" > <?php echo $loginPasswordError ; ?>

				<input class="btn btn-success btn-sm btn-block visible-xs-block" type = "submit" value = "LOGI SISSE" >

				</form>


			<h1>Loo kasutaja</h1>
			<form method="POST">

				<label>E-Post:</label>

				<input name="signupEmail" type = "email" value="<?=$signupEmail;?>"> <?php echo $signupEmailError ; ?>


				<label>Parool:</label>

				<input name="signupPassword" type = "password" > <?php echo $signupPasswordError; ?>


				<input class="btn btn-primary btn-sm btn-block visible-xs-block" type = "submit" value = "LOO KASUTAJA" >



			</form>

