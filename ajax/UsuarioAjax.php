<?php
	$PeticionAjax=true;
	require_once "../config/app.php";

	if(isset($_POST['usuario_cedula_bus'])|| isset($_POST['user_id_del']) || isset($_POST['user_id_del']) || isset($_POST['user_id_update']))
	{
		

		/*--------- Instancia al controlador ---------*/
		require_once "../controller/UsuarioController.php";
		$ins_usuario = new UsuarioController();

			
		/*--------- Buscar un usuario ---------*/
		if(isset($_POST['usuario_cedula_bus']))
		{
			echo $ins_usuario->BuscarUsuarioCedula();
		}
		/*--------- Eliminar un usuario ---------*/
		if (isset($_POST['user_id_del'])) {
			echo $ins_usuario->EliminarUsuarioController();
		}
		/*--------- Actualizar un usuario ---------*/
		if (isset($_POST['user_id_update'])) {
			echo $ins_usuario->ActualizarUsuarioController();
		}

		
	}
	 elseif (isset($_POST['usuario_dni_reg']))
	{
		

		/*--------- Instancia al controlador ---------*/
	 	require_once "../controller/UsuarioController.php";
	 	$ins_usuario = new UsuarioController();

			
	 	/*--------- Agregar un usuario ---------*/
	 	if(isset($_POST['usuario_dni_reg']))
	 	{
	 		 $ins_usuario->AgregarUsuarioController();
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