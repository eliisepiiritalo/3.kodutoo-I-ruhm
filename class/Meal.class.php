<?php 
class Meal {
	
	private $connection;
	
	function __construct($mysqli){
		
		$this->connection = $mysqli;
		
	}

	/*TEISED FUNKTSIOONID */
	function delete($id){

		$stmt = $this->connection->prepare("UPDATE Calender SET deleted=NOW() WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "kustutamine õnnestus!";
		}
		
		$stmt->close();
		
		
	}
		
	function get($q, $sort, $order) {
		
		$allowedSort = ["id", "Gender", "Age", "MealClass", "date"];
		
		if(!in_array($sort, $allowedSort)){
			// ei ole lubatud tulp
			$sort = "id";
		}
		
		$orderBy = "ASC";
		
		if ($order == "DESC") {
			$orderBy = "DESC";
		}
		echo "Sorteerin: ".$sort." ".$orderBy." ";
		
		
		//kas otsib
		if ($q != "") {
			
			echo "Otsib: ".$q;
			
			$stmt = $this->connection->prepare("
				SELECT id, Gender, Age, MealClass, date
				FROM Calender
				WHERE deleted IS NULL 
				AND (Gender LIKE ? OR Age LIKE ? OR MealClass LIKE ? OR date LIKE ?)
				ORDER BY $sort $orderBy
			");
			$searchWord = "%".$q."%";
			$stmt->bind_param("sisi", $searchWord, $searchWord, $searchWord, $searchWord);
			
		} else {
			
			$stmt = $this->connection->prepare("
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
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$Meal = new StdClass();
			
			$Meal->id = $id;
			$Meal->Gender = $Gender;
			$Meal->Age = $Age;
			$Meal->MealClass = $MealClass;
			$Meal->date = $date;
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $Meal);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	function getSingle($edit_id){

		$stmt = $this->connection->prepare("SELECT Gender, Age, MealClass,date  FROM Calender WHERE id=? AND deleted IS NULL");

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

	function save ($Gender, $Age, $MealClass, $date) {
		
		$stmt = $this->connection->prepare("INSERT INTO Calender (Gender, Age, MealClass, date) VALUES (?, ?, ?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("sisi", $Gender, $Age, $MealClass, $date );
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		
		
	}
	
	function update($id, $Gender, $Age, $MealClass, $date ){
    	
		$stmt = $this->connection->prepare("UPDATE Calender SET Gender=?, Age=?, MealClass=?, date=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("sisii",$Gender, $Age, $MealClass, $date, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
		
	}
	
}
?>