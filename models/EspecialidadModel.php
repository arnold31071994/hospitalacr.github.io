<?php 
	require_once 'MainModel.php';

	/**
	 * 
	 */
	class EspecialidadModel extends MainModel
	{
		
		protected static function GetEspecialidadModel()
		{

	 		$sql="SELECT * FROM ffEspecialidad";
	 		$stmt= MainModel::ConectarSQL()->prepare($sql);
	 		#$stmt->bindParam(":id", $id, PDO::PARAM_STR);

	 		$stmt->execute();
	 		return $stmt->fetchall();
	 	

		# code...
		}
	}