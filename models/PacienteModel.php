<?php 

require_once 'MainModel.php';

/**
 * 
 */
class PacienteModel extends MainModel
{
	
	protected static function AgregarPacienteModel($datos)
	{
		$sql=MainModel::Conectar()->prepare("INSERT INTO pacientes (pac_ced, pac_nom, pac_ape, pac_tel, pac_dir, pac_sex, pac_email, pac_seg, pac_num_seg, pac_fecnac) VALUES(:cedula, :nombre, :apellido, :telefono, :direccion,:sexo,:email,:seguro,:carnet,:fecn)");

		
		$sql2=MainModel::Conectar()->prepare("INSERT INTO seguro_paciente (pac_ced,segpac_carnet,seg_id) VALUES(:cedula,:carnet,:seguro)");
				
		$sql3=MainModel::Conectar()->prepare("UPDATE pacientes p SET seg_idpac =(SELECT segpac_id s FROM seguro_paciente WHERE pac_ced= :cedula)  WHERE pac_ced= :cedula");

		$sql->bindParam(":cedula",	   $datos["cedula"], PDO::PARAM_STR);
		$sql2->bindParam(":cedula",	   $datos["cedula"], PDO::PARAM_STR);
		$sql3->bindParam(":cedula",	   $datos["cedula"], PDO::PARAM_STR);
		$sql->bindParam(":nombre",	   $datos["nombre"] ,PDO::PARAM_STR);
		$sql->bindParam(":apellido",   $datos["apellido"], PDO::PARAM_STR);
		$sql->bindParam(":telefono",   $datos["telefono"], PDO::PARAM_STR);
		$sql->bindParam(":direccion",  $datos["direccion"], PDO::PARAM_STR);
		$sql->bindParam(":sexo", 	   $datos["sexo"], PDO::PARAM_STR);
		$sql->bindParam(":email", 	   $datos["email"], PDO::PARAM_STR);
		$sql->bindParam(":seguro",     $datos["seguro"], PDO::PARAM_STR);
		$sql->bindParam(":carnet",     $datos["carnet"], PDO::PARAM_STR);
		$sql->bindParam(":fecn",     $datos["fecn"], PDO::PARAM_STR);
		$sql2->bindParam(":seguro",     $datos["seguro"], PDO::PARAM_STR);
		$sql2->bindParam(":carnet",     $datos["carnet"], PDO::PARAM_STR);
		$sql->execute();
		$sql2->execute();
		$sql3->execute();




		return $sql;
	}


	protected static function BuscarUsuariosModel($datos)
	{					//ConectarJCE
		$sql=MainModel::Conectar()->prepare("SELECT CEDULA_COMPLETA,NOMBRES, APELLIDO1, APELLIDO2, CALLE,TELEFONO FROM cedulados WHERE CEDULA_COMPLETA IN(:cedula) OR (NUM_PASAPO IN(:cedula))");

		$sql->bindParam(":cedula", $datos, PDO::PARAM_STR);		
		#$sql->bindParam(":cedula", $datos["cedula"], PDO::PARAM_STR);	#(en caso de usar array)	
		$sql->execute();
		return $sql->fetchall();
	}

	protected static function BuscarPacienteCreadoModel($datos)
	{					//Conectar
		$sql=MainModel::Conectar()->prepare("SELECT * FROM pacientes WHERE pac_ced=:cedula");

		$sql->bindParam(":cedula", $datos, PDO::PARAM_STR);		
		#$sql->bindParam(":cedula", $datos["cedula"], PDO::PARAM_STR);	#(en caso de usar array)	
		$sql->execute();
		return $sql;
	}

/*	protected static function EliminarUsuarioModel($id,$estado)
	{
		$sql=MainModel::Conectar()->prepare("UPDATE usuario SET estado=:estado WHERE id_usuario = :id" );

		$sql->bindParam(":id",$id, PDO::PARAM_INT);
		$sql->bindParam(":estado",$estado, PDO::PARAM_STR);
		$sql->execute();
		return $sql;
	}*/

	protected static function DataPacienteModel($type,$id)
	{
		if ($type=="unic") 
		{
			$sql=MainModel::Conectar()->prepare("SELECT * FROM pacientes WHERE pac_id = :id ");
			$sql->bindParam(":id",$id, PDO::PARAM_INT);				# code...
		}
		elseif ($type=="count") 
		{
			$sql=MainModel::Conectar()->prepare("SELECT * FROM pacientes");

		}

		$sql->execute();
		return $sql;
	}		# code...
	


}