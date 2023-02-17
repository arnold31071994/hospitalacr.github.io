<?php 
if ($PeticionAjax) 
{
	require_once '../models/UsuarioModel.php';
}
else
{
	require_once './models/UsuarioModel.php';
}

/**
 * 
 */
class UsuarioController extends UsuarioModel
{
	/*--------- Controlador agregar usuario ---------*/
	public function AgregarUsuarioController()
	{
		#Limpia la Variable para que no haya inyeccion SQL
		$ced=MainModel::CleanString($_POST['usuario_dni_reg']);
		$nom=MainModel::CleanString($_POST['usuario_nombre_reg']);
		$ape=MainModel::CleanString($_POST['usuario_apellido_reg']);
		$tel=MainModel::CleanString($_POST['usuario_telefono_reg']);
		$dir=MainModel::CleanString($_POST['usuario_direccion_reg']);
		$user=MainModel::CleanString($_POST['usuario_usuario_reg']);
		$mail=MainModel::CleanString($_POST['usuario_email_reg']);
		$pass=MainModel::CleanString($_POST['usuario_clave_1_reg']);
		$pass2=MainModel::CleanString($_POST['usuario_clave_2_reg']);
		$villa=MainModel::CleanString($_POST['usuario_villa_reg']);
		$villa=MainModel::Decryption($villa);
		$privilegio=MainModel::CleanString($_POST['usuario_privilegio_reg']);/**/
		$privilegio=MainModel::Decryption($privilegio);


		$respuesta=UsuarioModel::BuscarUsuariosCreadoModel($ced);

	 /*
		$f=$_FILES['usuario_foto_reg']['error'];
		$itype=$_FILES['usuario_foto_reg']['type'];
		$isize=$_FILES['usuario_foto_reg']['size'];
		$pic=$_FILES['usuario_foto_reg']['name'];
		$itemp=$_FILES['usuario_foto_reg']['temp_name'];*/

	 /*	$rev_img=getimagesize($_FILES['usuario_foto_reg']['tmp_name']);
		
		if ($rev_img !== false) 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado ",
				"Texto"=>"carga una imagen",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}
		else
		{
			$image = $_FILES['usuario_foto_reg']['tmp_name'];
       		$imgContenido = addslashes(file_get_contents($image));			
		}*/

		
		if ($respuesta->rowCount()==1) 
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

		$check_vet=MainModel::ConsultaSimple("SELECT * FROM usuario WHERE email='$mail'");

		if ($check_vet->rowCount()>0) 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Este correo ya esta registrado",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif
		
		if($ced=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor Digite Una Cedula",
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
				"Texto"=>"Por Favor Digite el Nombre",
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
				"Texto"=>"Por Favor Digite el Apellido",
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
				"Texto"=>"Por Favor Digite Una Direccion",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}
		if($user=="")
		{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Por Favor Digite Un Nombre de usuario",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}
		if($mail=="")
		{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Por Favor Digite Un correo electronico",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}

		if($_FILES['usuario_foto_reg']['size']>=300000)
		{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"La imagen debe pesar menos de 3KB",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}
		else
		{
			//mover la imagen
			$folder= $_SERVER['DOCUMENT_ROOT'].'/arnold/view/assets/avatar/';
			$imgContenido = $_FILES['usuario_foto_reg']['tmp_name'];
			$orig_name= $_FILES['usuario_foto_reg']['name'];
			$array_name = explode('.',$orig_name);
			$count_array_name= count($array_name);
			$ext = strtolower($array_name[--$count_array_name]);
			$new_name = time().'_'.rand(0,100).'.'.$ext;
			$imgC = $folder.$new_name;

			move_uploaded_file($imgContenido,$imgC);
       /*
			$imagecon = $_FILES['usuario_foto_reg']['tmp_name'];
       		$imgC = file_get_contents($imagecon);*/
		}
		if($_FILES['usuario_foto_reg']['error']>0)
		{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Debes Introducir una imagen",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}

		if($pass=="")
		{
				$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"nombre temporal: ".$_FILES['usuario_foto_reg']['tmp_name']." Contraseña
							error: ".$_FILES['usuario_foto_reg']['error']."
							tamaño: ".$_FILES['usuario_foto_reg']['size']."
							tipo:  ".$_SERVER['DOCUMENT_ROOT']."
							ruta: ".$new_name."	",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}
		if($pass2=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor Confirme la Contraseña",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}			
		#comprueba si la varible cumple con los paramentros correctos
		if(MainModel::VerificarDatos("[0-9-]{10,20}",$ced)) 
		{
			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"No ha Intruducido Datos Validos",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}
		if ($pass!=$pass2) 
		{
			$alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Las Contraseñas no coinciden",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();			# code...
		}
		else
		{
			$passe=MainModel::Encryption($pass);
		}

		$DatosUser=['cedula'=>$ced,
		'nombre'=>$nom,
		'apellido'=>$ape,
		'usuario'=>$user,
		'telefono'=>$tel,
		'direccion'=>$dir,		
		'email'=>$mail,
		'pass'=>$passe,
		'villa'=>$villa,
		'privilegio'=>$privilegio,
		'imagen'=>$new_name,
		'estado'=>'activa'];

		$agregar_usuario=UsuarioModel::AgregarUsuariosModel($DatosUser);

		if ($agregar_usuario->rowCount()==1) 
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
	}/*--------- Fin Funtion ---------*/

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

	public function ActualizarUsuarioController()
	{
		$ced=MainModel::CleanString($_POST['usuario_dni_up']);
		$nom=MainModel::CleanString($_POST['usuario_nombre_up']);
		$ape=MainModel::CleanString($_POST['usuario_apellido_up']);
		$tel=MainModel::CleanString($_POST['usuario_telefono_up']);
		$dir=MainModel::CleanString($_POST['usuario_direccion_up']);
		$user=MainModel::CleanString($_POST['usuario_usuario_up']);
		$mail=MainModel::CleanString($_POST['usuario_email_up']);
		$pass=MainModel::CleanString($_POST['usuario_clave_1_up']);
		$pass2=MainModel::CleanString($_POST['usuario_clave_2_up']);
		$villa=MainModel::CleanString($_POST['usuario_villa_reg']);
		$privilegio=MainModel::CleanString($_POST['usuario_privilegio_up']);		
	}

	public function EliminarUsuarioController()
	{
		$id=mainModel::Decryption($_POST['user_id_del']);
		$sta=mainModel::Decryption($_POST['user_sta_del']);
		
		$id=MainModel::CleanString($id);
		$sta=MainModel::CleanString($sta);
		if($id=="")
		{
			$alerta=[
				"Alerta"=>"recargar",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Con el ID",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		if ($id==1) 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No puedes borrar el usuario principal",
				"Tipo"=>"error"
			];	
			echo json_encode($alerta);
		}

		if(mainModel::VerificarDatos("[0-9-]{1,12}",$id))
		{
			$alerta=[
				"Alerta"=>"recargar",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Los parametros del usuario tienen problemas",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif
		

		$check_user=MainModel::ConsultaSimple("SELECT estado FROM usuario WHERE id_usuario='$id'");


		if ($check_user->rowCount()<=0) 
		{
			
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"El usuario que intentas borrar no existe en el sistema",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}
		if ($sta=='activa') 
		{
			$delete_user=UsuarioModel::EliminarUsuarioModel($id,'inactiva');
		}
		if ($sta=='inactiva') 
		{
			$delete_user=UsuarioModel::EliminarUsuarioModel($id,'activa');
		}		

		//$delete_user=UsuarioModel::EliminarUsuarioModel($id);

		if ($delete_user->rowCount()==1) 
		{
			$alerta=
			[
				"Alerta"=>"recargar",
				"Titulo"=>"Usuario Desactivado",
				"Texto"=>"El usuario que intentas borrar no existe en el sistema",
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
				"Texto"=>"El usuario no pudo ser borrado",
				"Tipo"=>"error"
			];
			#echo json_encode($alerta);
				
		}
		echo json_encode($alerta);

	}


	public function DataUserController($type,$id)
	{
		$type=mainModel::CleanString($type);
		$id=mainModel::Decryption($id);
		$id=MainModel::CleanString($id);


		return UsuarioModel::DataUserModel($type,$id);
	}		# code...
	
	

	/*--------- Controller Page User ---------*/

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
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from usuario WHERE ((id_usuario!='$id' AND id_usuario!='1')AND (cedula like '%$search%' OR nombre like '%$search%' OR apellido like '%$search%')) ORDER BY nombre ASC LIMIT $start,$registro";
		} 
		else 
		{
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from usuario WHERE id_usuario!='$id' AND id_usuario!='1'  ORDER BY nombre ASC LIMIT $start,$registro";
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
					if ($row["estado"]=="activa")
					 {
						$sta='<td> <span class="badge badge-info">'.$row["estado"].'</span></td>';
						$staicon='<i class="fas fa-times"></i>';
						//fas fa-user-times
					}
					else
					{
						$sta='<td> <span class="badge badge-danger">'.$row["estado"].'</span></td>';
						$staicon='<i class="fas fa-check"></i>';
						//fas fa-user-check
					}
					$tabla.='<tr class="text-center" >
						'.$sta.'
						<td>'.$contador.'</td>
						<td>'.$row["cedula"].'</td>
						<td>'.$row["nombre"].'</td>
						<td>'.$row["apellido"].'</td>
						<td>'.$row["telefono"].'</td>
						<td>'.$row["usuario"].'</td>
						<td>'.$row["email"].'</td>
						<td>
							<a href="'.SERVERURL.'user-update/'.MainModel::Encryption($row["id_usuario"]).'/" class="btn btn-success">
									<i class="fas fa-sync-alt"></i>	
							</a>
						</td>
						<td>
							<form   class="FormularioAjax" action="'. SERVERURL.'ajax/UsuarioAjax.php" method="POST" data-form="delete" autocomplete="off">
								<input type="hidden" name="user_id_del" value="'.MainModel::Encryption($row["id_usuario"]).'">
								<input type="hidden" name="user_sta_del" value="'.MainModel::Encryption($row["estado"]).'">
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

}#endclass