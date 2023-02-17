



<?php
	$PeticionAjax=true;
	require_once "../config/app.php";

    if (isset($_POST['diagnostico_des_reg']))
	{
		

		/*--------- Instancia al controlador ---------*/
		require_once "../controller/DiagnosticoController.php";
		$ins_diagnostico = new DiagnosticoController();

			
		/*--------- Agregar un usuario ---------*/
		if(isset($_POST['diagnostico_des_reg']))
		{
			 $ins_diagnostico->AgregarDiagnosticoController();
		}
	}
	else
	{
		/*session_start(['name'=>'SPM']);
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
		exit();*/

		$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"falla ajax false Una Cedula",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
	}