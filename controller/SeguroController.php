

<?php 
	if ($PeticionAjax) 
	{
		require_once '../models/SegurosModel.php';
	}
	else
	{
		require_once './models/SegurosModel.php';
	}

/**
 */ 
 
class SeguroController extends SeguroModel
{
	public function BuscarSeguro()
	{
		$res=SeguroModel::GetSeguroModel();
		#echo='<label for="medico">medico</label>';
		#echo ' <select name="medico" id="medico">';
		#' <option value="pri">prueba</option>';
		echo '<label for="medicosl" class="bmd-label-floating">Seguro</label>';
		echo'<select class="form-control" name="paciente_seg_reg">';
		echo '<option value="seguro">Seguro</option>';
		foreach ($res as $row => $seguro) 
		{
			echo '<option value="'.MainModel::Encryption($seguro["seg_id"]).'">'.$seguro["seg_nom"].'</option>';
		}
		echo'</select>';
		
	}

	


}
#echo '<option value="'.$med["MED_NOM"].'">'.$med["MED_NOM"].'</option>'; 