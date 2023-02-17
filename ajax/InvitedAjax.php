<?php
	$PeticionAjax=true;
	require_once "../config/app.php";

	if(isset($_POST['invited_cedula_bus']) || isset($_POST['invited_cedula_aut']) || isset($_POST['inv_id_recive']) )
	{
		

		/*--------- Instancia al controlador ---------*/
		require_once "../controller/InvitedController.php";
		$ins_invitar = new InvitedController();

			
		/*--------- Agregar un usuario ---------*/
		if(isset($_POST['invited_cedula_bus']))
		{
			echo $ins_invitar->BuscarInvitedCedula();
		}

		if (isset($_POST['invited_cedula_aut'])) 
		{
			echo $ins_invitar->AutorizarInvitedController();
		}
		if (isset($_POST['inv_id_recive'])) 
		{
			echo $ins_invitar->EntSalInvitedController();
		}

		
	}
	elseif (isset($_POST['invited_dni_reg']))
	{
		

		/*--------- Instancia al controlador ---------*/
	 	require_once "../controller/InvitedController.php";
	 	$ins_invitar = new InvitedController();

			
	 	/*--------- Agregar un usuario ---------*/
	 	if(isset($_POST['invited_dni_reg']))
	 	{
	 		$ins_invitar->CrearInvitedController();
	 	}
	}
	else
	{
		session_start(['name'=>'SPM']);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
		exit();

		$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"falla ajax false Una Cedula",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
	}