<?php 
if ($PeticionAjax) 
{
	require_once '../models/PacienteModel.php';
}
else
{
	require_once './models/PacienteModel.php';
}


/**
 * 
 */
class PacienteController extends PacienteModel
{
	public function BuscarPacienteController()
	{
		$pac=MainModel::CleanString($_POST['paciente_data_bus']);
		
		if ($pac=="") 
		{
			return'
			<div class="alert alert-warning" role="alert">
                <p class="text-center mb-0">
                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                    No hemos encontrado ningún cliente en el sistema que coincida con <strong>“Busqueda”</strong>
                </p>
            </div>';

            exit();
		}

		$DatosPaciente=MainModel::ConsultaSimple("SELECT * from pacientes WHERE pac_ced like '%$pac%' OR pac_nom like '%$pac%' OR pac_ape like '%$pac%' ORDER BY pac_nom ASC");

		if ($DatosPaciente->rowCount()>=1) 
		{
			$DatosPaciente=$DatosPaciente->fetchall();

			$table='<div class="table-responsive">
			<table class="table table-hover table-bordered table-sm"> <tbody>';

			foreach($DatosPaciente as $rows)
			{
				$i=$rows["pac_id"];
				$table.='<tr class="text-center">
                            <td>'.$rows['pac_ced'].' - '.$rows['pac_nom'].' '.$rows['pac_ape'].'</td>
                            <td>
                                <button type="button" class="btn btn-primary" onclick="agregar_pac('.$i.')"><i class="fas fa-user-plus"></i></button>
                            </td>
                          </tr>';
			}

			$table.='  </tbody></table></div>';
			return $table;
		}
		else
		{
			return'
			<div class="alert alert-warning" role="alert">
                <p class="text-center mb-0">
                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                    No hemos encontrado ningún cliente en el sistema que coincida con <strong>“'.$pac.'”</strong>
                </p>
            </div>';

            exit();
		}

	}#end function BuscarPaciente


	public function DataPacienteController($type,$id)
	{
		$type=mainModel::CleanString($type);
		$id=mainModel::Decryption($id);
		$id=MainModel::CleanString($id);


		return PacienteModel::DataPacienteModel($type,$id);
	}		# code...
	


