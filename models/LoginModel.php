<?php 
	
require_once 'MainModel.php';

/**
 * 
 */
class LoginModel extends MainModel
{
	
	protected static function IniciarSesionModel($datos)
	{
		#$sql="SELECT * FROM usuario WHERE cedula=:cedula AND pass=:clave";
		$stmt= MainModel::Conectar()->prepare("SELECT * FROM usuario WHERE cedula=:cedula AND pass=:clave");
		#$stmt->bindParam(":cedula", $udatos, PDO::PARAM_STR);
		#$stmt->bindParam(":clave", $pdatos, PDO::PARAM_STR);
		$stmt->bindParam(":cedula", $datos['usuario'], PDO::PARAM_STR);
		$stmt->bindParam(":clave", $datos['pass'], PDO::PARAM_STR);
		$stmt->execute();
	 	return $stmt;
	}
}
