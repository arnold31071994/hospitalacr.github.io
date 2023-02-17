<?php 
	
	if ($PeticionAjax) 
	{
	require_once '../models/PrivilegiosModel.php';
	}
    else
	{
		require_once './models/PrivilegiosModel.php';
	}

/**
 * 
 */
class PrivilegiosController extends PrivilegiosModel
{



	public function GetPrivilegiosController()
	{
		$privi=PrivilegiosModel::GetPrivilegiosModel();
		echo '<label for="medicosl" class="bmd-label-floating">Privilegios</label>';
		echo'<select class="form-control" name="usuario_privilegio_reg">';
		echo '<option value="" selected="" disabled="">Seleccione una opción</option>';
			
		foreach ($privi as $key => $valuep) 
		{
			echo '<option value="'.MainModel::Encryption($valuep['nivel 1']).'">'.$valuep['usuario'].'</option>';
		}
		echo'</select>';
	}
	

}
	