	public function AgregarPacienteController()
	{
		#Limpia la Variable para que no haya inyeccion SQL
		$ced=MainModel::CleanString($_POST['paciente_ced_reg']);
		$nom=MainModel::CleanString($_POST['paciente_nom_reg']);
		$ape=MainModel::CleanString($_POST['paciente_ape_reg']);
		$tel=MainModel::CleanString($_POST['paciente_tel_reg']);
		$dir=MainModel::CleanString($_POST['paciente_dir_reg']);
		$sex=MainModel::CleanString($_POST['paciente_sex_reg']);
		$fecn=MainModel::CleanString($_POST['paciente_fecn_reg']);
		$mail=MainModel::CleanString($_POST['paciente_email_reg']);
		$pac_seg=MainModel::CleanString($_POST['paciente_seg_reg']);
		$carnet=MainModel::CleanString($_POST['paciente_carnet_reg']);
		$pac_seg2=MainModel::Decryption($pac_seg);
		/*$privilegio=MainModel::CleanString($_POST['paciente_privilegio_reg']);
		$privilegio=MainModel::Decryption($privilegio);*/



		if($ced=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor Digite la Cedula del Paciente",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}		

		if($nom=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor Digite el Nombre del Paciente",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}		

		if($ape=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor Digite el Apellido del Paciente",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}		


		if($tel=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor Digite el Telefono",
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
				"Texto"=>"Por Favor Digite la Direccion",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}		

		if($sex=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor Elija un Sexo",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}		

		if($fecn=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor Ponga la Fecha de Nacimiento",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}		


		if(isset($pac_seg2))
		{	
			$check_seg=MainModel::ConsultaSimple("SELECT * FROM seguros WHERE seg_id='$pac_seg' AND seg_est='1'");
			if($check_seg->rowCount()==1) 
			{
				$alerta=
					[
						"Alerta"=>"simple",
						"Titulo"=>"Ocurrió un error inesperado ",
						"Texto"=>"El seguro no esta activo o activo registrado",
						"Tipo"=>"error"
					];
				echo json_encode($alerta);
				exit();
			}
				if($carnet=="")
			{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Si selecciono un seguro debe poner el numero de Carnert",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}
		}		


		$respuesta=PacienteModel::BuscarPacienteCreadoModel($ced);
		if($respuesta->rowCount()==1) 
		{
			$alerta=
				[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado ",
					"Texto"=>"La Cedula ".$ced." Ya esta registrada",
					"Tipo"=>"error"
				];
			echo json_encode($alerta);
			exit();
		}	

	  #---------------VERIFICACION DE LOS DATOS ENTRADOS----------------------------------------

		if(MainModel::VerificarDatos("[0-9-]{11}",$ced)) 
		{
			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"EL FORMATO DE LOS DATOS DEL 'CEDULA' NO SON VALIDOS",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}
		
		if(MainModel::VerificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nom)) 
		{
			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"EL FORMATO DE LOS DATOS DEL 'NOMBRE' NO SON VALIDOS",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}
		
		if(MainModel::VerificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$ape)) 
		{
			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"EL FORMATO DE LOS DATOS DEL 'APELLIDO' NO SON VALIDOS",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}
		///error aqui
		if(MainModel::VerificarDatos("[MF]{1}",$sex)) 
		{
			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"EL FORMATO DE LOS DATOS DEL 'SEXO' NO SON VALIDOS",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}

		if(MainModel::VerificarDatos("[0-9-]{10}",$tel)) 
		{
			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"EL FORMATO DE LOS DATOS DEL 'TELEFONO' NO SON VALIDOS",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}

		if(MainModel::VerificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,135}",$dir)) 
		{
			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"EL FORMATO DE LOS DATOS DEL 'DIRECCION' NO SON VALIDOS",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}

		if(MainModel::VerificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-@]{10,80}",$mail)) 
		{
			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"EL FORMATO DE LOS DATOS DEL 'CORREO' NO SON VALIDOS",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}

		if(MainModel::VerificarDatos("[0-9-]{1,3}",$pac_seg2)) 
		{
			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"EL FORMATO DE LOS DATOS DEL 'NOMBRE DEL SEGURO' NO SON VALIDOS",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}

		if(MainModel::VerificarDatos("[0-9-]{1,20}",$carnet)) 
		{
			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"EL FORMATO DE LOS DATOS DEL 'CARNET' NO SON VALIDOS",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}
			#---------------FIN VERIFICACION DE LOS DATOS ENTRADOS---------------------------------


		$DatosPaciente=[
		'cedula'=>$ced,
		'nombre'=>$nom,
		'apellido'=>$ape,
		'telefono'=>$tel,
		'direccion'=>$dir,
		'sexo'=>$sex,		
		'email'=>$mail,
		'fecn'=>$fecn,
		'seguro'=>$pac_seg2,
		'carnet'=>$carnet];


		$agregar_paciente=PacienteModel::AgregarPacienteModel($DatosPaciente);
		if ($agregar_paciente->rowCount()==1) 
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
					"Texto"=>"No se ha podido registrar al usuario",
					"Tipo"=>"error"
				];
			#echo json_encode($alerta);
				
		}
		echo json_encode($alerta);

	}#end Function Agregar Pacientes


	public function AgregarPacienteRecetaController()
	{
		$id=MainModel::CleanString($_POST['id_agregar_pac']);
		
		$CheckPaciente=MainModel::ConsultaSimple("SELECT * from pacientes WHERE pac_id='$id'");

		if ($CheckPaciente->rowCount()<=0) 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No Hemos encontrado al Paciente en la Base de Datos",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();			
		}
		else
		{
			$camp=$CheckPaciente->fetch();
		}

		session_start(['name'=>'SPM']);

		if (empty($_SESSION['data_pac'])) 
		{
			$_SESSION['data_pac']=[
				"id"=>$camp['pac_id'],
				"ced"=>$camp['pac_ced'],
				"nom"=>$camp['pac_nom'],
				"ape"=>$camp['pac_ape']
			];


			$alerta=
			[
				"Alerta"=>"recargar",
				"Titulo"=>"Operacion Exitosa",
				"Texto"=>"El Paciente se agrego para Relizar una Receta",
				"Tipo"=>"success"
			];
			echo json_encode($alerta);
			exit();	

		}
		else
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No se ha podido agregar al paciente",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();	

		}

	}	
	/*--------- Controller Page User ---------*/

