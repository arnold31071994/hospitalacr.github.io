<?php 


/**
 * 
 */
class ClassName extends AnotherClass
{
	
	public function BuscarUsuarioCedula()
	{
		$cedula=MainModel::CleanString($_POST['usuario_cedula_bus']);

		if($cedula=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No has llenado todos los campos que son obligatorios",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		if(mainModel::VerificarDatos("[0-9-]{11,11}",$cedula))
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"El DNI no coincide con el formato solicitado",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif
	


		

			$ced=UsuarioModel::BuscarUsuariosModel($cedula);

			if (empty($ced) ||is_null($ced)) 
			{
				$alerta=
					[
						"Alerta"=>"simple",
						"Titulo"=>"La Cedula ".$cedula." No Existe",
						"Texto"=>"La Cedula ".$cedula." No Existe",
						"Tipo"=>"error"
					];
				echo json_encode($alerta);
				exit();
			}
			else
			{
				foreach ($ced as $row => $item) 
				{
					$alerta=
					[
						"Alerta"=>"cargar",
						"Titulo"=>"Busqueda Exitosa",
						"Texto"=>"".$item["CEDULA_COMPLETA"]."",
						"Tipo"=>"success",
						"Result1"=>["#usuario_dni" =>"".$item["CEDULA_COMPLETA"]."",
									"#usuario_nombre" =>"".$item["NOMBRES"]."",
			 "#usuario_apellido" =>"".$item['APELLIDO1']." ".$item['APELLIDO2']."",
								    "#usuario_telefono" =>"".$item["TELEFONO"]."",
								    "#usuario_direccion" =>"".$item["CALLE"].""],
						"Result2"=>["#usuario_dni",
									"#usuario_nombre",
								    "#usuario_apellido",
								    "#usuario_telefono",
								    "#usuario_direccion"],
						"Result3"=>["$('#usuario_dni').val(".$item["CEDULA_COMPLETA"].")"]
					];
					echo json_encode($alerta);
					exit();


				}#end foreach
				/*foreach ($ced as $row => $item) 
				{
					$alerta=
					[
						"Alerta"=>"cargar",
						"Titulo"=>"Busqueda Exitosa",
						"Texto"=>"".$item["CEDULA_COMPLETA"]."",
						"Tipo"=>"success",
						"Result1"=>"".$item["CEDULA_COMPLETA"]."",
						"Result2"=>"".$item['NOMBRES']."",
						"Result3"=>"".$item['APELLIDO1']." ".$item['APELLIDO2']."",
						"Result4"=>"".$item["TELEFONO"]."",
						"Result5"=>"".$item["CALLE"].""
					];
					echo json_encode($alerta);
					exit();
				}#end foreach*/
			}#end if else
		
	}#end function
}