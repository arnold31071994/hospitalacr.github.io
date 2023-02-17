<?php 
	require_once 'MainModel.php';

	/**
	 * 
	 */
	class CardModel extends MainModel
	{
		
		protected static function GetCardModel()
		{

	 		$sql="SELECT * FROM menu WHERE parent_id=0 AND ( titulo!='LOGUIN' AND titulo != 'INICIO')";
	 		$stmt= MainModel::Conectar()->prepare($sql);
	 		#$stmt->bindParam(":id", $id, PDO::PARAM_STR);

	 		$stmt->execute();
	 		return $stmt->fetchall();
	 	

		# code...
		}

		protected static function GetSubCardModel($privilegios)
		{
			
			$sql="SELECT * FROM menu WHERE parent_id>0 AND (privilegios<=:pri)";
			//$sql="SELECT * FROM menu WHERE parent_id>0 AND (privilegios<=:pri) ORDER BY parent_id";

			$stmt= MainModel::Conectar()->prepare($sql);
			$stmt->bindParam(":pri", $privilegios, PDO::PARAM_INT);
			#$sql->bindParam(":parent_id", $id,PDO::PARAM_INT);	# code...		
			
			$stmt->execute();
			return $stmt->fetchall();
			
		}



		protected static function DataCardModel($type,$id)
		{
			if ($type=="unic") 
			{
				$sql=MainModel::Conectar()->prepare("SELECT * FROM menu WHERE id = :id ");
				$sql->bindParam(":id",$id, PDO::PARAM_INT);				# code...
			}
			elseif ($type=="count") 
			{
				$sql=MainModel::Conectar()->prepare("SELECT * FROM usuario WHERE parent_id =:id");

			}

			$sql->execute();
			return $sql;
		}#endfunction	
	}