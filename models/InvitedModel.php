<?php 

require_once 'MainModel.php';


/**
 * 
 */
class InvitedModel extends MainModel
{
	protected static function BuscarInvitedModel($datos)
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

	protected static function AgregarInvitedModel($datos)
	{
		$sql=MainModel::Conectar()->prepare
		("INSERT INTO invitados (inv_ced, inv_nom, inv_ape, inv_com, inv_fecha,aut_cod,id_usuario) VALUES(:cedula, :nombre, :apellido, :comentario, :fecha,:autorizacion,:id)");

		$usql=MainModel::Conectar()->prepare("UPDATE autorizaciones SET estado='usado',cedula=:cedula, fecha_u_aut=(SELECT CURRENT_TIMESTAMP()) WHERE aut_cod=:autorizacion");
		$usql->execute();
		$sql->bindParam(":cedula",	  	 $datos["cedula"], PDO::PARAM_STR);
		$sql->bindParam(":nombre",	  	 $datos["nombre"] ,PDO::PARAM_STR);
		$sql->bindParam(":apellido",  	 $datos["apellido"], PDO::PARAM_STR);
		$sql->bindParam(":comentario",	 $datos["comentario"], PDO::PARAM_STR);
		$sql->bindParam(":autorizacion", $datos["autorizacion"], PDO::PARAM_STR);
		$sql->bindParam(":fecha",		 $datos["fecha"], PDO::PARAM_STR);
		$sql->bindParam(":id",			 $datos["iduser"], PDO::PARAM_STR);
		$sql->execute();
		
		return $sql;
		
	}

	protected static function DataInvitedModel($type,$id,$i_fecha,$f_fecha)
	{
		if ($type=="unic") 
		{
			$sql=MainModel::Conectar()->prepare("SELECT * FROM invitados WHERE id_usuario = :id ");
			$sql->bindParam(":id",$id, PDO::PARAM_INT);				# code...
		}
		elseif ($type=="count") 
		{
			$sql=MainModel::Conectar()->prepare("SELECT SQL_CALC_FOUND_ROWS * from invitados WHERE inv_fecha= (SELECT CURRENT_DATE) ORDER BY inv_fecha ");

		}
		elseif ($type=="list") 
		{
			$sql=MainModel::Conectar()->prepare("SELECT * from invitados WHERE inv_fecha>=:i_fecha AND inv_fecha<=:f_fecha ");
			//$sql->bindParam(":id",$id, PDO::PARAM_INT);
			$sql->bindParam(":i_fecha",$i_fecha, PDO::PARAM_STR);	
			$sql->bindParam(":f_fecha",$f_fecha, PDO::PARAM_STR);

		}
		elseif ($type=="fal") 
		{
			$sql=MainModel::Conectar()->prepare("SELECT * from invitados WHERE inv_hora_salida ='' OR inv_hora_salida IS NULL AND inv_fecha>=:i_fecha AND inv_fecha<=:f_fecha ");
			//$sql->bindParam(":id",$id, PDO::PARAM_INT);
			$sql->bindParam(":i_fecha",$i_fecha, PDO::PARAM_STR);	
			$sql->bindParam(":f_fecha",$f_fecha, PDO::PARAM_STR);

		}
		elseif ($type=="aut") 
		{
			$sql=MainModel::Conectar()->prepare("SELECT invitados.inv_nom, invitados.inv_ape, invitados.inv_com, invitados.aut_cod, usuario.nombre,usuario.apellido, invitados.inv_fecha, invitados.inv_hora_ent, invitados.inv_hora_salida,invitados.inv_ced from (invitados INNER JOIN usuario ON usuario.id_usuario=invitados.id_usuario) INNER JOIN autorizaciones on invitados.aut_cod=autorizaciones.aut_cod where autorizaciones.estado='usado' ");
			//$sql->bindParam(":id",$id, PDO::PARAM_INT);
			$sql->bindParam(":i_fecha",$i_fecha, PDO::PARAM_STR);	
			$sql->bindParam(":f_fecha",$f_fecha, PDO::PARAM_STR);

		}
		$sql->execute();
		return $sql;
	}		# code...
	
}#endclass Invited