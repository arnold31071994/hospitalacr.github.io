<?php 
if ($PeticionAjax) 
{
	require_once '../models/InvitedModel.php';
}
else
{
	require_once './models/InvitedModel.php';
}


/**
 * 
 */
class InvitedController extends InvitedModel
{
	public function BuscarInvitedCedula()
	{
		$cedula=MainModel::CleanString($_POST['invited_cedula_bus']);

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


		$check_vet=MainModel::ConsultaSimple("SELECT * FROM vetados WHERE ban_ced='$cedula'");

		if ($check_vet->rowCount()>0) 
		{
			$a=$check_vet->fetch();
		
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Esta persona esta vetada por ".$a['ban_nota'],
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif		


		

		$ced=InvitedModel::BuscarInvitedModel($cedula);

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
					"Result1"=>["#invited_dni" =>"".$item["CEDULA_COMPLETA"]."",
								"#invited_nombre" =>"".$item["NOMBRES"]."",
			 "#invited_apellido" =>"".$item['APELLIDO1']." ".$item['APELLIDO2']."",
							    "#invited_telefono" =>"".$item["TELEFONO"].""],
					"Result2"=>["#invited_dni",
								"#invited_nombre",
							    "#invited_apellido",
							    "#invited_telefono"]
				];
				echo json_encode($alerta);
				exit();
			}#end foreach
		}#end if else
		
	}#end function	
	

	public function CrearInvitedController()
	{
		
		$ced=MainModel::CleanString($_POST['invited_dni_reg']);
		$nom=MainModel::CleanString($_POST['invited_nombre_reg']);
		$ape=MainModel::CleanString($_POST['invited_apellido_reg']);
		$tel=MainModel::CleanString($_POST['invited_telefono_reg']);
		$fecInv=MainModel::CleanString($_POST['invited_fecha_reg']);
		$coment=MainModel::CleanString($_POST['invited_comentario_reg']);
		//$aut=MainModel::CleanString($_POST['invited_aut_reg']);		
		$privilegio=MainModel::CleanString($_POST['invited_dni_pri']);
		$privilegio=MainModel::Decryption($privilegio);
		$iduser=MainModel::CleanString($_POST['invited_dni_id']);
		$iduser=MainModel::Decryption($iduser);

		if ($privilegio<5) 
		{
			$aut=MainModel::CleanString($_POST['invited_aut_reg']);
			if ($aut=="") 
			{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"No tienes permiso para esta accion ",
					"Texto"=>"Debes digitar un codigo de autorizacion de un gerente ",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

			$check_aut=MainModel::ConsultaSimple("SELECT estado FROM autorizaciones WHERE aut_cod='$aut' AND estado='disponible'");

			if ($check_aut->rowCount()<=0) 
			{
				
				$alerta=
				[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"El Codigo no es valido o no existe",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
		}
		else
		{
		$aut=0;
		}

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

		$check_vet=MainModel::ConsultaSimple("SELECT * FROM vetados WHERE ban_ced='$ced'");

		if ($check_vet->rowCount()>0) 
		{
			$a=$check_vet->fetch();
		
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Esta persona esta vetada por ".$a['ban_nota'],
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif		

		if($nom=="" || $ape=="")
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


		if($tel=="")
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

		if($fecInv=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Elija la fecha de la Visita",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		if($fecInv < date("Y-m-d"))
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"La fecha de la visita no puede ser antes de hoy",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		if($coment=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Diga el motivo de la visita",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif	

		if(MainModel::VerificarDatos("[0-9-]{10,20}",$ced)) 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No ha Intruducido Datos Validos",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}





		$DatosUser=[
		'cedula'=>$ced,
		'nombre'=>$nom,
		'apellido'=>$ape,
		'fecha'=>$fecInv,
		'telefono'=>$tel,
		'comentario'=>$coment,
		'autorizacion'=>$aut,
		'iduser'=>$iduser];

		$agregar_invitado=InvitedModel::AgregarInvitedModel($DatosUser);
		if ($agregar_invitado->rowCount()==1) 
		{
			MainModel::ConsultaSimple("UPDATE autorizaciones SET estado='usado',cedula='$ced', fecha_u_aut=(SELECT CURRENT_TIMESTAMP()) WHERE aut_cod='$aut'");
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


	}#end funcion CrearInvitedController

	public function AutorizarInvitedController()
	{
		$aut=MainModel::CodigoRamdon(8);
		$id=MainModel::CleanString($_POST['id_usuario_aut']);
		$sta="disponible";

		$DatosAut=[
		"usuario"=>$id,
		"codaut"=>$aut,
		"estado"=>$sta
		];

		$crear_aut=InvitedModel::AutorizarInvitedModel($DatosAut);

		if ($crear_aut->rowCount()==1) 
		{
			$alerta=
				[
					"Alerta"=>"recargar",
					"Titulo"=>"autorizacion Creada",
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
					"Texto"=>"No se ha podido registrar al usuario",
					"Tipo"=>"error"
				];
			#echo json_encode($alerta);
				
		}
		echo json_encode($alerta);

		/*	$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un ".$aut." error inesperado",
				"Texto"=>"No has ".$id." todos los campos que son obligatorios",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();*/
		
		//return $aut;
	}


	public function PageAutController($pag,$registro,$pri,$url,$search,$id)
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
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from autorizaciones WHERE id_usuario='$id'  and estado='disponible' AND (cedula like '%$search%' OR nombre like '%$search%' OR apellido like '%$search%')) ORDER BY fecha_c_aut ASC LIMIT $start,$registro*/";
		} 
		else 
		{
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from autorizaciones WHERE id_usuario='$id' and estado='disponible' ORDER BY fecha_c_aut ASC LIMIT $start,$registro";#AND id_usuario!='1'
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
						<th>FECHA</th>
						<th>CODIGO</th>
						<th>ESTADO</th>
						<th>CEDULA</th>
						<th>FECHA USO</th>
						<th>EMAIL</th>
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
					if ($row["cedula"]=="") 
					{
						$ced="N/A";
					}
					else
					{
						$ced=$row["cedula"];
					}
					if ($row["estado"]=="disponible")
					 {
						$sta='<td> <span class="badge badge-info">'.$row["estado"].'</span></td>';
					}
					else
					{
						$sta='<td> <span class="badge badge-danger">'.$row["estado"].'</span></td>';
					}
					$tabla.='<tr class="text-center" >
						<td>'.$contador.'</td>
						<td>'.$row["fecha_c_aut"].'</td>
						<td>'.$row["aut_cod"].'</td>
						'.$sta.'								
						<td>'.$ced.'</td>
						<td>'.$row["fecha_u_aut"].'</td>
						<td>'.$row["aut_cod"].'</td>
						<td>
							<a href="'.SERVERURL.'user-update/'.MainModel::Encryption($row["id_usuario"]).'/" class="btn btn-success">
									<i class="fas fa-sync-alt"></i>	
							</a>
						</td>
						<td>
							<form   class="FormularioAjax" action="'. SERVERURL.'ajax/UsuarioAjax.php" method="POST" data-form="delete" autocomplete="off">
								<input type="hidden" name="user_id_del" value="'.MainModel::Encryption($row["id_usuario"]).'">
								<button type="submit" class="btn btn-warning">
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

	public function DataInvitedController($type,$id,$i_fecha,$f_fecha)
	{
		$type=mainModel::CleanString($type);
		$id=mainModel::Decryption($id);
		$id=MainModel::CleanString($id);


		return InvitedModel::DataInvitedModel($type,$id,$i_fecha,$f_fecha);
	}	
		public function PageInvController($pag,$registro,$pri,$url,$search,$id)
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
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from invitados WHERE (inv_ced like '%$search%' OR inv_nom like '%$search%' OR inv_ape like '%$search%') ORDER BY inv_nom ASC LIMIT $start,$registro";
		} 
		else 
		{
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from invitados WHERE inv_fecha= (SELECT CURRENT_DATE)/*id_usuario!='$id' AND id_usuario!='1' */ ORDER BY inv_fecha ASC LIMIT $start,$registro";
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
						<th>RAZON</th>
						<th>ESTADO</th>
						<th>ENTRADA</th>
						<th>SALIDA</th>									
						<th>MARCAR</th>
					</tr>
				</thead>
				<tbody>';
			if ($total>=1 && $pag<=$Npag)
			{
				$contador=$start+1;
				$reg_start=$start+1;
				foreach ($datos as $row ) 
				{
					if ($row["inv_hora_ent"]=="" && $row["inv_hora_salida"]=="")
					 {
						$sta='<td> <span class="badge badge-danger">'.'Sin Registrar'.'</span></td>';
					}
					elseif(isset($row["inv_hora_ent"]) && $row["inv_hora_salida"]=="")
					{
						$sta='<td> <span class="badge badge-warning">'.'En Proceso'.'</span></td>';
					}
					elseif(isset($row["inv_hora_ent"]) && isset($row["inv_hora_salida"]) )
					{
						$sta='<td> <span class="badge badge-success">'.'Completado'.'</span></td>';
					}

					$tabla.='<tr class="text-center" >
						
						<td>'.$contador.'</td>
						<td>'.$row["inv_ced"].'</td>
						<td>'.$row["inv_nom"].' '.$row["inv_ape"].'</td>
						<td>'.$row["inv_com"].'</td>
						'.$sta.'
						<td>'.$row["inv_hora_ent"].'</td>
						<td>'.$row["inv_hora_salida"].'</td>
						<td>
							<form   class="FormularioAjax" action="'. SERVERURL.'ajax/InvitedAjax.php" method="POST" data-form="delete" autocomplete="off">
								<input type="hidden" name="inv_id_recive" value="'.MainModel::Encryption($row["id_inv"]).'">
								<input type="hidden" name="inv_hor_ent" value="'.MainModel::Encryption($row["inv_hora_ent"]).'">
								<input type="hidden" name="inv_hor_sal" value="'.MainModel::Encryption($row["inv_hora_salida"]).'">
								<button type="submit" class="btn btn-dark">
										<i class="fas fa-edit"></i>
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


	public function EntSalInvitedController()
	{
		$id_inv=mainModel::Decryption($_POST['inv_id_recive']);
		$ent=mainModel::Decryption($_POST['inv_hor_ent']);
		$sal=mainModel::Decryption($_POST['inv_hor_sal']);

		mainModel::VerificarDatos("[0-9-]{1,12}",$id_inv);
		mainModel::VerificarDatos("[0-24-]{8,8}",$ent);
		mainModel::VerificarDatos("[0-24-]{8,8}",$sal);

		if (empty($ent) ||is_null($ent) || $ent='') 
		{
			$ah=MainModel::ConsultaSimple("UPDATE invitados SET inv_hora_ent=(SELECT CURRENT_TIME()) WHERE id_inv='$id_inv'");
		}
		elseif(isset($ent) && empty($sal) ||is_null($sal))
		{
			$ah=MainModel::ConsultaSimple("UPDATE invitados SET inv_hora_salida=(SELECT CURRENT_TIME()) WHERE id_inv='$id_inv'");
		}
		elseif (isset($ent) && isset($sal)) 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"La entrada y la salida ya fueron confirmadas no puede alterar nada mas",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();			
		}

		if ($ah->rowCount()==1) 
		{
			$alerta=
			[
				"Alerta"=>"recargar",
				"Titulo"=>"Hora Registrada",
				"Texto"=>"Se ha registrado la hora correctamente",
				"Tipo"=>"success"
			];
			//echo json_encode($alerta);
		}
		else
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No se pudo registrar la hora",
				"Tipo"=>"error"
			];
			#echo json_encode($alerta);
				
		}
		echo json_encode($alerta);



	}/*--------- Fin Funtion ---------*/


		public function PageUserController($pag,$registro,$pri,$url,$search,$id)
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
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from invitados WHERE ((id_usuario!='$id' AND id_usuario!='1')AND (inv_ced like '%$search%' OR inv_nom like '%$search%' OR inv_ape like '%$search%')) ORDER BY inv_nom ASC LIMIT $start,$registro";
		} 
		else 
		{
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from invitados WHERE id_usuario!='$id' AND id_usuario!='1'  ORDER BY inv_nom ASC LIMIT $start,$registro";
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
						<th>ESTADO</th>
						<th>#</th>
						<th>CEDULA</th>
						<th>NOMBRE</th>
						<th>APELLIDO</th>
						<th>TELÉFONO</th>
						<th>USUARIO</th>
						<th>EMAIL</th>
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
						<td> <span class="badge badge-danger">'.$row["inv_ced"].'</span></td>
						<td>'.$contador.'</td>
						<td>'.$row["inv_ced"].'</td>
						<td>'.$row["inv_ced"].'</td>
						<td>'.$row["inv_ced"].'</td>
						<td>'.$row["inv_ced"].'</td>
						<td>'.$row["inv_ced"].'</td>
						<td>'.$row["inv_ced"].'</td>
						<td>
							<a href="'.SERVERURL.'user-update/'.MainModel::Encryption($row["id_usuario"]).'/" class="btn btn-success">
									<i class="fas fa-sync-alt"></i>	
							</a>
						</td>
						<td>
							<form   class="FormularioAjax" action="'. SERVERURL.'ajax/UsuarioAjax.php" method="POST" data-form="delete" autocomplete="off">
								<input type="hidden" name="user_id_del" value="'.MainModel::Encryption($row["id_usuario"]).'">
								<input type="hidden" name="user_sta_del" value="'.MainModel::Encryption($row["inv_ced"]).'">
								<button type="submit" class="btn btn-warning">
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