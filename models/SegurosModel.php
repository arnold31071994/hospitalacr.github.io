<?php 
	require_once 'MainModel.php';

	/**
	 * 
	 */
	class SeguroModel extends MainModel
	{
		
		protected static function GetSeguroModel()
		{

	 		$sql="SELECT seg_id,seg_nom FROM seguros WHERE seg_est=1";
	 		$stmt= MainModel::Conectar()->prepare($sql);
	 		//$stmt->bindParam(":esp", $especialidad, PDO::PARAM_STR);

	 		$stmt->execute();
	 		return $stmt->fetchall();
	 	

		# code...
		}
		protected static function AgregarVillaModel($datos)
		{

		$sql=MainModel::Conectar()->prepare("INSERT INTO villa (nom_villa, dir_villa, num_villa, est_villa, pre_villa) VALUES (:nom, :dir, :ndir,:sta ,:p)");

		$sql->bindParam(":nom",	   $datos["nom"], PDO::PARAM_STR);
		$sql->bindParam(":dir",	   $datos["dir"] ,PDO::PARAM_STR);
		$sql->bindParam(":ndir",   $datos["num_dir"], PDO::PARAM_INT);
		$sql->bindParam(":sta",    $datos["estado"], PDO::PARAM_INT);
		$sql->bindParam(":p",    $datos["p"], PDO::PARAM_INT);
		$sql->execute();
		return $sql;	 	

		# code...
		}/*fin function */
		protected static function DataVillaModel($type)
		{
			if ($type=="unic") 
			{
				$sql=MainModel::Conectar()->prepare("SELECT * FROM usuario WHERE id_usuario = :id ");
				$sql->bindParam(":id",$id, PDO::PARAM_INT);				# code...
			}
			elseif ($type=="count") 
			{
				$sql=MainModel::Conectar()->prepare("SELECT * FROM villa");

			}

			$sql->execute();
			return $sql;
		}		# code...
	
	}