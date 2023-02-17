<?php 

require_once 'MainModel.php';


/**
 * 
 */
class RecetaModel extends MainModel
{
	protected static function AgregaRecetaModel($datos)
	{
		$sql=MainModel::Conectar()->prepare("INSERT INTO receta(receta_cod, receta_fecha, paciente_id, id_usuario,	receta_item, receta_obv) VALUES(:cod, :fecha, :pac, :user, :cant, :obv)");


		


		$sql->bindParam(":cod",	  	 $datos["cod"], PDO::PARAM_STR);
		$sql->bindParam(":fecha",	 $datos["fecha"] ,PDO::PARAM_STR);
		$sql->bindParam(":cant",  	 $datos["cant"], PDO::PARAM_STR);
		$sql->bindParam(":obv",  	 $datos["obv"], PDO::PARAM_STR);
		$sql->bindParam(":pac",  	 $datos["pac"], PDO::PARAM_INT);
		$sql->bindParam(":user",  	 $datos["user"], PDO::PARAM_INT);
		$sql->execute();
		
		return $sql;
		
	}


	protected static function AgregaRecetaDetalleModel($datos)
	{
		$sql=MainModel::Conectar()->prepare("INSERT INTO receta_item(ireceta_nom, ireceta_cant, ireceta_dias,receta_cod, item_cod, item_tipo,item_dos,ireceta_detalle) 
			VALUES(:nom, :cant, :dias, :reccod, :itemcod, :tipo, :dosis, :detalle)");
			

		$sql->bindParam(":nom",	  		$datos["nom"], PDO::PARAM_STR);
		$sql->bindParam(":cant",	  	$datos["cant"] ,PDO::PARAM_STR);
		$sql->bindParam(":dias",  		$datos["dias"], PDO::PARAM_STR);
		$sql->bindParam(":reccod",  	$datos["codi"], PDO::PARAM_STR);
		$sql->bindParam(":itemcod",  	$datos["itemcod"], PDO::PARAM_STR);
		$sql->bindParam(":tipo",  		$datos["tipo"], PDO::PARAM_STR);
		$sql->bindParam(":dosis", 	 	$datos["dos"], PDO::PARAM_STR);
		$sql->bindParam(":detalle", 	$datos["detalle"], PDO::PARAM_STR);
				
		$sql->execute();		
		return $sql;

	}


	protected static function EliminarRecetaModel($codigo,$tipo)
	{
		if ($tipo=="receta") 
		{
			$sql=MainModel::Conectar()->prepare("DELETE FROM receta WHERE receta_cod= :$codigo");
			
		}
		elseif ($tipo=="detalle") 
		{
			$sql=MainModel::Conectar()->prepare("DELETE FROM receta_item WHERE receta_cod= :$codigo");

		}
		
		$sql->bindParam(":codigo",$codigo);		
		$sql->execute();
		return $sql;
	}

		protected static function DataRecetaModel($type,$id)
		{
			if ($type=="unic") 
			{
				$sql=MainModel::Conectar()->prepare("SELECT * FROM receta WHERE receta_id = :id");

				$sql->bindParam(":id",$id, PDO::PARAM_INT);				# code...
			}
			elseif ($type=="count") 
			{
				$sql=MainModel::Conectar()->prepare("SELECT * from receta r inner join pacientes p on r.paciente_id = p.pac_id WHERE r.receta_id =:id");

			}
			elseif ($type=="item") 
			{
				$sql=MainModel::Conectar()->prepare("SELECT * from receta_item WHERE receta_cod =:id");
				$sql->bindParam(":id",$id, PDO::PARAM_STR);	

			}


			$sql->execute();
			return $sql;
		}#endfunction		
	
}#endclass Invited