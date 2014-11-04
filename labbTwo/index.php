<?php

	session_start();
	
	require_once("controller/LoginController.php");

	$loginController = new LoginController();
	$loginController->startController();



