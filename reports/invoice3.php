<?php
	
	$PeticionAjax=true;
	require_once "../config/app.php";
	require "./fpdf.php";
	//	require "./fpdf.php";

	$mod=(isset($_GET['mod'])) ? $_GET['mod'] : 0;

	require_once "../controller/RecetaController.php";
	$ins_rec= new RecetaController();
	$DataReceta=$ins_rec->DataRecetaController("unic",$mod);

	//$DataBan=$ins_ban->DataBanController("list",0);
	if ($DataReceta->rowCount()==1) 
	{
		// code...
	
		$DataReceta=$DataReceta->fetch();


		require_once "../controller/UsuarioController.php";
		$ins_doc= new UsuarioController();
		$Datadoc=$ins_doc->DataUserController("unic",MainModel::Encryption($DataReceta['id_usuario']));

		if ($Datadoc->rowCount()==1) 
		{
			$Datadoc=$Datadoc->fetch();

			//echo var_dump($Datadoc);
		}



		
		//$da['receta_fecha'];

		$pdf = new FPDF('P','mm','b5');
		$pdf->SetMargins(5,10,17);
		$pdf->AddPage();
		$pdf->Image('../view/assets/img/logo.png',5,5,10,10,'PNG');

		$pdf->SetFont('Arial','B',8);
		$pdf->SetTextColor(0,107,181);
		$pdf->Cell(60,3,utf8_decode(strtoupper("Consultorio Dr Tabla".$DataReceta['id_usuario'])),0,0,'C');
	  /*$pdf->SetFont('Arial','',12);
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(-40,16,utf8_decode('N. de factura'),'',0,'C');*/

		$pdf->Ln(10);

		$pdf->SetFont('Arial','',12);
		$pdf->SetTextColor(0,107,181);
		//$pdf->Cell(60,3,utf8_decode(strtoupper("Consultorio Dr Tabla".$Datadoc['nombre'])),0,0,'C');
		//$pdf->Cell(0,10,utf8_decode("Dr.".$Datadoc['nombre']),0,0,'C');
		$pdf->SetFont('Arial','',6);
		$pdf->SetTextColor(97,97,97);
		//$pdf->Cell(-50,10,utf8_decode("CODIGO DE FACTURA"),'',0,'C');

		//$pdf->Ln(10);

		/*$pdf->SetTextColor(33,33,33);
		$pdf->Cell(60,0,utf8_decode('Fecha:'),0,10);
		$pdf->Cell(80,0,utf8_decode(date("d/m/Y", strtotime($DataReceta['receta_fecha']))),0,0);
		$pdf->SetTextColor(97,97,97);
		//$pdf->Cell(-40,10,utf8_decode("CODIGO DE FACTURA"),'',0,'C');
		$pdf->Cell(50,0,utf8_decode(date("d/m/Y", strtotime($DataReceta['receta_fecha']))),'',50,'C');
		$pdf->Ln(10);
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(15,6,utf8_decode('Atendido por:'),"",0,0);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(15,8,utf8_decode("NOMBRE DEL ADMINISTRADOR"),0,0);

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

		$pdf->SetFillColor(38,198,208);
		$pdf->SetDrawColor(38,198,208);
		$pdf->SetTextColor(33,33,33);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(15,10,utf8_decode('Cant.'),1,0,'C',true);
		$pdf->Cell(90,10,utf8_decode('Descripción'),1,0,'C',true);
		$pdf->Cell(51,10,utf8_decode('Tiempo - Costo'),1,0,'C',true);
		$pdf->Cell(25,10,utf8_decode('Subtotal'),1,0,'C',true);

		$pdf->Ln(10);

		$pdf->SetTextColor(97,97,97);


		$pdf->Cell(15,10,utf8_decode(2000),'L',0,'C');
		$pdf->Cell(90,10,utf8_decode("00000 - silla plastica blanca"),'L',0,'C');
		$pdf->Cell(51,10,utf8_decode("7 Evento ($10.00 c/u)"),'L',0,'C');
		$pdf->Cell(25,10,utf8_decode("$100,000.00"),'LR',0,'C');
		$pdf->Ln(10);
		$pdf->Cell(15,10,utf8_decode(2000),'L',0,'C');
		$pdf->Cell(90,10,utf8_decode("00000 - Mesa plastica roja"),'L',0,'C');
		$pdf->Cell(51,10,utf8_decode("10 Evento ($10.00 c/u)"),'L',0,'C');
		$pdf->Cell(25,10,utf8_decode("$100,000.00"),'LR',0,'C');

		$pdf->Ln(10);

		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(15,10,utf8_decode(''),'T',0,'C');
		$pdf->Cell(90,10,utf8_decode(''),'T',0,'C');
		$pdf->Cell(51,10,utf8_decode('TOTAL'),'LTB',0,'C');
		$pdf->Cell(25,10,utf8_decode("$100,000.00"),'LRTB',0,'C');

		$pdf->Ln(15);*/

		//$pdf->MultiCell(0,9,utf8_decode("OBSERVACIÓN: "/*.$datos_prestamo['prestamo_observacion']*/),0,'J',false);
	/*	$pdf->Cell(0,12,utf8_decode("NOMBRE DEL OBSERVACIÓN"),0,0);

		$pdf->SetFont('Arial','',6);
		//if(true){
			$pdf->Ln(11);

			$pdf->SetTextColor(97,97,97);
			$pdf->MultiCell(0,4,utf8_decode("NOTA IMPORTANTE: Esta factura presenta un saldo pendiente de pago por la cantidad de $.00"),0,'J',false);
		//}

		$pdf->Ln(12);*/

		/*----------  INFO. EMPRESA  ----------
		$pdf->SetFont('Arial','B',6);
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(0,6,utf8_decode("NOMBRE DE LA EMPRESA"),0,0,'C');
		$pdf->Ln(3);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(0,6,utf8_decode("DIRECCION DE LA EMPRESA"),0,0,'C');
		$pdf->Ln(3);
		$pdf->Cell(0,6,utf8_decode("Teléfono: "),0,0,'C');
		$pdf->Ln(3);
		$pdf->Cell(0,6,utf8_decode("Correo: "),0,0,'C');*/


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
		<title><?php echo COMPANY; ?></title>

		<?php include '../view/inc/Link.php'; ?>
	</head>
	<body>
		<div class="full-box container-404">
			<div>
				<p class="text-center"><i class="fa fa-rocket fa-10x"></i></p>
				<h1 class="text-center">ERROR 404</h1>
				<p class="lead text-center">Receta No Encontrada</p>
			</div>
		</div>	

		<?php include '../view/inc/Script.php'; ?>
	</body>
	</html>

	<?php } ?>
	