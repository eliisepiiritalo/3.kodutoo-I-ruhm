<?php
	
	//võtab ja kopereerib faili sisu
	require("../functions.php");
	require ("../class/User.class.php");
	$User=new User($mysqli);
	
	require("../class/Helper.class.php");
	$Helper=new Helper ($mysqli);
	
	
	//kas kasutaja on sisse logitud
	if(isset ($_SESSION["userId"])) {
		
			header("Location: data.php");
			
	}

	
	
	//var_dump(5.5);
	//var_dump($_GET);
	//echo "<br>";
	//var_dump($_POST);
	
	//MUUTUJAD
	$signupUsernameError = "";	
	$signupEmailError = "";
	$signupPasswordError = "";
	$loginEmailError = "";
	$genderError = "";
	$firstNameError = "";
	$lastNameError = "";
	
	$loginEmail = "";
	$signupEmail = "";
	$signupUsername ="";
	$gender = "";
	$firstName = "";
	$lastName = "";
	

	if ( isset ( $_POST["loginEmail"] ) ) {
		
		if ( empty ( $_POST["loginEmail"] ) ) {
			
			$loginEmailError = "See väli on kohustuslik!";
		} else {
			$loginEmail = $_POST["loginEmail"]; //jätab e-maili meelde, kui parool on vale
			
		}
		
	}
	
	if ( isset ( $_POST["signupUsername"] ) ) {
		
		if ( empty ( $_POST["signupUsername"] ) ) {
			$signupUsernameError = "See väli on kohustuslik!";
			
		} else {
			$signupUsername = $_POST["signupUsername"];
		}

	}

	if (isset ($_POST["signupPassword"]) ) {

	

		if (empty ($_POST["signupPassword"]) ) { 

			$signupPasswordError = "See väli on kohustuslik!";

		

		} else {

			

			if (strlen ($_POST["signupPassword"]) <= 8 ){

				$signupPasswordError = "Parool peab olema 8 tähemärki pikk!";

			}

		} 

		

	}

	if (isset ($_POST["firstName"]) ){

		if (empty ($_POST["firstName"]) ){

			$firstNameError = "See väli on kohustuslik!";		

		} else {

			//The preg_match() function searches a string for pattern, returning true if the pattern exists, and false otherwise.

			if (!preg_match("/^[a-zA-Z õäöüšž-]*$/",$_POST["firstName"])) { 

				$firstNameError = "Pole nimi!"; 

			}

		}

	}


	$gender = "";

	if (isset ($_POST["gender"]) ) {

	

		if (empty ($_POST["gender"]) ) { 

			$genderError = "See väli on kohustuslik!";

		} else {

			$gender = $_POST["gender"];

		}

	}

	

	if (isset ($_POST["signupEmail"]) ) {

	

		if (empty ($_POST["signupEmail"]) ) { 

			$signupEmailError = "See väli on kohustuslik!";

		} else {

			$signupEmail = $_POST["signupEmail"];

		}

	}

	

	// Kus tean, et ühtegi viga ei olnud ja saan kasutaja andmed salvestada

	if (isset($_POST["signupPassword"]) &&

		isset($_POST["signupEmail"]) &&

		isset($_POST["signupUsername"]) &&

		isset($_POST["gender"]) &&
		
		isset($_POST["firstName"]) &&
		
		isset($_POST["lastName"]) &&

		empty($signupEmailError) && 

		empty($signupUsernameError) && 

		empty($genderError) && 

		empty($signupPasswordError) &&
		
		empty($firstNameError) &&
		
		empty($lastNameError)

		) {

			//näitab, millised andmed kasutaja sisestas: email, parool, räsi

		echo "Salvestan...<br>";

		echo "kasutajatunnus ".$signupUsername."<br>";

		echo "email ".$signupEmail."<br>";

		

		$password = hash("sha512", $_POST["signupPassword"]);

		

		echo "parool ".$_POST["signupPassword"]."<br>";

		echo "räsi ".$password."<br>";

		echo "sugu ".$gender."<br>";

		

		//echo $serverUsername; 
		
		$signupEmail = cleanInput($signupEmail);
		$password = cleanInput($password);

		

		$User->signup($signupEmail, $Helper->cleanInput($password), $signupUsername, $gender);



		}

		

		$error = "";

		//kontrollin, et kasutaja täitis välja ja võib sisse logida

		if(isset($_POST["loginEmail"]) &&

			isset($_POST["loginPassword"]) &&

			!empty($_POST["loginEmail"]) &&

			!empty($_POST["loginPassword"])

		) {

			//login sisse

			$error = $User->login($Helper->cleanInput($_POST["loginEmail"]), $Helper->cleanInput($_POST["loginPassword"]));

			
		}
		

		

?>
	
<!DOCTYPE html>
<html>
	<head>
		<title>Sisselogimise lehekülg</title>
	</head>
	<body>

		<h1>Logi sisse</h1>
		
		<form method="POST">
			<p style="color:red;"><?=$error;?></p>
			
			<input name="loginEmail" type="email" value="<?=$loginEmail;?>" placeholder="E-mail/Kasutajatunnus"> <?php echo $loginEmail; ?>
			
			<br><br>
			
			<input name="loginPassword" type="password" placeholder="Parool">
			
			<br><br>
			
			<input type="submit" value="Logi sisse">
			
		</form>
		<h1>Loo kasutaja</h1>
		
		<form method="POST">
		
			<input name="signupUsername" type="username" placeholder="Kasutajatunnus"> <?php echo $signupUsernameError;?>
			
			<br><br>
			
			<label>Eesnimi</label><br>
			<input name="firstName" type="text"> <?php echo $firstNameError; ?>
			<br><br>
			
			<label>Perekonnanimi</label><br>
			<input name="lastName" type="text"> <?php echo $lastNameError; ?>
			<br><br>
			
			<label>Sugu</label><br>
			
			<?php if($gender == "male") { ?>
				<input type="radio" name="gender" value="male" checked> Male<br>
			<?php } else{ ?>
					<input type="radio" name="gender" value="male" > Male<br>
			<?php } ?>
			
			<?php if($gender == "female") { ?>
					<input type="radio" name="gender" value="female" checked> Female<br>
			<?php } else { ?>
					<input type="radio" name="gender" value="female" > Female<br>
			<?php } ?>
			
			
			<br><br>

			<input name="signupEmail" type="email" value="<?=$signupEmail;?>" placeholder="E-maili aadress"> <?php echo $signupEmailError; ?>
			
			<br><br>
			
			<input type="submit" value="Loo kasutaja">
			
		</form>
		
	</body>
</html>