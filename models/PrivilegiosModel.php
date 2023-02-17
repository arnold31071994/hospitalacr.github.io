<?php 
	
	require_once 'MainModel.php';

/**
 * 
 */
class PrivilegiosModel extends MainModel
{


	protected static function GetPrivilegiosModel()
	{
		$sql="SELECT * FROM privilegio";
		$stmt= MainModel::Conectar()->prepare($sql);
		#$stmt->bindParam(":pri", $privilegios, PDO::PARAM_INT);
		#$sql->bindParam(":parent_id", $id,PDO::PARAM_INT);	# code...		
		
		$stmt->execute();
		return $stmt->fetchall();		
	}

	

}
	