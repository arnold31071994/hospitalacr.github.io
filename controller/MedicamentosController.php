<?php 
if ($PeticionAjax) 
{
	require_once '../models/MedicamentosModel.php';
}
else
{
	require_once './models/MedicamentosModel.php';
}


/**
 * 
 */
class MedicamentosController extends MedicamentosModel
{
	public function BuscarMedicamentosController()
	{
		$med=MainModel::CleanString($_POST['input_medicamento_bus']);

		if ($med=="") 
		{
			return'
			<div class="alert alert-warning" role="alert">
                <p class="text-center mb-0">
                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                    No hemos encontrado ningún medicamento en el sistema que coincida con <strong>“Busqueda”</strong>
                </p>
            </div>';

            exit();
		}

		$DatosMedicamento=MainModel::ConsultaSimple("SELECT * from medicamentos WHERE item_des like '%$med%' OR item_nom like '%$med%' OR item_dosis like '%$med%' OR item_tipo like '%$med%' ORDER BY item_nom ASC");

		if ($DatosMedicamento->rowCount()>=1) 
		{
			$DatosMedicamento=$DatosMedicamento->fetchall();

			$table='<div class="table-responsive">
			<table class="table table-hover table-bordered table-sm"> <tbody>';

			foreach($DatosMedicamento as $rows)
			{
				$i=$rows["item_id"];
				$table.='<tr class="text-center">
                            <td>'.$rows['item_cod'].' - '.$rows['item_nom'].' '.$rows['item_dosis'].' '.$rows['item_tipo'].'</td>
                            <td>
                                <button type="button" class="btn btn-primary" onclick="agregar_med('.$i.')"><i class="fas fa-box-open"></i></button>
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
                    No hemos encontrado ningún cliente en el sistema que coincida con <strong>“'.$med.'”</strong>
                </p>
            </div>';

            exit();
		}

		
	}#end function	
	

	public function CrearMedicamentosController()
	{
		
		$nom=MainModel::CleanString($_POST['med_nombre_reg']);
		$dos=MainModel::CleanString($_POST['med_dosis_reg']);
		$tipo=MainModel::CleanString($_POST['med_tipo_reg']);
		$detalle=MainModel::CleanString($_POST['med_detalle_reg']);

		$lastitem=MainModel::ConsultaSimple("SELECT  item_id FROM medicamentos");
		
		if ($lastitem->rowCount()>=1) 
		{
			$codigo=$lastitem->rowCount()+1;
		}
		else
		{
			$codigo='1';
		}

		$nome=substr($tipo, 0, 3);
		//$codigo=$lastitem->fetch();
		$codigome=MainModel::codigo($nome,$codigo,7);
		
		
	

		if($nom=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado ".$codigome,
				"Texto"=>"No has llenado todos los campos que son obligatorios",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		if($tipo=="")
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

		$check_vet=MainModel::ConsultaSimple("SELECT * FROM medicamentos WHERE item_nom='$nom' AND item_dosis='$dos' AND item_tipo='$tipo'");

		if ($check_vet->rowCount()>0) 
		{
			$a=$check_vet->fetch();
		
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Este Medicamento ya Existe en el Sistema",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif		

		

		if(MainModel::VerificarDatos("[0-9-mlgr]{1,9}",$dos)) 
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


		$DatosMedicamento=[
		'nombre'=>$nom,
		'dosis'=>$dos,
		'tipo'=>$tipo,
		'detalle'=>$detalle,
		'cod'=>$codigome];

		$agregar_medicamentos=MedicamentosModel::AgregarMedicamentosModel($DatosMedicamento);
		$s=$agregar_medicamentos->errorInfo();
		if ($agregar_medicamentos->rowCount()==1) 
		{ 
			$alerta=
				[
					"Alerta"=>"limpiar",
					"Titulo"=>"Medicamentos Registrado",
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
					"Texto"=>"No se ha podido registrar el Medicamento".$s[2],
					"Tipo"=>"error"
				];
			#echo json_encode($alerta);
				
		}
		echo json_encode($alerta);


	}#end funcion CrearInvitedController



	public function PageMedicamentosController($pag,$registro,$pri,$url,$search,$id)
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
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from medicamentos WHERE item_des like '%$search%' OR item_nom like '%$search%' OR item_tipo like '%$search%' ORDER BY item_nom ASC LIMIT $start,$registro";
		} 
		else 
		{
			$consulta="SELECT SQL_CALC_FOUND_ROWS * from medicamentos ORDER BY item_nom ASC LIMIT $start,$registro";#AND id_usuario!='1'
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
						<th>CODIGO</th>
						<th>MEDICAMENTO</th>
						<th>DOSIS</th>
						<th>INFO</th>
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
		/*			if ($row["cedula"]=="") 
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
					}*/
					$tabla.='<tr class="text-center" >
						<td>'.$contador.'</td>
						<td>'.$row["item_cod"].'</td>
						<td>'.$row["item_nom"].' '.$row["item_tipo"].'</td>					
						<td>'.$row["item_dosis"].'</td>
						<td>
                        	<button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="Detalle Medicamento" data-content="'.$row["item_des"].' ">
                            <i class="fas fa-info-circle"></i>
                        	</button>
                    	</td>

						<td>
							<a href="'.SERVERURL.'user-update/'.MainModel::Encryption($row["item_id"]).'/" class="btn btn-success">
									<i class="fas fa-sync-alt"></i>	
							</a>
						</td>
						<td>
							<form   class="FormularioAjax" action="'. SERVERURL.'ajax/UsuarioAjax.php" method="POST" data-form="delete" autocomplete="off">
								<input type="hidden" name="user_id_del" value="'.MainModel::Encryption($row["item_id"]).'">
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

	}/*--------- Fin Funtion PageMedicamentosController ---------*/



		public function AgregarMedicamentoRecetaController()
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
		$detalle=MainModel::CleanString($_POST['detalle_detalle_reg']);


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
				"cod"=>$datos['item_cod'],
				"nom"=>$datos['item_nom'],
				"tipo"=>$datos['item_tipo'],
				"dosis"=>$datos['item_dosis'],
				"detalle"=>$detalle,
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


	public function RemoverMedicamentoRecetaController()
	{
		$id=MainModel::CleanString($_POST['id_eliminar_med']);
		session_start(['name'=>'SPM']);

		unset($_SESSION['datos_med'][$id]);

		if (empty($_SESSION['datos_med'][$id])) 
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


}