	public function PagePacienteController($pag,$registro,$pri,$url,$search,$id)
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
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from pacientes WHERE pac_ced like '%$search%' OR pac_nom like '%$search%' OR pac_ape like '%$search%' ORDER BY pac_nom ASC LIMIT $start,$registro";
		} 
		else 
		{
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from pacientes ORDER BY pac_nom ASC LIMIT $start,$registro";
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
						<th>TELÉFONO</th>
						<th>EDAD</th>
						<th>EMAIL</th>
						<th>ACTUALIZAR</th>
					</tr>
				</thead>
				<tbody>';
			if ($total>=1 && $pag<=$Npag)
			{
				$contador=$start+1;
				$reg_start=$start+1;
				foreach ($datos as $row ) 
				{
					//$edad=date("Y")-$row["pac_fecnac"];
					$cumpleanos = new DateTime($row["pac_fecnac"]);
    				$hoy = new DateTime();
    				$annos = $hoy->diff($cumpleanos);
   					$edad= $annos->y;
					// if (isset($row["pac_id"]))
					//  {
					// 	$sta='<td> <span class="badge badge-info">'.$row["pac_id"].'</span></td>';
					// 	$staicon='<i class="fas fa-times"></i>';
					// 	//fas fa-user-times
					// }
					// else
					// {
					// 	$sta='<td> <span class="badge badge-danger">'.$row["pac_id"].'</span></td>';
					// 	$staicon='<i class="fas fa-check"></i>';
					// 	//fas fa-user-check
					// }
					$tabla.='<tr class="text-center" >
					
						<td>'.$contador.'</td>
						<td>'.$row["pac_ced"].'</td>
						<td>'.$row["pac_nom"].'</td>
						<td>'.$row["pac_ape"].'</td>
						<td>'.$row["pac_tel"].'</td>
						<td>'.$edad.'</td>
						<td>'.$row["pac_email"].'</td>
						<td>
							<a href="'.SERVERURL.'paciente-update/'.MainModel::Encryption($row["pac_id"]).'/" class="btn btn-success">
									<i class="fas fa-sync-alt"></i>	
							</a>
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

	}/*--------- Fin Funtion page patient ---------*/

	public function RemoverPacienteRecetaController()
	{
		session_start(['name'=>'SPM']);

		unset($_SESSION['data_pac']);

		if (empty($_SESSION['data_pac'])) 
		{
			$alerta=
			[
				"Alerta"=>"recargar",
				"Titulo"=>"Paciente Removido",
				"Texto"=>"El Paciente Ha sido removido de la Receta",
				"Tipo"=>"success"
			];
			echo json_encode($alerta);
			exit();	

		}
		else
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No se ha podido remover al paciente",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();	

		}
	}

	public function BuscarEnfermedadesController()
	{
		$enf=MainModel::CleanString($_POST['enfermedad_data_bus']);
		
		if ($enf=="") 
		{
			return'
			<div class="alert alert-warning" role="alert">
                <p class="text-center mb-0">
                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                    No hemos encontrado ningún cliente en el sistema que coincida con <strong>“Busqueda”</strong>
                </p>
            </div>';

            exit();
		}

		$DatosEnfermedad=MainModel::ConsultaSimple("SELECT * from enfermedades WHERE enf_nom like '%$enf%' OR enf_tipo like '%$enf%' ORDER BY enf_nom ASC");

		if ($DatosEnfermedad->rowCount()>=1) 
		{
			$DatosEnfermedad=$DatosEnfermedad->fetchall();

			$table='<div class="table-responsive">
			<table class="table table-hover table-bordered table-sm"> <tbody>';

			foreach($DatosEnfermedad as $rows)
			{
				$i=$rows["enf_id"];
				$table.='<tr class="text-center">
                            <td>'.$rows['enf_nom'].' '.$rows['enf_tipo'].'</td>
                            <td>
                                <button type="button" class="btn btn-primary" onclick="agregar_enf('.$i.')"><i class="fas fa-plus fa-fw"></i></button>
                            </td>
                          </tr>';
			}

			$table.='  </tbody></table></div>';
			return $table;
		}
		else
		{
			return'
			<div class="alert alert-warning" role="alert">
                <p class="text-center mb-0">
                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                    No hemos encontrado ningún cliente en el sistema que coincida con <strong>“'.$enf.'”</strong>
                </p>
            </div>';

            exit();
		}

	}#end function BuscarPaciente


	public function AgregarEnfermedadesController()
	{
		$id=MainModel::CleanString($_POST['id_agregar_enf']);
		
		$CheckEnfermedad=MainModel::ConsultaSimple("SELECT * from enfermedades WHERE enf_id='$id'");

		if ($CheckEnfermedad->rowCount()<=0) 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No Hemos encontrado esa enfermedad en la Base de Datos",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();			
		}
		else
		{
			$camp=$CheckEnfermedad->fetch();
		}

		session_start(['name'=>'SPM']);

		if (empty($_SESSION['data_enf'] [$id])) 
		{
			$_SESSION['data_enf'] [$id]=[
				"id"=>$camp['enf_id'],
				"enom"=>$camp['enf_nom']
			];


			$alerta=
			[
				"Alerta"=>"recargar",
				"Titulo"=>"Operacion Exitosa",
				"Texto"=>"Enfermedad Agregada",
				"Tipo"=>"success"
			];
			echo json_encode($alerta);
			exit();	

		}
		else
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No se ha podido agregar la enfermedad",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();	

		}

	}


	public function RemoverEnfermedadesController()
	{
		$id=MainModel::CleanString($_POST['id_eliminar_pat']);
		session_start(['name'=>'SPM']);

		unset($_SESSION['data_enf'][$id]);

		if (empty($_SESSION['data_enf'][$id])) 
		{
			$alerta=
			[
				"Alerta"=>"recargar",
				"Titulo"=>"Medicamento Removido",
				"Texto"=>"El Medicamento Ha sido removido de la Receta",
				"Tipo"=>"success"
			];
			echo json_encode($alerta);
			exit();	

		}
		else
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No se ha podido remover el medicamento",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();	
		}
	}

}#end class PacienteController
