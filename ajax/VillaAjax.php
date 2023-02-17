



<?php
	$PeticionAjax=true;
	require_once "../config/app.php";

    if (isset($_POST['villa_nombre_reg']))
	{
		

		/*--------- Instancia al controlador ---------*/
		require_once "../controller/VillaController.php";
		$ins_villa = new VillaController();

			
		/*--------- Agregar un usuario ---------*/
		if(isset($_POST['villa_nombre_reg']))
		{
			 $ins_villa->AgregarVillaController();
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