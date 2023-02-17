<?php
	$PeticionAjax=true;
	require_once "../config/app.php";

	if(isset($_POST['token']) && isset($_POST['usuario']))
	{
		
		require_once "../controller/LoginController.php";
		$ins_login = new LoginController();	

		echo $ins_login->CloseSesionController();	
	}
	else
	{
		session_start(['name'=>'SPM']);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
		exit();
	}