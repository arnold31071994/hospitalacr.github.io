
<?php
	$PeticionAjax=true;
	require_once "../config/app.php";
	
	require_once "../controller/PrivilegiosController.php";
#$especialidad=$_POST['especialidad'];
	$ins_pri = new PrivilegiosController();
	$ins_pri->GetPrivilegiosController();