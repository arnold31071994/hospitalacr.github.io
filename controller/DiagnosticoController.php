<?php 
	if ($PeticionAjax) 
	{
		require_once '../models/DiagnosticoModel.php';
	}
	else
	{
		require_once './models/DiagnosticoModel.php';
	}

/**
 * 
 */
class DiagnosticoController extends DiagnosticoModel
{
	public function AgregarDiagnosticoController()
	{
		$des=strtoupper(MainModel::CleanString($_POST['diagnostico_des_reg']));


		if($des=="")
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Por Favor Digite El diagnostico que quiere agregar",
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

	

		if(mainModel::VerificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,50}",$des))
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



		$check_nom_dia=MainModel::ConsultaSimple("SELECT * FROM diagnostico WHERE des_dia='$des'");
	

		if ($check_nom_dia->rowCount()>0) 
		{
			$a=$check_nom_dia->fetch();
		
			$alerta=
			[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"Ya existe un Diagnostico llamado ".$a['des_dia'],
				"Tipo"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}#endif

		//$desc=strtoupper($des);
		$Datosdiag=['des'=>$des];

		$agregar_diagnostico=DiagnosticoModel::AgregarDiagnosticoModel($Datosdiag);

		if ($agregar_diagnostico->rowCount()==1) 
		{
			$alerta=
				[
					"Alerta"=>"limpiar",
					"Titulo"=>"Diagnostico Registrado",
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


//*--------- Fin Funtion ---------*/	


}
