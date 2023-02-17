<?php 

	$PeticionAjax=true;
	require_once "../config/app.php";

	if(isset($_POST['med_nombre_reg']) || isset($_POST['input_medicamento_bus']) )
	{
		

		/*--------- Instancia al controlador ---------*/
		require_once "../controller/MedicamentosController.php";
		$ins_med = new MedicamentosController();


		/*--------- Buscar un Medicamento ---------*/
		if(isset($_POST['input_medicamento_bus']))
		{
			echo $ins_med->BuscarMedicamentosController();
		}
			
		/*--------- Agregar un Medicamento ---------*/
		if(isset($_POST['med_nombre_reg']))
		{
			echo $ins_med->CrearMedicamentosController();
		}

		/*if (isset($_POST['ban_cedula_aut'])) 
		{
			echo $ins_invitar->AutorizarbanController();
		}
		if (isset($_POST['inv_id_recive'])) 
		{
			echo $ins_invitar->EntSalbanController();
		}*/

		
	}
	elseif (isset($_POST['ban_dni_reg']))
	{
		

		/*--------- Instancia al controlador ---------*/
	 	require_once "../controller/banController.php";
	 	$ins_invitar = new banController();

			
	 	/*--------- Agregar un usuario ---------*/
	 	if(isset($_POST['ban_dni_reg']))
	 	{
	 		$ins_invitar->CrearbanController();
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