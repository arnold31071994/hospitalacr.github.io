<?php 
	
	require_once 'MainModel.php';

/**
 * 
 */
class MenuModel extends MainModel
{
	protected static function GetMenuModel($privilegios)
	{

	 	$sql="SELECT * FROM menu WHERE parent_id=0 AND ( titulo!='LOGUIN') AND (privilegios<=:pri) ";
	 	$stmt= MainModel::Conectar()->prepare($sql);
	 	$stmt->bindParam(":pri", $privilegios, PDO::PARAM_STR);

	 	$stmt->execute();
	 	return $stmt->fetchall();
	 	

		# code...
	}

	protected static function GetSubMenuModel($privilegios)
	{
		
		$sql="SELECT * FROM menu WHERE parent_id>0 AND (privilegios<=:pri) ORDER BY parent_id";
		$stmt= MainModel::Conectar()->prepare($sql);
		$stmt->bindParam(":pri", $privilegios, PDO::PARAM_INT);
		#$sql->bindParam(":parent_id", $id,PDO::PARAM_INT);	# code...		
		
		$stmt->execute();
		return $stmt->fetchall();
		
	}

	protected static function GetPrivilegioModel()
	{
		$sql="SELECT * FROM villa WHERE ORDER BY id_villa";
		$stmt= MainModel::Conectar()->prepare($sql);
		#$stmt->bindParam(":pri", $privilegios, PDO::PARAM_INT);
		#$sql->bindParam(":parent_id", $id,PDO::PARAM_INT);	# code...		
		
		$stmt->execute();
		return $stmt->fetchall();		
	}

	

}
	