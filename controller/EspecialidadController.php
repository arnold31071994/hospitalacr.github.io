<?php 
	if ($PeticionAjax) 
	{
		require_once '../models/EspecialidadModel.php';
	}
	else
	{
		require_once './models/EspecialidadModel.php';
	}

/**
 * 
 */
class EspecialidadController extends EspecialidadModel
{
	public function BuscarEspecialidad()
	{

		$respuesta=EspecialidadModel::GetEspecialidadModel();
		echo'<select name="especialidad" id="especialidad" name="cita_especialidad_reg"  onchange="esconderpre()">';
		echo '<option value="ESPECIALIDAD">ESPECIALIDAD</option>';
		foreach ($respuesta as $row => $esp) 
		{
			echo '<option value="'.$esp["ES_DES"].'">'.$esp["ES_DES"].'</option>';
		}
		echo'</select>';

	}


}

