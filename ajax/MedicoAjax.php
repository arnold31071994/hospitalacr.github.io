<?php
	$PeticionAjax=true;
	require_once "../config/app.php";
	
require_once "../controller/MedicoController.php";
#$especialidad=$_POST['especialidad'];
$ins_medico = new MedicoController();
$ins_medico->BuscarMedico($_POST['especialidad']);