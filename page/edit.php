<?php
	
	require("../function.php");
	
	require("../class/Helper.class.php");
	$Helper=new Helper($mysqli);
	
	require("../class/Meal.class.php");
	$Meal = new Meal($mysqli);
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Meal->update($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["Gender"]), $Helper->cleanInput($_POST["Age"]), $Helper->cleanInput($_POST["Meal"]),$Helper->cleanInput($_POST["date"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true"); //kasutaja jääb samale lehele
        exit();	
		
	}
	
	//kustutan
	if(isset($_GET["delete"])){
		
		$Meal->delete($_GET["id"]);
		
		header("Location: data.php");
		exit();
	}
	
	
	// kui ei ole id'd aadressireal siis suunan
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	
	//saadan kaasa id
	$Calender=$Meal->getSingle($_GET["id"]);
	//var_dump($c);
	
	if(isset($_GET["success"])){
		echo "Salvestamine õnnestus!";
	}
	
?>
	
 
<!DOCTYPE html>
<html>
<head>

<h2>Muuda andmeid</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
	
  	<label for="Gender" >Sugu</label><br>
	<input id="Gender" name="Gender" type="text" value="<?php echo $Calender->Gender;?>" ><br><br>
	
  	<label for="Age" >Vanus</label><br>
	<input id="Age" name="Age" type="text" value="<?=$Calender->Age;?>"><br><br>
	
	<label for="Meal" >Toidukord</label><br>
	<input id="Meal" name="Meal" type="text" value="<?=$Calender->Meal;?>"><br><br>
	
	<label for="date" >Kuupäev</label><br>
	<input id="date" name="date" type="text" value="<?=$Calender->date;?>"><br><br>
	

	<input type="submit" name="update" value="Salvesta">
  </form>
  

  <br>
  <a href="?id=<?=$_GET["id"];?>&delete=true">Kustuta</a>
  <br><br>
  <a href="data.php">Tagasi</a>
  
</body>
</html>