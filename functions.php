<?php

	require("/home/eliispiiri/config.php");

	/* ALUSTAN SESSIOONI */
	session_start();
		
	/* �HENDUS */
	$database = "if16_eliispiiri";
	$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);


?>