<?php
	$PeticionAjax=true;
	require_once "../config/app.php";

	if(isset($_POST['id_agregar_med']) || isset($_POST['id_eliminar_med']))
	{
		
		/*--------- Instancia al controlador ---------*/

		//require_once "../controller/RecetaController.php";
		//$ins_rec = new RecetaController();
		require_once "../controller/MedicamentosController.php";
		$ins_med = new MedicamentosController();
		

			 
		/*--------- Agregar un Receta ---------*/
		if(isset($_POST['id_agregar_med']))
		{
			echo $ins_med->AgregarMedicamentoRecetaController();
		}
 
		if(isset($_POST['id_eliminar_med']))
		{
			//require_once "../controller/MedicamentosController.php";
			//$ins_rec = new MedicamentosController();
			echo $ins_med->RemoverMedicamentoRecetaController();
		}
 		

	}
	elseif(isset($_POST['receta_fecha_reg']))
	{
		require_once "../controller/RecetaController.php";
		$ins_rec = new RecetaController();

		if(isset($_POST['receta_fecha_reg']))
		{
			echo $ins_rec->CrearRecetaController();
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