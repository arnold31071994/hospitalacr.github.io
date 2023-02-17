<?php 
if ($PeticionAjax) 
{
	require_once '../models/RecetaModel.php';
}
else
{
	require_once './models/RecetaModel.php';
}


/**
 * 
 */
class RecetaController extends RecetaModel
{
	public function CrearRecetaController()
	{

		session_start(['name'=>'SPM']);

		//verificar si hay medicamentos cargados
		if (empty($_SESSION['datos_med'])) 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No ha seleccionado Ningun Medicamento Para la receta",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}
		//verificar si hay paciente cargados
		if (empty($_SESSION['data_pac'])) 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No ha seleccionado Ningun paciente Para la receta",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}


		$fecha=MainModel::CleanString($_POST['receta_fecha_reg']);
		$observacion=MainModel::CleanString($_POST['receta_obv_reg']);

		if ($fecha!=date("Y-m-d")) 
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"La fecha de Registro no es la de Hoy",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}



		/*if(MainModel::VerificarFecha($fecha)) 
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"La fecha no tiene el Formato Valido ".$fecha,
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}*/


		/*Generar Codigo de receta*/


		$relacion=MainModel::ConsultaSimple("SELECT  receta_id as nuevo FROM receta");
		
		if ($relacion->rowCount()>=1) 
		{
			$rec=$relacion->rowCount()+1;
		}
		else
		{
			$rec='1';
		}

		//$relacion=MainModel::ConsultaSimple("SELECT receta_id FROM receta");
		//$relacion=($relacion->rowCount())+1;
		$codigo=MainModel::codigo('RE',$rec,6);


		$chec_cod=MainModel::ConsultaSimple("SELECT  receta_id as nuevo FROM receta WHERE receta_cod='$codigo'");
		if ($chec_cod->rowCount()>=1) 
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Este Codigo de Receta ya esta en uso",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();			
		}

		$DatosReceta=
		["cod"=>$codigo,
		 "fecha"=>$fecha,
		 "obv"=>$observacion,
		 "cant"=>count($_SESSION['datos_med']),
		 "pac"=>$_SESSION['data_pac']['id'],
		 "user"=>$_SESSION['id_spm']
		];

		$Agregar_receta=RecetaModel::AgregaRecetaModel($DatosReceta);

		if ($Agregar_receta->rowCount()!=1) 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No Se ha Creado la Receta",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}
		/*else
		{

		$error_medicamentos=0;
		}*/

		$error_medicamentos=0;

		foreach ($_SESSION['datos_med'] as $item) 
		{
			$detalle_receta=
			[
				"nom"=>$item['nom'],
				"tipo"=>$item['tipo'],
				"dos"=>$item['dosis'],
				"cant"=>$item['cant'],
				"detalle"=>$item['detalle'],
				"dias"=>$item['dias'],
				"itemcod"=>$item['cod'],				
				"codi"=>$codigo
			];




			$agregar_item_receta=RecetaModel::AgregaRecetaDetalleModel($detalle_receta);

			if($agregar_item_receta->rowCount()!=1) 
			{
				$error_medicamentos=1;
				break;
			}
		}/*end foreach para agreagar items de la receta*/


		if ($error_medicamentos==0) 
		{
			unset($_SESSION['datos_med']);
			unset($_SESSION['data_pac']);
			$alerta=
			[
				"Alerta"=>"recargar",
				"Titulo"=>"Receta registrada",
				"Texto"=>"Se ha registrado la receta en el sistema",
				"Tipo"=>"success"
			];
			echo json_encode($alerta);
			exit();			
			
		}
		else
		{
			RecetaModel::EliminarRecetaModel($codigo,"detalle");
			RecetaModel::EliminarRecetaModel($codigo,"receta");

			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado ",
				"Texto"=>"No se ha registrado la receta (Error:002), Intente de nuevo ",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

				

	}/*end function crear receta*/


	public function AgregarRecetaController()
	{
		
		$id=MainModel::CleanString($_POST['id_agregar_med']);

		$ChekMed=MainModel::ConsultaSimple("SELECT * from medicamentos WHERE item_id='$id'");
		

		if ($ChekMed->rowCount()<=0) 
		{
					
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No se ha podido Seleccionar el Medicamento",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}
		else
		{
			$datos=$ChekMed->fetch();
		}
		$cantidad=MainModel::CleanString($_POST['detalle_cantidad_reg']);
		$dias=MainModel::CleanString($_POST['detalle_dias_reg']);


		if ($cantidad=="" || $dias=="") 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"No Ha llenado todos los Campos requeridoss",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		if(MainModel::VerificarDatos("[0-9-]{1}",$cantidad)) 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"EL FORMATO DE LA CANTIDAD NO ES VALIDOS",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		if(MainModel::VerificarDatos("[0-9-]{2}",$dias)) 
		{
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"EL FORMATO DE LOS DIAS NO ES VALIDOS",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		session_start(['name'=>'SPM']);

		if (empty($_SESSION['datos_med'] [$id])) 
		{
			$_SESSION['datos_med'] [$id]=
			[
				"id"=>$datos['item_id'],
				"nom"=>$datos['item_nom'],
				"tipo"=>$datos['item_tipo'],
				"dosis"=>$datos['item_dosis'],
				"cant"=>$cantidad,
				"dias"=>$dias
			];



			$alerta=
			[
				"Alerta"=>"recargar",
				"Titulo"=>"medicamento Agregado",
				"Texto"=>"se agrego el medicamento ha la receta",
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
				"Texto"=>"El medicamento ya esta en la Receta",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}
		
	}#end funcion CrearRecetaController


	public function DataRecetaController($type,$id)
	{
		$type=mainModel::CleanString($type);
		$id=mainModel::Decryption($id);
		$id=MainModel::CleanString($id);


		return RecetaModel::DataRecetaModel($type,$id);
	}#ENDFUNCTION



	public function PageRecetaController($pag,$registro,$pri,$url,$search,$id)
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
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from receta r inner join pacientes p on r.paciente_id = p.pac_id where (p.pac_ced like '%$search%' OR p.pac_nom like '%$search%' OR p.pac_ape like '%$search%') ORDER BY p.pac_nom ASC LIMIT $start,$registro";
		} 
		else 
		{
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from receta r inner join pacientes p on r.paciente_id = p.pac_id ORDER BY p.pac_nom ASC LIMIT $start,$registro";
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
						<th>IMPRIMIR</th>
						<th>RECETA</th>
						<th>FECHA</th>
					</tr>
				</thead>
				<tbody>';
			if ($total>=1 && $pag<=$Npag)
			{
				$contador=$start+1;
				$reg_start=$start+1;
				foreach ($datos as $row ) 
				{
				/*	if ($row["estado"]=="activa")
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
					}*/
					$tabla.='<tr class="text-center" >
						<td>'.$contador.'</td>
						<td>'.$row["pac_ced"].'</td>
						<td>'.$row["pac_nom"].'</td>
						<td>'.$row["pac_ape"].'</td>
						<td>
							<a href="'. SERVERURL.'reports/inv.php?mod='.MainModel::Encryption($row["receta_id"]).'" target="_blank" class="btn btn-info">
								<i class="fas fa-file-pdf"></i>	
							</a>
						</td>
						<td>'.$row["receta_cod"].'</td>
						<td>'.$row["receta_fecha"].'</td>
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

}#endclass recetacontroller