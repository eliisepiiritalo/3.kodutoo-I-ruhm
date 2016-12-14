<?php
class Meal{
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection=$mysqli;
	}
	function delete($id){
		$stmt=$this->connection->prepare("UPDATE Calender SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $id);
		
		//kas õnnestus salvestada
		if($stmt->execute()){
			//õnnestus
			echo "Kustutamine õnnestus!";
		}
		$stmt->close();
	}
	
	function update($id, $Gender, $Age, $MealClass, $date){
		
		
		$stmt = $this->connection->prepare("UPDATE Calender SET Gender=?, Age=?, Meal=?, date=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("sissi",$Gender, $Age, $MealClass, $date, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "Salvestus õnnestus!";
		}
		
		$stmt->close();
	}
	
	function get($q, $sort, $order){
			$allowedSort=["id", "Gender", "Age", "Meal", "date"];
				
			if(!in_array($sort, $allowedSort)){
				$sort="id";
			}
			$orderBy="ASC";
			if($order=="DESC"){
				$orderBy="DESC";
			}
			//echo "Sorteerin: ".$sort." ".$orderBy."";
				
		//kas otsib
		if($q!=""){
			
			$stmt=$this->connection->prepare("
			SELECT id, Gender, Age, Meal, date
			FROM Calender
			WHERE deleted IS NULL
			AND (Gender LIKE ? OR Age LIKE ? OR Meal LIKE ? OR date LIKE ?)
			ORDER BY $sort $orderBy
			
			");
			$searchWord="%".$q."%";
			$stmt->bind_param("sssss", $searchWord, $searchWord, $searchWord, $searchWord, $searchWord);
			
		}else{
			$stmt=$this->connection->prepare("
				SELECT id, Gender, Age, MealClass, date
				FROM Calender
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
			
			");
			
		}
	
		echo $this->connection->error;
		$stmt->bind_result($id, $Gender, $Age, $MealClass, $date);
		$stmt->execute();
		
				//tekitan massiivi
		$result=array();
			//seni kuni on üks rida andmeid saada (10 rida=10 korda)
			while($stmt->fetch()) {
				$person=new StdClass();
				$person->id=$id;
				$person->Gender=$Gender;
				$person->Age=$Age;
				$person->Meal=$MealClass;
				$person->date=$date;
				
				//echo $Color."<br>";
				array_push($result, $person);
			}
			$stmt->close();
			
			return $result;
		
	
	}
	function getSingle($edit_id){
		$stmt = $this->connection->prepare("SELECT Gender, Age, Meal, date FROM Calender WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($Gender, $Age, $MealClass, $date);
		$stmt->execute();
		
		//tekitan objekti
		$Meal = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$Meal->Gender = $Gender;
			$Meal->Age = $Age;
			$Meal->MealClass = $MealClass;
			$Meal->date = $date;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		
		
		return $Meal;
		
	}
	 
	function savePeople ($Gender, $Age, $MealClass, $date){
			
			//käsk
			$stmt=$this->connection->prepare("INSERT INTO Calender (Gender, Age, MealClass, date) VALUES(?,?,?,?)");
			
			$stmt->bind_param("siss",$Gender, $Age, $MealClass, $date);
			
			if($stmt->execute()) {
				echo "Salvestamine õnnestus";
				
			} else {
					echo "ERROR ".$stmt->error;
			
			}
			$stmt->close();	
	}
	
	function getAllPeople () {
			
			//käsk
			$stmt=$this->connection->prepare("
				SELECT id, Gender, Age, MealClass, date
				FROM Calender
			");
			echo $this->connection->error;
			$stmt->bind_result($id, $Gender, $Age, $MealClass, $date);
			$stmt->execute();
			
			//array("Eliise", "P")
			$result=array();
			//seni kuni on üks rida andmeid saada (10 rida=10 korda)
			while($stmt->fetch()) {
				$person=new StdClass();
					$person->id=$id;
					$person->Gender=$Gender;
					$person->Age=$Age;
					$person->MealClass=$MealClass;
					$person->date=$date;
				
				
				//echo $Color."<br>";
				array_push($result, $person);
			}
			$stmt->close();
			
			return $result;
			
		}
	
	}
?>