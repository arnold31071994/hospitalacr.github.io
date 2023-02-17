<?php 

require_once 'MainModel.php';

/**
 * 
 */
class UsuarioModel extends MainModel
{
	
	protected static function AgregarUsuariosModel($datos)
	{
		$sql=MainModel::Conectar()->prepare("INSERT INTO usuario (cedula, nombre, apellido, pass, email, usuario,telefono,direccion,id_villa,privilegio,estado,imagen) VALUES(:cedula, :nombre, :apellido, :pass, :email,:usuario,:telefono,:direccion,:villa,:privilegio,:estado,:imagen)");

		$sql->bindParam(":cedula",	   $datos["cedula"], PDO::PARAM_STR);
		$sql->bindParam(":nombre",	   $datos["nombre"] ,PDO::PARAM_STR);
		$sql->bindParam(":apellido",   $datos["apellido"], PDO::PARAM_STR);
		$sql->bindParam(":pass", 	   $datos["pass"], PDO::PARAM_STR);
		$sql->bindParam(":email", 	   $datos["email"], PDO::PARAM_STR);
		$sql->bindParam(":usuario",    $datos["usuario"], PDO::PARAM_STR);
		$sql->bindParam(":telefono",   $datos["telefono"], PDO::PARAM_STR);
		$sql->bindParam(":direccion",  $datos["direccion"], PDO::PARAM_STR);
		$sql->bindParam(":imagen",     $datos["imagen"], PDO::PARAM_STR);
		$sql->bindParam(":villa",	   $datos["villa"], PDO::PARAM_INT);
		$sql->bindParam(":privilegio", $datos["privilegio"], PDO::PARAM_INT);		
		$sql->bindParam(":estado", 	   $datos["estado"], PDO::PARAM_STR);
		$sql->execute();
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

	protected static function BuscarUsuariosCreadoModel($datos)
	{					//Conectar
		$sql=MainModel::Conectar()->prepare("SELECT * FROM usuario WHERE cedula=:cedula");

		$sql->bindParam(":cedula", $datos, PDO::PARAM_STR);		
		#$sql->bindParam(":cedula", $datos["cedula"], PDO::PARAM_STR);	#(en caso de usar array)	
		$sql->execute();
		return $sql;
	}

	protected static function EliminarUsuarioModel($id,$estado)
	{
		$sql=MainModel::Conectar()->prepare("UPDATE usuario SET estado=:estado WHERE id_usuario = :id" );

		$sql->bindParam(":id",$id, PDO::PARAM_INT);
		$sql->bindParam(":estado",$estado, PDO::PARAM_STR);
		$sql->execute();
		return $sql;
	}

	protected static function DataUserModel($type,$id)
	{
		if ($type=="unic") 
		{
			$sql=MainModel::Conectar()->prepare("SELECT * FROM usuario WHERE id_usuario = :id");
			$sql->bindParam(":id", $id, PDO::PARAM_INT);				# code...
		}
		elseif ($type=="count") 
		{
			$sql=MainModel::Conectar()->prepare("SELECT * FROM usuario WHERE id_usuario !=1 AND estado='activa'");

		}

		$sql->execute();
		return $sql;
	}		# code...
	


}