<?php 
	require_once 'MainModel.php';

	/**
	 * modificar
	 */
	class ProfesionModel extends MainModel
	{
		
		protected static function GetProfesionModel()
		{

	 		$sql="SELECT profesion_id ,profesion_nom FROM profesiones";
	 		$stmt= MainModel::Conectar()->prepare($sql);
	 		//$stmt->bindParam(":esp", $especialidad, PDO::PARAM_STR);

	 		$stmt->execute();
	 		return $stmt->fetchall();
	 	

		
		}
		protected static function AgregarProfesionModel($datos)
		{

			$sql=MainModel::Conectar()->prepare("INSERT INTO Profesion (nom_Profesion, dir_Profesion, num_Profesion, est_Profesion, pre_Profesion) VALUES (:nom, :dir, :ndir,:sta ,:p)");

			$sql->bindParam(":nom",	   $datos["nom"], PDO::PARAM_STR);
			$sql->bindParam(":dir",	   $datos["dir"] ,PDO::PARAM_STR);
			$sql->bindParam(":ndir",   $datos["num_dir"], PDO::PARAM_INT);
			$sql->bindParam(":sta",    $datos["estado"], PDO::PARAM_INT);
			$sql->bindParam(":p",    $datos["p"], PDO::PARAM_INT);
			$sql->execute();
			return $sql;	 	

		# code...
		}/*fin function */
		protected static function DataProfesionModel($type)
		{
			if ($type=="unic") 
			{
				$sql=MainModel::Conectar()->prepare("SELECT * FROM usuario WHERE id_usuario = :id ");
				$sql->bindParam(":id",$id, PDO::PARAM_INT);				# code...
			}
			elseif ($type=="count") 
			{
				$sql=MainModel::Conectar()->prepare("SELECT * FROM Profesiones");

			}

			$sql->execute();
			return $sql;
		}		# code...
	
	}