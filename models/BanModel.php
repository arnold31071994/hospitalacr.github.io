<?php 

require_once 'MainModel.php';


/**
 * 
 */
class BanModel extends MainModel
{
	protected static function BuscarBanModel($datos)
	{					//ConectarJCE
		$sql=MainModel::Conectar()->prepare("SELECT CEDULA_COMPLETA,NOMBRES, APELLIDO1, APELLIDO2, CALLE,TELEFONO FROM cedulados WHERE CEDULA_COMPLETA IN(:cedula) OR (NUM_PASAPO IN(:cedula))");

		$sql->bindParam(":cedula", $datos, PDO::PARAM_STR);		
		#$sql->bindParam(":cedula", $datos["cedula"], PDO::PARAM_STR);	#(en caso de usar array)	
		$sql->execute();
		return $sql->fetchall();
	}

	protected static function BuscarInvitedCreadoModel($datos)
	{					//Conectar
		$sql=MainModel::Conectar()->prepare("SELECT * FROM usuario WHERE cedula=:cedula");

		$sql->bindParam(":cedula", $datos, PDO::PARAM_STR);		
		#$sql->bindParam(":cedula", $datos["cedula"], PDO::PARAM_STR);	#(en caso de usar array)	
		$sql->execute();
		return $sql;
	}	

	protected static function AutorizarInvitedModel($datosaut)
	{
		$sql=MainModel::Conectar()->prepare("INSERT INTO autorizaciones(id_usuario, aut_cod, estado, fecha_c_aut) VALUES(:usuario, :codaut, :estado, (SELECT CURRENT_TIMESTAMP()))");

		$sql->bindParam(":usuario", $datosaut["usuario"], PDO::PARAM_STR);
		$sql->bindParam(":codaut", $datosaut["codaut"], PDO::PARAM_STR);
		$sql->bindParam(":estado", $datosaut["estado"], PDO::PARAM_STR);
		#$sql->bindParam(":cedula", $datosaut, PDO::PARAM_STR);		
		#$sql->bindParam(":cedula", $datosaut["cedula"], PDO::PARAM_STR);	#(en caso de usar array)	
		$sql->execute();
		return $sql;	
	}

	protected static function AgregarBanModel($datos)
	{
		$sql=MainModel::Conectar()->prepare
		("INSERT INTO vetados(ban_ced, ban_nom, ban_ape, ban_nota,ban_tel ,ban_fecha,id_usuario) 
			VALUES(:cedula, :nombre, :apellido, :comentario,:telefono, (SELECT CURRENT_TIMESTAMP()),:id)");

		$sql->bindParam(":cedula",	  	 $datos["cedula"], PDO::PARAM_STR);
		$sql->bindParam(":nombre",	  	 $datos["nombre"] ,PDO::PARAM_STR);
		$sql->bindParam(":apellido",  	 $datos["apellido"], PDO::PARAM_STR);
		$sql->bindParam(":telefono",  	 $datos["telefono"], PDO::PARAM_STR);
		$sql->bindParam(":comentario",	 $datos["comentario"], PDO::PARAM_STR);
		$sql->bindParam(":id",			 $datos["iduser"], PDO::PARAM_INT);
		$sql->execute();
		
		return $sql;
		
	}

	protected static function DataBanModel($type,$id)
	{
		if ($type=="unic") 
		{
			$sql=MainModel::Conectar()->prepare("SELECT * FROM vetados WHERE id_usuario = :id ");
			$sql->bindParam(":id",$id, PDO::PARAM_INT);				# code...
		}
		elseif ($type=="count") 
		{
			$sql=MainModel::Conectar()->prepare("SELECT SQL_CALC_FOUND_ROWS * from vetados");

		}
		elseif ($type=="list") 
		{
			$sql=MainModel::Conectar()->prepare("SELECT  * from vetados");

		}
		


		$sql->execute();
		return $sql;
	}		# code...
	
	
}#endclass Invited