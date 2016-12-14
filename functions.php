<?php

	require("/home/eliispiiri/config.php");

	/* ALUSTAN SESSIOONI */
	session_start();
		
	/* ÜHENDUS */
	$database = "if16_eliispiiri";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);


?>