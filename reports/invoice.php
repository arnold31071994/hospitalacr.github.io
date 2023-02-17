<?php
	
	$PeticionAjax=true;
	require_once "../config/app.php";
	require "./fpdf.php";


	$mod=(isset($_GET['mod'])) ? $_GET['mod'] : 0;
	/*$fi=(isset($_GET['fi'])) ? $_GET['fi'] : 0;
	$ff=(isset($_GET['ff'])) ? $_GET['ff'] : 0;*/
	//$fi=$_GET['fi'];
	//$ff=$_GET['ff'];

	//$fi=date("d/m/Y",substr(strtotime($fi),0,11));
    $fi=substr($_GET['fi'],1,10);
    $ff=substr($_GET['ff'],1,10);

    $fi2=date_create($fi);
    $ff2=date_create($ff);
    $fi2=date_format($fi2,'d-m-Y');
    $ff2=date_format($ff2,'d-m-Y');
   // $fi=date("d/m/Y",$fi);

    	
    
	require_once "../controller/CardController.php";
	$ins_card= new CardController();

	$DataReport=$ins_card->DataCardController("unic",$mod);

	
if ($DataReport->rowCount()==1) 
{
	$DataReport=$DataReport->fetch();

  /*	require_once "../controller/UsuarioController.php";
	$ins_user= new UsuarioController();*/

	//$datauser=$ins_user->DataUserController();

	if ($DataReport['titulo']=='Entradas' || $DataReport['titulo']=='Salidas' || $DataReport['titulo']=="Historial" || $DataReport['titulo']=='Accesos') 
	{
		
		require_once "../controller/InvitedController.php";
		$ins_inv= new InvitedController();

		if ($DataReport['titulo']=="Historial") 
		{
			$DataInvited=$ins_inv->DataInvitedController("list",$mod,$fi,$ff);
			$pdf = new FPDF('P','mm','b5');
			$pdf->SetMargins(17,17,17);
			$pdf->AddPage();
			$pdf->Image('../view/assets/img/Logo2.png',10,10,30,30,'PNG');

			$pdf->SetFont('Arial','B',18);
			$pdf->SetTextColor(0,107,181);
			$pdf->Cell(0,10,utf8_decode(strtoupper($DataReport['titulo']. " De Invitaciones realidadas")),0,0,'C');

			$pdf->SetFont('Arial','',14);
			$pdf->SetTextColor(0,0,0);			
			$pdf->Cell(-182,30,utf8_decode("De ". $fi2. " Hasta ". $ff2),0,0,'C');
			$pdf->SetFont('Arial','',12);
			$pdf->SetTextColor(33,33,33);
			//$pdf->Cell(-35,20,utf8_decode($fi),'',0,'C');

			$pdf->Ln(10);

		}
		if ($DataReport['titulo']=='Salidas') 
		{
			$DataInvited=$ins_inv->DataInvitedController("fal",$mod,$fi,$ff);

			$pdf = new FPDF('P','mm','Letter');
			$pdf->SetMargins(17,17,17);
			$pdf->AddPage();
			$pdf->Image('../view/assets/img/Logo2.png',10,10,30,30,'PNG');

			$pdf->SetFont('Arial','B',18);
			$pdf->SetTextColor(0,107,181);
			$pdf->Cell(0,10,utf8_decode(strtoupper($DataReport['titulo']. " NO REALIZADAS")),0,0,'C');

			$pdf->SetFont('Arial','',14);
			$pdf->SetTextColor(0,0,0);			
			$pdf->Cell(-182,30,utf8_decode("De ". $fi2. " Hasta ". $ff2),0,0,'C');
			$pdf->SetFont('Arial','',12);
			$pdf->SetTextColor(33,33,33);
			//$pdf->Cell(-35,20,utf8_decode($fi),'',0,'C');

			$pdf->Ln(10);

		}

		if($DataReport['titulo']=='Accesos')
		{
			$DataInvited=$ins_inv->DataInvitedController("aut",$mod,$fi,$ff);

			$pdf = new FPDF('P','mm','Letter');
			$pdf->SetMargins(17,17,17);
			$pdf->AddPage();
			$pdf->Image('../view/assets/img/logo2.png',90,5,30,30,'PNG');
			$pdf->SetFont('Arial','B',18);
			$pdf->SetTextColor(0,107,181);
			$pdf->Cell(0,45,utf8_decode(strtoupper("REPORTE DE INVITACIONES AUTORIZADAS POR CODIGO")),0,0,'C');

			/*$pdf->SetFont('Arial','',14);
			$pdf->SetTextColor(0,0,0);			
			$pdf->Cell(-182,30,utf8_decode("De ". $fi2. " Hasta ". $ff2),0,0,'C');*/
			$pdf->SetFont('Arial','',12);
			$pdf->SetTextColor(33,33,33);
			//$pdf->Cell(-35,20,utf8_decode($fi),'',0,'C');

			$pdf->Ln(10);

			//$Data=$DataBan->fetchAll();
		}

		//$DataInvited=$ins_inv->DataInvitedController("list",$mod,$fi,$ff);
		$Data=$DataInvited->fetchAll();


	}
	elseif($DataReport['titulo']=='PROHIBICION')
	{
		require_once "../controller/BanController.php";
		$ins_ban= new BanController();

		$DataBan=$ins_ban->DataBanController("list",0);

		$pdf = new FPDF('P','mm','Letter');
		$pdf->SetMargins(17,17,17);
		$pdf->AddPage();
		$pdf->Image('../view/assets/img/logo2.png',10,10,30,30,'PNG');
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(0,107,181);
		$pdf->Cell(0,10,utf8_decode(strtoupper("REPORTE DE PERSONAS PROHIBIDAS")),0,0,'C');

		/*$pdf->SetFont('Arial','',14);
		$pdf->SetTextColor(0,0,0);			
		$pdf->Cell(-182,30,utf8_decode("De ". $fi2. " Hasta ". $ff2),0,0,'C');*/
		$pdf->SetFont('Arial','',12);
		$pdf->SetTextColor(33,33,33);
		//$pdf->Cell(-35,20,utf8_decode($fi),'',0,'C');

		$pdf->Ln(10);

		$Data=$DataBan->fetchAll();
	}#end if report vetados

	elseif($DataReport['titulo']=='Accesos')
	{
		require_once "../controller/BanController.php";
		$ins_ban= new BanController();

		$DataBan=$ins_ban->DataBanController("list",0);

		$pdf = new FPDF('P','mm','Letter');
		$pdf->SetMargins(17,17,17);
		$pdf->AddPage();
		$pdf->Image('../view/assets/img/logo2.png',90,5,30,30,'PNG');
		$pdf->SetFont('Arial','B',18);
		$pdf->SetTextColor(0,107,181);
		$pdf->Cell(0,45,utf8_decode(strtoupper("REPORTE DE INVITACIONES AUTORIZADAS POR CODIGO")),0,0,'C');

		/*$pdf->SetFont('Arial','',14);
		$pdf->SetTextColor(0,0,0);			
		$pdf->Cell(-182,30,utf8_decode("De ". $fi2. " Hasta ". $ff2),0,0,'C');*/
		$pdf->SetFont('Arial','',12);
		$pdf->SetTextColor(33,33,33);
		//$pdf->Cell(-35,20,utf8_decode($fi),'',0,'C');

		$pdf->Ln(10);

		$Data=$DataBan->fetchAll();
	}#

	$pdf->SetFont('Arial','',15);
	$pdf->SetTextColor(0,107,181);
	$pdf->Cell(0,10,utf8_decode(""),0,0,'C');
	$pdf->SetFont('Arial','',12);
	$pdf->SetTextColor(97,97,97);
	//$pdf->Cell(-35,10,utf8_decode("CODIGO DE FACTURA"),'',0,'C');

	$pdf->Ln(25);

	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(36,8,utf8_decode('Fecha de emisión:'.date("d/m/Y")),0,0);
	$pdf->SetTextColor(97,97,97);
	//$pdf->Cell(27,8,utf8_decode(date("d/m/Y", strtotime($fi))),0,0);
	$pdf->Ln(8);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(27,8,utf8_decode('Atendido por:'),"",0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(13,8,utf8_decode("NOMBRE DEL ADMINISTRADOR"),0,0);

	$pdf->Ln(15);

	$pdf->SetFont('Arial','',12);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(15,8,utf8_decode('Cliente:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(65,8,utf8_decode("NOMBRE DEL CLIENTE"),0,0);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(10,8,utf8_decode('DNI:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(40,8,utf8_decode("0000000000"),0,0);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(19,8,utf8_decode('Teléfono:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(35,8,utf8_decode("(000)00000000"),0,0);
	$pdf->SetTextColor(33,33,33);

	$pdf->Ln(8);

	$pdf->Cell(8,8,utf8_decode('Dir:'),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(109,8,utf8_decode("DIRECCION DEL CLIENTE"),0,0);

	$pdf->Ln(15);
	if ($DataReport['titulo']=="Historial") 
	{
		
	
		$pdf->SetFillColor(38,198,208);
		$pdf->SetDrawColor(38,198,208);
		$pdf->SetTextColor(33,33,33);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(25,10,utf8_decode('CEDULA'),0,0,'C',true);
		$pdf->Cell(80,10,utf8_decode('NOMBRE'),1,0,'C',true);
		$pdf->Cell(31,10,utf8_decode('FECHA'),1,0,'C',true);
		$pdf->Cell(20,10,utf8_decode('ENTRADA'),1,0,'C',true);
		$pdf->Cell(20,10,utf8_decode('SALIDA'),1,0,'C',true);
		//$pdf->Cell(25,10,utf8_decode('Subtotal'),1,0,'C',true);

		$pdf->Ln(10);

		$pdf->SetTextColor(97,97,97);

		foreach ($Data as $row =>$key ) 
		{
			// code...

			$pdf->Cell(25,10,utf8_decode($key['inv_ced']),'L',0,'C');
			$pdf->Cell(80,10,utf8_decode($key['inv_nom']." ".$key['inv_ape']),'L',0,'C');
			$pdf->Cell(31,10,utf8_decode($key['inv_fecha']),'L',0,'C');
			$pdf->Cell(20,10,utf8_decode($key['inv_hora_ent']),'L',0,'C');
			$pdf->Cell(20,10,utf8_decode($key['inv_hora_salida']),'LR',0,'R');
			//$pdf->Cell(25,10,utf8_decode("$100,000.00"),'LR',0,'C');
			$pdf->Ln(10);

		}
	}

	elseif ($DataReport['titulo']=='Salidas') 
	{
		
	
		$pdf->SetFillColor(38,198,208);
		$pdf->SetDrawColor(38,198,208);
		$pdf->SetTextColor(33,33,33);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(25,10,utf8_decode('CEDULA'),0,0,'C',true);
		$pdf->Cell(80,10,utf8_decode('NOMBRE'),1,0,'C',true);
		$pdf->Cell(31,10,utf8_decode('FECHA'),1,0,'C',true);
		$pdf->Cell(23,10,utf8_decode('ENTRADA'),1,0,'C',true);
		$pdf->Cell(23,10,utf8_decode('SALIDA'),1,0,'C',true);
		//$pdf->Cell(25,10,utf8_decode('Subtotal'),1,0,'C',true);

		$pdf->Ln(10);

		$pdf->SetTextColor(97,97,97);

		foreach ($Data as $row =>$key ) 
		{
			if ($key['inv_fecha']<date("Y-m-d") && $key['inv_hora_salida']=='' ) 
			{
				$horas='Sin Registrar';
			}
			else
			{
				$horas=$key['inv_hora_salida'];	
			}
			if ($key['inv_fecha']<date("Y-m-d") && $key['inv_hora_ent']=='' ) 
			{
				$horae='Sin Registrar';
			}
			else
			{
				$horae=$key['inv_hora_ent'];	
			}
			$pdf->Cell(25,10,utf8_decode($key['inv_ced']),'L',0,'C');
			$pdf->Cell(80,10,utf8_decode($key['inv_nom']." ".$key['inv_ape']),'L',0,'C');
			$pdf->Cell(31,10,utf8_decode($key['inv_fecha']),'L',0,'C');
			$pdf->Cell(23,10,utf8_decode($horae),'L',0,'C');
			$pdf->Cell(23,10,utf8_decode($horas),'LR',0,'C');
			//$pdf->Cell(25,10,utf8_decode("$100,000.00"),'LR',0,'C');
			$pdf->Ln(10);

		}
	}
	elseif ($DataReport['titulo']=='Accesos') 
	{
		
	
		$pdf->SetFillColor(38,198,208);
		$pdf->SetDrawColor(38,198,208);
		$pdf->SetTextColor(33,33,33);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(25,10,utf8_decode('CEDULA'),0,0,'C',true);
		$pdf->Cell(55,10,utf8_decode('NOMBRE'),1,0,'C',true);
		$pdf->Cell(24,10,utf8_decode('CODIGO'),1,0,'C',true);
		$pdf->Cell(24,10,utf8_decode('FECHA'),1,0,'C',true);
		$pdf->Cell(23,10,utf8_decode('ENTRADA'),1,0,'C',true);
		$pdf->Cell(23,10,utf8_decode('SALIDA'),1,0,'C',true);
		//$pdf->Cell(25,10,utf8_decode('Subtotal'),1,0,'C',true);

		$pdf->Ln(10);

		$pdf->SetTextColor(97,97,97);

		foreach ($Data as $row =>$key ) 
		{
			/*$pdf->Cell(80,10,utf8_decode(var_dump($key)),'L',0,'C');*/
			if ($key['inv_fecha']<date("Y-m-d") && $key['inv_hora_salida']=='' ) 
			{
				$horas='Sin Registrar';
			}
			else
			{
				$horas=$key['inv_hora_salida'];	
			}
			if ($key['inv_fecha']<date("Y-m-d") && $key['inv_hora_ent']=='' ) 
			{
				$horae='Sin Registrar';
			}
			else
			{
				$horae=$key['inv_hora_ent'];	
			}
			$pdf->Cell(25,10,utf8_decode($key['inv_ced']),'L',0,'C');
			$pdf->Cell(55,10,utf8_decode($key['inv_nom']." ".$key['inv_ape']),'L',0,'C');
			$pdf->Cell(24,10,utf8_decode($key['aut_cod']),'L',0,'C');
			$pdf->Cell(24,10,utf8_decode($key['inv_fecha']),'L',0,'C');
			$pdf->Cell(23,10,utf8_decode($horae),'L',0,'C');
			$pdf->Cell(23,10,utf8_decode($horas),'LR',0,'C');
			//$pdf->Cell(25,10,utf8_decode("$100,000.00"),'LR',0,'C');
			$pdf->Ln(10);

		}
	}
	elseif ($DataReport['titulo']=='PROHIBICION') 
	{
		$pdf->SetFillColor(38,198,208);
		$pdf->SetDrawColor(38,198,208);
		$pdf->SetTextColor(33,33,33);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(25,10,utf8_decode('CEDULA'),0,0,'C',true);
		$pdf->Cell(65,10,utf8_decode('NOMBRE'),1,0,'C',true);
		$pdf->Cell(31,10,utf8_decode('FECHA'),1,0,'C',true);
		$pdf->Cell(50,10,utf8_decode('RAZON '),1,0,'C',true);
		//$pdf->Cell(20,10,utf8_decode('SALIDA'),1,0,'C',true);
		//$pdf->Cell(25,10,utf8_decode('Subtotal'),1,0,'C',true);

		$pdf->Ln(10);

		$pdf->SetTextColor(97,97,97);

		foreach ($Data as $row =>$key ) 
		{
			$date=date_create($key['ban_fecha']);
			//$date=date_format($date,'d-m-Y');

			$pdf->Cell(25,10,utf8_decode($key['ban_ced']),'L',0,'C');
			$pdf->Cell(65,10,utf8_decode($key['ban_nom']." ".$key['ban_ape']),'L',0,'C');
			$pdf->Cell(31,10,utf8_decode(date_format($date,'d-m-Y')),'L',0,'C');
			$pdf->Cell(50,10,utf8_decode($key['ban_nota']),'L',0,'C');
			//$pdf->Cell(20,10,utf8_decode($key['inv_hora_salida']),'LR',0,'C');
			//$pdf->Cell(25,10,utf8_decode("$100,000.00"),'LR',0,'C');
			$pdf->Ln(10);

		}		
	}

	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(15,10,utf8_decode(''),'T',0,'C');
	$pdf->Cell(80,10,utf8_decode(''),'T',0,'C');
	$pdf->Cell(51,10,utf8_decode('TOTAL'),'LTB',0,'C');
	$pdf->Cell(25,10,utf8_decode("$100,000.00"),'LRTB',0,'C');

	$pdf->Ln(15);

	$pdf->MultiCell(0,9,utf8_decode("OBSERVACIÓN: "),0,'J',false);

	$pdf->SetFont('Arial','',12);
	if(true){
		$pdf->Ln(12);

		$pdf->SetTextColor(97,97,97);
		$pdf->MultiCell(0,9,utf8_decode("NOTA IMPORTANTE: \nEsta factura presenta un saldo pendiente de pago por la cantidad de $.00"),0,'J',false);
	}

	$pdf->Ln(25);

	/*----------  INFO. EMPRESA  ----------*/
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(0,6,utf8_decode("NOMBRE DE LA EMPRESA"),0,0,'C');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(0,6,utf8_decode("DIRECCION DE LA EMPRESA"),0,0,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,6,utf8_decode("Teléfono: "),0,0,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,6,utf8_decode("Correo: "),0,0,'C');
	$pdf->PageNo();

	$pdf->Output("I","Factura_1.pdf",true);
}

else
{
	?>

	<!DOCTYPE html>
	<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title> <?php echo COMPANY ?></title>
		<?php include '../view/inc/Link.php'; ?>
	</head>
	<body>
		<div class="full-box container-404">
			<div>
				<p class="text-center"><i class="fa fa-rocket fa-10x"></i></p>
				<h1 class="text-center">ERROR 404</h1>
				<p class="lead text-center">Este reporte no esta listo</p>
			</div>
		</div>

		<?php include '../view/inc/Script.php';  ?>

	</body>
	</html>
<?php }  ?>	
