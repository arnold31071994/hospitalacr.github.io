<?php 
	session_start(['name'=>'SPM']);
	require_once "../config/app.php";

	if (isset($_POST['search_inicial']) || isset($_POST['delete_search']) || isset($_POST['end_date']) || isset($_POST['start_date'])) 
	{
		$data_url=["paciente"=>"paciente-search","user"=>"user-search","receta"=>"receta-search","ban"=>"ban-search","historial"=>"historial-search","medicamentos"=>"medicamentos-search"];

		if (isset($_POST['modulo'])) 
		{
			$modulo=$_POST['modulo'];
			if (!isset($data_url[$modulo])) 
			{
				$alerta=
				[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Ocurrio un erro de configuracion y no se pudo realizar la search3",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
		}
		else
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Ocurrio un erro de configuracion y no se pudo realizar la search",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		if ($modulo=='historial') 
		{
			$start_date='date_start'.$modulo;
			$end_date='date_end'.$modulo;

			//REALIZAR search
			if (isset($_POST['start_date']) || isset($_POST['end_date'])) 
			{
				if ($_POST['start_date']=="" || $_POST['end_date']=="" ) 
				{
					$alerta=
					[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Debe poner una fecha de inicio y una de final",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();					
				}#endif si fechas vacias
				if ($_POST['end_date']> $_POST['start_date']) 
				{
					$alerta=
					[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"error con las fechas",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();					# code...
				}#endif si fechas final mayor a fecha inicial

				$_SESSION[$start_date]=$_POST['start_date'];
				$_SESSION[$end_date]=$_POST['end_date'];
			}#end if isset fechas
				//eliminar search
			if (isset($_POST['delete_search'])) 
			{
				unset($_POST['start_date']);
				unset($_POST['end_date']);
			}
		} 
		else 
		{	//REALIZAR search
			$name_var="search_".$modulo;
			if (isset($_POST['search_inicial'])) 
			{
				if ($_POST['search_inicial']=="") 
				{
					$alerta=
					[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado",
						"Texto"=>"Debe poner un dato para buscar ".$data_url[$modulo]."",
						"Tipo"=>"error"
					];
					echo json_encode($alerta);
					exit();
				}

				$_SESSION[$name_var]=$_POST['search_inicial'];
				
			}#end if isset search inicial


			if (isset($_POST['delete_search'])) 
			{
				unset($_SESSION[$name_var]);
			}
		}#end else


		//redireccionar
		$url=$data_url[$modulo];
		$alerta=[
			"Alerta"=>"redireccionar",
			"URL"=> SERVERURL.$url."/"];
		
		echo json_encode($alerta);
	}
	else
	{
		session_unset();
		session_destroy();
		header("Location: ".SERVERURL."login/");
		exit();		
	}