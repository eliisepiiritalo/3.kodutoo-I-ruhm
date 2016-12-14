<?php 

	require("../functions.php");
	
	require("../class/Meal.class.php");
	$MealClass=new Meal($mysqli);
	
	require("../class/Helper.class.php");
	$Helper=new Helper();
	

	// kas on sisseloginud, kui ei ole siis

	// suunata login lehele

	if (!isset ($_SESSION["userId"])) {

		header("Location: login.php");
		exit();

	}

	//kas ?logout on aadressireal

	if (isset($_GET["logout"])) {

		session_destroy();

		header("Location: login.php");
		exit();

	}

			// echo $date
			
	//muutujad
	$Gender="";
	$GenderError="";
	$Age="";
	$AgeError="";
	$Meal="";
	$MealError="";
	$Date="";
	$DateError="";
	
	//Kontrollin, kas kasutaja sisestas andmed
	if(isset($_POST["Age"])) {
			if (empty($_POST["Age"])){
			$AgeError="See väli on kohustuslik!";
			
			}else {
				$Age=$_POST["Age"];
			}
	}
	
	if(isset($_POST["Meal"])) {
		if(empty($_POST["Meal"])){
			$MealError="See väli on kohustuslik!";
			
		}else{
			$Meal=$_POST["Meal"];
		}
	}
	
	if(isset($_POST["Date"])) {
		if(empty($_POST["Date"])){
			$DateError="See väli on kohustuslik!";
			
		}else{
			$Date=$_POST["Date"];
		}
	}
	//Ühtegi viga ei olnud ja saan kasutaja andmed salvestada
	if ( isset($_POST["Gender"]) &&
		isset($_POST["Age"]) &&
		isset($_POST["Meal"]) &&
		isset($_POST["Date"]) &&
		
		
		empty($_POST["GenderError"]) &&
		empty($_POST["AgeError"]) &&
		empty($_POST["MealError"]) &&
		empty($_POST["DateError"])  

	  ) {
		  
		 // echo "siin";
		  
			$Gender=$Helper->cleanInput($_POST["Gender"]);
			$Age=$Helper->cleanInput($_POST["Age"]);
			$Meal=$Helper->cleanInput($_POST["Meal"]);
			//$Date=$Helper->cleanInput($_POST["Date"]);

			$date = new Datetime ($_POST['Date']);
			$date = $date->format('Y-m-d');
		
			$MealClass->savePeople($Helper->cleanInput($_POST["Gender"]), $Helper->cleanInput($_POST["Age"]), $Helper->cleanInput($_POST["Meal"]), $Helper->cleanInput($date));
	
	//header("Location: data.php");
	//exit();
	
	}

	//$people = getAllPeople();
	
	//var_dump($people[1]);
	
	if (isset($_GET["q"])){
		
			//kui otsitakse, võtame otsisõna aadressirealt
			$q=$_GET["q"];
		
	}else{
		
			//otsisõna on tühi
			$q="";
		}
	
		$sort="id";
		$order="ASC";
	
		if (isset($_GET["sort"])&& isset($_GET["order"])){
			$sort=$_GET["sort"];
			$order=$_GET["order"];
	}
	
		//otsisõna funktsiooni sisse
		$Calender=$MealClass->get($q, $sort, $order);
	
	
?>

<h1>Toidukordade sisestamine</h1>

<p>

	Tere tulemast <?=$_SESSION["Email"];?>!

	<a href="?logout=1">Logi välja</a>

</p> 

<h1>Salvesta andmed</h1>

<form method="POST">

	<label>Sugu</label><br>
	
	<input type="radio" name="Gender" value="male" > Mees<br>

	<input type="radio" name="Gender" value="female" > Naine<br>
	
	<br><br>
	<label>Vanus</label><br>
	<input name="Age" type="number">

	<br><br>
	<label>Söögikord</label><br>
	<select name="Toidukord">
	<option value="" disabled selected>Vali söögikord</option>
	<option value="Hommikusöök">Hommikusöök</option>
	<option value="Lõunasöök">Lõunasöök</option>
	<option value="Õhtusöök">Õhtusöök</option>
	</select>
	
	
	<input name="Meal" type="text" placeholder="sisaldab">
	<br><br>
	
	<label>Kuupäev</label><br>
	<input name="Date" type="date" placeholder="Kuupäev">
	

	
	<br><br>
	<input type = "submit" value = "Salvesta">

	<!--<input type="text" name="gender" ><br>-->
	
	


</form>

<!--<h2>Varasemad andmed</h2>

		foreach($people as $p){
			
			echo "<h3 style=' Color:".$p->Color."; '>".$p->Gender."</h3>;
		}
		
	-->

<br><br>	
<h2> Kasutajate andmed </h2>
<?php

		$html="<table>";
		$html .="<tr>";
			$idOrder="ASC";
			if(isset($_GET["order"])&& $_GET["order"]=="ASC"){
				$idOrder="DESC";
			}
			$html .="<th>
				<a href='?q=".$q."&sort=id&order=".$idOrder."'>
				</a>
			</th>";
			
			$GenderOrder="ASC";
			if(isset($_GET["order"]) && $_GET["order"]=="ASC"){
				$GenderOrder="DESC";
			}
			$html .="<th>
				<a href='?q=".$q."&sort=Gender&order=".$GenderOrder."'> Sugu
				</a>
			</th>";
			
			$AgeOrder="ASC";
			if(isset($_GET["order"]) && $_GET["order"]=="ASC"){
				$AgeOrder="DESC";
			}
			$html .="<th>
				<a href='?=".$q."&sort=Age&order=".$AgeOrder."'>Vanus
				</a>
			</th>";
			
			$MealOrder="ASC";
			if(isset($_GET["order"]) && $_GET["order"]=="ASC"){
				$Meal="DESC";
			}
			$html .="<th>
				<a href='?=".$q."&sort=Meal&order=".$MealOrder."'>Toidukord
				</a>
			</th>";
			
			$dateOrder="ASC";
			if(isset($_GET["order"]) && $_GET["order"]=="ASC"){
				$dateOrder="DESC";
			}
			$html .="<th>
				<a href='?=".$q."&sort=date&order=".$dateOrder."'>Kuupäev
				</a>
			</th>";
			
		$html .="</tr>";
	
		foreach($Calender as $p){
			$html .="<tr>";
				$html .="<td>".$p->id."</td>";
				$html .="<td>".$p->Gender."</td>";
				$html .="<td>".$p->Age."</td>";
				$html .="<td>".$p->Meal."</td>";
				$html .="<td>".$p->date."</td>";
				
				$html .= "<td><a class='btn btn-default btn-sm' href='edit.php?id=".$p->id."'><span class='glyphicon glyphicon-pencil'></span>Muuda</a></td>";
				
				
			
		    $html .="</tr>";
		
			}
	$html .="</table>";
	echo $html;
?>