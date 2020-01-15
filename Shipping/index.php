<?php
session_start();

include("Controller.php");

	$ctrl = new Controller();
	$ctrl->start();

?>