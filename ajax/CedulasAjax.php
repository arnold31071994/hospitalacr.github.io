<?php
	$PeticionAjax=true;
	require_once "../config/app.php";

	if(isset($_POST['usuario_cedula_bus']))
	{
		

		/*--------- Instancia al controlador ---------*/
		require_once "../controller/UsuarioController.php";
		$ins_usuario = new UsuarioController();

			
		/*--------- Buscar un usuario ---------*/
		if(isset($_POST['usuario_cedula_bus']))
		{
			echo $ins_usuario->BuscarUsuarioCedula();
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