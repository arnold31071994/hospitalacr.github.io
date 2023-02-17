<?php 
	require_once 'MainModel.php';

	/**
	 * 
	 */
	class DiagnosticoModel extends MainModel
	{
		

		protected static function AgregarDiagnosticoModel($datos)
		{

		$sql=MainModel::Conectar()->prepare("INSERT INTO diagnostico (des_dia) VALUES (:dia)");

		$sql->bindParam(":dia",	   $datos["des"], PDO::PARAM_STR);
		$sql->execute();
		return $sql;	 	
		# code...
		}/*fin function */
		
	
	}