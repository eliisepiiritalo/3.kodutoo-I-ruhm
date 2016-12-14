<?php

	

	//functions.php

	require("../../Config.php");

	//alustan sessiooni, et saaks kasutada

	//$_SESSSION muutujaid

	session_start();

	//********************

	//****** SIGNUP ******

	//********************

	//$name = "eliise";

	//var_dump($GLOBALS);

	$database = "if16_eliispiiri";

	function signup ($Name, $Age, $Email, $password, $Gender) {

		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

		$stmt = $mysqli->prepare("INSERT INTO user_sample (Name, Age, Email, password, Gender) VALUES (?,?,?,?,?)");

		echo $mysqli->error;

		$stmt->bind_param("sisss",$Name, $Age, $Email, $password, $Gender);

		if ($stmt->execute()) {

			echo "salvestamine õnnestus";

		} else {

			echo "ERROR ".$stmt->error;

		}

	}

	function login($Email, $password) {

		$error = "";

		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

		$stmt = $mysqli->prepare("

			SELECT id, Email, password, created

			FROM user_sample

			WHERE Email = ?

		");

		echo $mysqli->error;

		//asendan küsimärgi

		$stmt->bind_param("s", $Email);

		//määran tupladele muutujad

		$stmt->bind_result($id, $EmailFromDb, $passwordFromDb, $created);

		$stmt->execute();

		//küsin rea andmeid

		if($stmt->fetch()) {

			//oli rida

			// võrdlen paroole

			$hash = hash("sha512", $password);

			if($hash == $passwordFromDb) {

				echo "kasutaja ".$id." logis sisse";

				$_SESSION["userId"] = $id;

				$_SESSION["Email"] = $EmailFromDb;
				
				//$_SESSION["Name"] = $NameFromDB;

				//suunaks uuele lehele

				

				} else {

				$error = "parool vale";

			}

			} else {

			//ei olnud 

			$error = "sellise emailiga ".$Email." kasutajat ei olnud";

		}

		return $error;

		}

	function savePeople ($Gender, $Age, $Meal, $date) {

		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

		$stmt = $mysqli->prepare("INSERT INTO Calender (Gender, Age, Meal, date) VALUES (?,?,?,?)");

		$stmt->bind_param("siss", $Gender, $Age, $Meal, $date);

		if ($stmt->execute()) {

			echo "salvestamine õnnestus";

		} else {

			echo "ERROR ".$stmt->error;

		}

	}

	function getAllPeople () {

		$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"],$GLOBALS["serverPassword"],$GLOBALS["database"]);

		$stmt = $mysqli->prepare("

			SELECT id, Gender, Age, Meal, date

			FROM Calender

		");

		echo $mysqli->error;

		$stmt->bind_result($id, $Gender, $Age, $Meal, $date);

		$stmt->execute();

		// array("Eliise", "P")

		$result = array();

		// seni kuni on üks rida andmeid saada (10 rida = 10 korda)

		while ($stmt->fetch()) {

			$person = new StdClass();

			$person->id=$id;

			$person->Gender=$Gender;

			$person->Age=$Age;

			$person->Meal=$Meal;
			
			$person->date=$date;
			
			

			//echo $color."<br>";

			array_push($result, $person);

		}

		$stmt->close();

		$mysqli->close();

		return $result;

	}
	
	function cleanInput($input) {
		
		//input = "eliize95@tlu.ee  "
		
		$input = trim($input);
		
		//input = "eliize95@tlu.ee"
		
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		
		return $input;
	}
	
	/*function sum ($x, $y) {

		return $x + $y;

	}


	echo sum(5476567567,234234234);

	echo "<br>";

	$answer = sum(10,15);

	echo $answer;

	echo "<br>";

	function hello ($firstname, $lastname) {

		return "Tere tulemast ".$firstname." ".$lastname."!";
	echo hello("Eliise", "P.");
	}
	
	*/

?>
