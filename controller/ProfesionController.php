

<?php 
	if ($PeticionAjax) 
	{
		require_once '../models/ProfesionModel.php';
	}
	else
	{
		require_once './models/ProfesionModel.php';
	}

/**
 * 
 */
class ProfesionController extends ProfesionModel
{
	public function BuscarProfesion()
	{
		$res=ProfesionModel::GetProfesionModel();
		#echo='<label for="medico">medico</label>';
		#echo ' <select name="medico" id="medico">';
		#' <option value="pri">prueba</option>';
		echo '<label for="medicosl" class="bmd-label-floating">Profesion</label>';
		echo'<select class="form-control" name="usuario_profesion_reg">';
		echo '<option value="profesion">profesion</option>';
		foreach ($res as $row => $villa) 
		{
			echo '<option value="'.MainModel::Encryption($villa["profesion_nom"]).'">'.$villa["profesion_nom"].'</option>';
		}
		echo'</select>';
		
	}
/*
	public function AgregarVillaController()
	{
		$nom=MainModel::CleanString($_POST['villa_nombre_reg']);
		$dir=MainModel::CleanString($_POST['villa_dir_reg']);
		$ndir=MainModel::CleanString($_POST['villa_ndir_reg']);
		$precio=MainModel::CleanString($_POST['villa_precio_reg']);
		$sta=MainModel::CleanString($_POST['villa_status_reg']);

		if($nom=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor Digite El Nombre de la Villa",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		if($dir=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor Digite Una Direccion",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}		

		if($ndir=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor Digite el Numero Direccion",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		if($sta=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor especifique si esta Activa o Inactiva",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}		

		if(mainModel::VerificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,25}",$nom))
		{
			$alerta=
			[
				"Alerta"=>"recargar",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"El parametro del nombre tiene problemas",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		if(mainModel::VerificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,50}",$dir))
		{
			$alerta=
			[
				"Alerta"=>"recargar",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"El parametro de la direccion tiene problemas",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		if(mainModel::VerificarDatos("[0-9-]{1,4}",$ndir))
		{
			$alerta=
			[
				"Alerta"=>"recargar",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Los parametros del usuario tienen problemas",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		if(mainModel::VerificarDatos("[0-1-]{1}",$sta))
		{
			$alerta=
			[
				"Alerta"=>"recargar",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Los parametros del estatus no cumple lo requisitos",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		$check_nom_villa=MainModel::ConsultaSimple("SELECT * FROM villa WHERE nom_villa='$nom'");
		$check_dir_villa=MainModel::ConsultaSimple("SELECT * FROM villa WHERE dir_villa='$dir' AND num_villa='$ndir' ");

		if ($check_nom_villa->rowCount()>0) 
		{
			$a=$check_vet->fetch();
		
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Ya existe una villa llamada ".$a['nom_villa'],
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		if ($check_dir_villa->rowCount()>0) 
		{
			$a=$check_vet->fetch();
		
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Ya hay una villa en esta direccion: ".$a['dir_villa']." ".$a['num_villa'],
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		$Datosvilla=['nom'=>$nom,
		'dir'=>$dir,		
		'num_dir'=>$ndir,
		'estado'=>$sta,
		'p'=>$precio];

		$agregar_villa=VillaModel::AgregarVillaModel($Datosvilla);

		if ($agregar_villa->rowCount()==1) 
		{
			$alerta=
				[
					"Alerta"=>"limpiar",
					"Titulo"=>"Usuario Registrado",
					"Texto"=>"Los Datos se han registrado exitosamente",
					"Tipo"=>"success "
				];
		}
		else
		{
			$arr=$agregar_villa->errorInfo();
			$alerta=
				[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No se ha podido registrar la villa ".$arr[2],
					"Tipo"=>"error"
				];
			#echo json_encode($alerta);
				
		}
		echo json_encode($alerta);


	}#endfunction agregar villa controller
	public function PageVillaController($pag,$registro,$pri,$url,$search,$id)
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
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from usuario WHERE ((id_usuario!='$id' AND id_usuario!='1')AND (cedula like '%$search%' OR nombre like '%$search%' OR apellido like '%$search%')) ORDER BY nombre ASC LIMIT $start,$registro";
		} 
		else 
		{
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from villa ORDER BY nom_villa ASC LIMIT $start,$registro";
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
						<th>NOMBRE</th>
						<th>DIRECCION</th>
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
					if ($row["est_villa"]==1)
					 {
						$sta='<td> <span class="badge badge-info">Activa</span></td>';
						$staicon='<i class="fas fa-times"></i>';
						//fas fa-user-times
					}
					else
					{
						$sta='<td> <span class="badge badge-danger">Inactiva</span></td>';
						$staicon='<i class="fas fa-check"></i>';
						//fas fa-user-check
					}
					$tabla.='<tr class="text-center" >
						'.$sta.'
						<td>'.$contador.'</td>
						<td>'.$row["nom_villa"].'</td>
						<td>'.$row["dir_villa"].' #'.$row["num_villa"].'</td>
						<td>
							<a href="'.SERVERURL.'user-update/'.MainModel::Encryption($row["id_villa"]).'/" class="btn btn-success">
									<i class="fas fa-sync-alt"></i>	
							</a>
						</td>
						<td>
							<form   class="FormularioAjax" action="'. SERVERURL.'ajax/UsuarioAjax.php" method="POST" data-form="delete" autocomplete="off">
								<input type="hidden" name="user_id_del" value="'.MainModel::Encryption($row["id_villa"]).'">
								<input type="hidden" name="user_sta_del" value="'.MainModel::Encryption($row["est_villa"]).'">
								<button type="submit" class="btn btn-dark btn-lg">
										'.$staicon.'
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


		public function DataProfesionController($type)
	{
		$type=mainModel::CleanString($type);
		/*$id=mainModel::Decryption($id);
		$id=MainModel::CleanString($id);*/


		return ProfesionModel::DataProfesionModel($type);
	}/*--------- Fin Funtion ---------*/	


}
#echo '<option value="'.$med["MED_NOM"].'">'.$med["MED_NOM"].'</option>'; 