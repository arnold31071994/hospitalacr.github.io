<?php 
	require_once 'MainModel.php';

	/**
	 * 
	 */
	class MedicamentosModel extends MainModel
	{
		
		protected static function GetMedicamentosModel()
		{

	 		$sql="SELECT seg_id,seg_nom FROM seguros WHERE seg_est=1";
	 		$stmt= MainModel::Conectar()->prepare($sql);
	 		//$stmt->bindParam(":esp", $especialidad, PDO::PARAM_STR);

	 		$stmt->execute();
	 		return $stmt->fetchall();
	 	

		# code...
		}
		protected static function AgregarMedicamentosModel($datos)
		{

			$sql=MainModel::Conectar()->prepare("INSERT INTO medicamentos (item_nom, item_des, item_tipo, item_dosis,item_cod) VALUES(:nom, :detalle, :tipo, :dosis, :cod)");

			$sql->bindParam(":cod",  	  $datos["cod"], PDO::PARAM_STR);
			$sql->bindParam(":nom",	   	  $datos["nombre"], PDO::PARAM_STR);
			$sql->bindParam(":tipo",   	  $datos["tipo"], PDO::PARAM_STR);
			$sql->bindParam(":dosis",  	  $datos["dosis"], PDO::PARAM_STR);
			$sql->bindParam(":detalle",   $datos["detalle"], PDO::PARAM_STR);
			$sql->execute();
			return $sql;	 	

			# code...
		}/*fin function */
		protected static function DataMedicamentosModel($type)
		{
			if ($type=="unic") 
			{
				$sql=MainModel::Conectar()->prepare("SELECT * FROM medicamentos WHERE item_id = :id ");
				$sql->bindParam(":id",$id, PDO::PARAM_INT);				# code...
			}
			elseif ($type=="count") 
			{
				$sql=MainModel::Conectar()->prepare("SELECT * FROM medicamentos");

			}

			$sql->execute();
			return $sql;
		}		# code...
	
	}