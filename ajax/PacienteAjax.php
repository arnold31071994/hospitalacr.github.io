<?php
	$PeticionAjax=true;
	require_once "../config/app.php";

	if(isset($_POST['paciente_ced_reg']) || isset($_POST['paciente_data_bus']) || isset($_POST['id_agregar_pac']) || isset($_POST['id_remover_pac']) || isset($_POST['enfermedad_data_bus']) || isset($_POST['id_agregar_enf']) || isset($_POST['id_eliminar_pat']))
	{
		 
		/*--------- Instancia al controlador ---------*/
		require_once "../controller/PacienteController.php";
		$ins_pac = new PacienteController();

			 
		/*--------- Agregar un Paciente ---------*/
		if(isset($_POST['paciente_ced_reg']))
		{
			echo $ins_pac->AgregarPacienteController();
		}
 
		/*--------- Buscar un Paciente ---------*/
		if(isset($_POST['paciente_data_bus']))
		{
			echo $ins_pac->BuscarPacienteController();
		}

 		/*--------- agregar paciente para receta ---------*/
		if(isset($_POST['id_agregar_pac']))
		{
			echo $ins_pac->AgregarPacienteRecetaController();
		}
		/*--------- eliminar paciente para receta ---------*/
		if(isset($_POST['id_remover_pac']))
		{
			echo $ins_pac->RemoverPacienteRecetaController();
		}
		/*--------- Buscar Enfermedades  ---------*/
		if(isset($_POST['enfermedad_data_bus']))
		{
			echo $ins_pac->BuscarEnfermedadesController();
		}
		/*--------- Agregar Enfermedades  ---------*/
		if(isset($_POST['id_agregar_enf']))
		{
			echo $ins_pac->AgregarEnfermedadesController();
		}
		if (isset($_POST['id_eliminar_pat'])) 
		{
			echo $ins_pac->RemoverEnfermedadesController();
		}

	}
	//  elseif (isset($_POST['usuario_dni_reg']))
	// {
		

	// 	/*--------- Instancia al controlador ---------*/
	// 	require_once "../controller/PacienteController.php";
	// 	$ins_usuario = new UsuarioController();

			
	// 	/*--------- Agregar un usuario ---------*/
	// 	if(isset($_POST['usuario_dni_reg']))
	// 	{
	// 		 $ins_usuario->AgregarUsuarioController();
	// 	}
	// }
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