<?php 
	if ($PeticionAjax) 
	{
		require_once '../models/BanModel.php';
	}
	else
	{
		require_once './models/BanModel.php';
	}

/**
 * 
 */
class BanController extends BanModel
{
	public function BuscarBanCedula()
	{
		$cedula=MainModel::CleanString($_POST['ban_cedula_bus']);

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

		if(mainModel::VerificarDatos("[0-9-]{10,12}",$cedula))
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
	


		

		$ced=BanModel::BuscarBanModel($cedula);

		if (empty($ced) ||is_null($ced)) 
		{
			$alerta=
				[
					"Alerta"=>"simple",
					"Titulo"=>"La Cedula ".$cedula." No Existe",
					"Texto"=>"La Cedula ".$datos['cedula']." No Existe",
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
					"Result1"=>["#ban_dni" =>"".$item["CEDULA_COMPLETA"]."",
								"#ban_nombre" =>"".$item["NOMBRES"]."",
			 "#ban_apellido" =>"".$item['APELLIDO1']." ".$item['APELLIDO2']."",
							    "#ban_telefono" =>"".$item["TELEFONO"].""],
					"Result2"=>["#ban_dni",
								"#ban_nombre",
							    "#ban_apellido",
							    "#ban_telefono"]
				];
				echo json_encode($alerta);
				exit();
			}#end foreach
		}#end if else
		
	}#end function	


	public function CrearBanController()
	{
		$ced=MainModel::CleanString($_POST['ban_dni_reg']);
		$nom=MainModel::CleanString($_POST['ban_nombre_reg']);
		$ape=MainModel::CleanString($_POST['ban_apellido_reg']);
		$tel=MainModel::CleanString($_POST['ban_telefono_reg']);
		$coment=MainModel::CleanString($_POST['ban_nota_reg']);
		//$privilegio=MainModel::Decryption($privilegio);
		$iduser=MainModel::CleanString($_POST['user_id']);
		$iduser=MainModel::Decryption($iduser);
		
		if($ced=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado ",
				"Texto"=>"No has llenado todos los campos que son obligatorios",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		if($nom=="" || $ape=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado ",
				"Texto"=>"No has llenado todos los campos que son obligatorios",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif


		if($coment=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado ",
				"Texto"=>"Debes digitar la razon del veto",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		if($iduser=="" || !isset($iduser))
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado ",
				"Texto"=>"el usuario que esta creando el veto no existe",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();

		}#endif


		/*if ($privilegio<5) 
		{
			
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"No tienes permiso para esta accion ",
					"Texto"=>"tu usuario no tienes permiso para vetar personas",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();	
		}#endif*/

		$check_aut=MainModel::ConsultaSimple("SELECT estado FROM vetados WHERE cedula='$ced'");

		if ($check_aut->rowCount()>0) 
		{
		
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Esta persona ya esta vetada",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif


		$DatosUser=
		[
			'cedula'=>$ced,
			'nombre'=>$nom,
			'apellido'=>$ape,
			'telefono'=>$tel,
			'comentario'=>$coment,
			'iduser'=>$iduser
		];
		$Agregar_Ban=BanModel::AgregarBanModel($DatosUser);
		if ($Agregar_Ban->rowCount()==1) 
		{
		
			$alerta=
				[
					"Alerta"=>"limpiar",
					"Titulo"=>"Usuario Registrado",
					"Texto"=>"Los Datos se han registrado exitosamente",
					"Tipo"=>"success"
				];
		}
		else
		{
			$alerta=
				[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se ha podido registrar el invitado",
					"Tipo"=>"error"
				];
			#echo json_encode($alerta);
				
		}
		echo json_encode($alerta);
	}


	public function DataBanController($type,$id)
	{
		$type=mainModel::CleanString($type);
		$id=mainModel::Decryption($id);
		$id=MainModel::CleanString($id);


		return BanModel::DataBanModel($type,$id);
	}	
	public function PageBanController($pag,$registro,$pri,$url,$search,$id)
	{
		$pag=MainModel::CleanString($pag);
		$registro=MainModel::CleanString($registro);
		$pri=MainModel::CleanString($pri);
		$search=MainModel::CleanString($search);
		$id=MainModel::CleanString($id);
		$url=MainModel::CleanString($url);

		$url=SERVERURL.$url."/";
		$tabla="";

		$pag=(isset($pag) && $pag>0) ? (int) $pag :1 ;
		$start=($pag>0)?(($pag*$registro)-$registro): 0;

		if (isset($search) && $search!="") 
		{
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from vetados WHERE ((id_usuario!='$id' AND id_usuario!='1')AND (cedula like '%$search%' OR ban_nom like '%$search%' OR ban_ape like '%$search%')) ORDER BY ban_nom ASC LIMIT $start,$registro";
		} 
		else 
		{
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from vetados ORDER BY ban_nom ASC LIMIT $start,$registro";
		}
		
		$conne= MainModel::Conectar();
		$datos= $conne->query($consulta);
		$datos=$datos->fetchall();

		$total= $conne->query("SELECT FOUND_ROWS()");
		$total= (int) $total->fetchcolumn();

		$Npag=ceil($total/$registro);

		$tabla.='<div class="table-responsive">
			<table class="table table-dark table-sm">
				<thead>
					<tr class="text-center roboto-medium">
						<th>#</th>
						<th>CEDULA</th>
						<th>NOMBRE</th>
						<th>APELLIDO</th>
						<th>ACTUALIZAR</th>
						<th>ELIMINAR</th>
					</tr>
				</thead>
				<tbody>';
			if ($total>=1 && $pag<=$Npag)
			{
				$contador=$start+1;
				$reg_start=$start+1;
				foreach ($datos as $row ) 
				{

					$tabla.='<tr class="text-center" >
						<td>'.$contador.'</td>
						<td>'.$row["ban_ced"].'</td>
						<td>'.$row["ban_nom"].'</td>
						<td>'.$row["ban_ape"].'</td>

						<td>'.$row["ban_nota"].'</td>
						<td>
							<form   class="FormularioAjax" action="'. SERVERURL.'ajax/BanAjax.php" method="POST" data-form="delete" autocomplete="off">
								<input type="hidden" name="ban_id_del" value="'.MainModel::Encryption($row["ban_id"]).'">
								
								<button type="submit" class="btn btn-warning btn-lg">
									<i class="far fa-trash-alt"></i>
								</button>
							</form>
						</td>
					</tr>';
					$contador++;
				}#endforeach
				$reg_end=$contador-1;
			}
			else
			{
				if ($total>=1) 
				{
					$tabla.='<tr class="text-center" > <td colspan="9"><a href="'.$url.'" class="btn btn-raised btn-primary btn-sm">Click para recargar el listado </a></td></tr>';


				} 
				else 
				{
					
					$tabla.='<tr class="text-center" > <td colspan="9"> No hay registros en el sistema</td></tr>';
				}
				
			}#end if else


		$tabla.='</tbody></table></div>';

		if ($total>=1 && $pag<=$Npag) 
		{
			$tabla.='<p class="text-right"> Mostrando usuario '.$reg_start.' a '.$reg_end.' de '.$total.'</p>';
		}

		if ($total>=1 && $pag<=$Npag) 
		{
			$tabla.=MainModel::PaginadorTablas($pag,$Npag,$url,7);
		}

		return $tabla;



	}/*--------- Fin Funtion ---------*/

}