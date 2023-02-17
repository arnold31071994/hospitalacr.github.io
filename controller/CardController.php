<?php 

//	require_once './models/CardModel.php';
	require_once 'UsuarioController.php';
	require_once 'InvitedController.php';
	require_once 'BanController.php';
	require_once 'ProfesionController.php';
	if ($PeticionAjax) 
	{
		require_once '../models/CardModel.php';
		//require_once '../models/UsuarioModel.php';
	}
	else
	{
		require_once './models/CardModel.php';
		//require_once './models/UsuarioModel.php';
	}

	class CardController extends CardModel
	{
		
		
		public function GetCardController($id)
		{
			
			$respuesta=CardModel::GetCardModel();
			//$conu= new UsuarioController();
			//	$total=$conu->DataUserController("count",0);
			
			$totaluser=UsuarioController::DataUserController("count",0);
			$totaluser=$totaluser->rowCount();

			$totalInvited=InvitedController::DataInvitedController("count",0,0,0);
			$totalInvited=$totalInvited->rowCount();

			$totalBan=BanController::DataBanController("count",0);
			$totalBan=$totalBan->rowCount();

			$totalvilla=ProfesionController::DataProfesionController("count");
			$totalvilla=$totalvilla->rowCount();
			

			foreach ($respuesta as $row => $card) 
			{
				if ($card["titulo"]=="USUARIOS" ) 
				{
					echo '<a href=" '. SERVERURL.''.$card["enlace"].'/" class="tile">
						<div class="tile-tittle">'.$card["titulo"].'</div>
						<div class="tile-icon">
							<i class="'.$card["icono"].' fa-fw"></i>
							<p>'.$totaluser.' Registrados</p>
						</div>
					</a>';
				//	echo $total;						# code...
				}
				elseif ($card["titulo"]=="INVITACION" ) 
				{
					echo '<a href=" '. SERVERURL.''.$card["enlace"].'/" class="tile">
						<div class="tile-tittle">'.$card["titulo"].'</div>
						<div class="tile-icon">
							<i class="'.$card["icono"].' fa-fw"></i>
							<p>'.$totalInvited.' Registrados</p>
						</div>
					</a>';
				//	echo $total;						# code...
				}
				elseif ($card["titulo"]=="VETADOS" ) 
				{
					echo '<a href=" '. SERVERURL.''.$card["enlace"].'/" class="tile">
						<div class="tile-tittle">'.$card["titulo"].'</div>
						<div class="tile-icon">
							<i class="'.$card["icono"].' fa-fw"></i>
							<p>'.$totalBan.' Registrados</p>
						</div>
					</a>';
				//	echo $total;						# code...
				}
				elseif ($card["titulo"]=="VILLAS" ) 
				{
					echo '<a href=" '. SERVERURL.''.$card["enlace"].'/" class="tile">
						<div class="tile-tittle">'.$card["titulo"].'</div>
						<div class="tile-icon">
							<i class="'.$card["icono"].' fa-fw"></i>
							<p>'.$totalvilla.' Registrados</p>
						</div>
					</a>';
				//	echo $total;						# code...
				}
				else/*if ($card["titulo"]!="USUARIOS") */
				{
					echo '<a href=" '. SERVERURL.''.$card["enlace"].'/" class="tile">
						<div class="tile-tittle">'.$card["titulo"].'</div>
						<div class="tile-icon">
							<i class="'.$card["icono"].' fa-fw"></i>
							<p> <br> </p>
						</div>
						</a>';
				}
			}		
		}


		public function GetReportCardController($privilegios)
		{

	/*	echo'
		<form method="GET">
			<div class="container-fluid">
				<div class="row justify-content-md-center">
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="busqueda_inicio_prestamo" >Fecha inicial</label>
							<input type="date" class="form-control" name="busqueda_inicio" id="busqueda_inicio" maxlength="30">
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="form-group">
							<label for="busqueda_final_prestamo" >Fecha final</label>
							<input type="date" class="form-control" name="busqueda_final" id="busqueda_final" maxlength="30">
						</div>
					</div>
				</div>
			</div>
		</div>	';*/




			$respuesta=CardModel::GetSubCardModel($privilegios);
			foreach ($respuesta as $row => $card) 
			{
				if ($card["parent_id"]==4) 
				{
					echo '<a href=" '. SERVERURL.'reports/invoice.php?mod='.MainModel::Encryption($card["id"]).'&fi={{fi}}&ff={{ff}}" target="_blank" class="tile">
						<div class="tile-tittle">'.$card["titulo"].'</div>
						<div class="tile-icon">
							<i class="'.$card["icono"].' fa-fw"></i>
							<p></p>
						</div>
						</a>';



				}
			}

			//echo'</form>';
		}#ENDFUNCTION


		public function DataCardController($type,$id)
		{
			$type=mainModel::CleanString($type);
			$id=mainModel::Decryption($id);
			$id=MainModel::CleanString($id);


			return CardModel::DataCardModel($type,$id);
		}#ENDFUNCTION
		







	}#endclass