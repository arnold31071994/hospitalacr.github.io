<?php 
	if ($PeticionAjax) 
	{
		require_once '../models/MedicoModel.php';
	}
	else
	{
		require_once './models/MedicoModel.php';
	}

/**
 * 
 */
class MedicoController extends MedicoModel
{
	public function BuscarMedico($esp)
	{
		/*if ($esp=='' ||$esp=='ESPECIALIDAD') 
		{
			# code...
		}
		else
		{*/
			$res=MedicoModel::GetMedicoModel($esp);
			#echo='<label for="medico">medico</label>';
			#echo ' <select name="medico" id="medico">';
			#' <option value="pri">prueba</option>';
			
			echo'<select name="medico" id="medicosl" name="cita_medico_reg"  onchange="esconderpre()" >';
			echo '<option value="medico">MEDICO</option>';
			foreach ($res as $row => $med) 
			{
				echo '<option value="'.$med["MED_NOM"].'">'.$med["MED_NOM"].'</option>';
			}
			echo'</select>';
		#}
	}


}
#echo '<option value="'.$med["MED_NOM"].'">'.$med["MED_NOM"].'</option>';