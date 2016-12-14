<?php

class User{
	
		private $connection;
	
		function __construct($mysqli){
		
			$this->connection=$mysqli;
	}
	
		function signup ($Name, $Age, $Email, $password, $Gender) {
			

			$stmt = $this->connection->prepare("INSERT INTO user_sample (Name, Age, Email, password, Gender) VALUES (?,?,?,?,?)");

			echo $this->connection->error;

			$stmt->bind_param("sisss",$Name, $Age, $Email, $password, $Gender);

			if ($stmt->execute()) {

				echo "salvestamine õnnestus";

		} else {

			echo "ERROR ".$stmt->error;

		}

	}

		function login($Email, $password) {

			$error = "";

			$stmt = $this->connection->prepare("

				SELECT id, Email, password, created

				FROM user_sample

				WHERE Email = ?

		");

			echo $this->connection->error;

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

					header("Location: data.php");
					exit();

				} else {

						$error = "parool vale";

				}
	
			} else {

					//ei olnud 

					$error = "sellise emailiga ".$Email." kasutajat ei olnud";

			}

			return $error;

		}
		
	}
	?>