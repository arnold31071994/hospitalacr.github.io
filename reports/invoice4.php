 <?php 
 class PDF extends FPDF
    {

        // Cabecera de página
        function Header()
        {
            // Logo
            $this->Image('../view/assets/img/logo.png',5,5,10,10,'PNG');
            // Arial bold 15
            $this->SetFont('Arial','B',8);
            // Movernos a la derecha
            $this->Cell(10);
            // Título
            $this->Cell(0,0,'Consultorio Dr Chanbra',0,0,'C');
            // Salto de línea
            $this->Ln(20);
        }

        // Pie de página
        function Footer()
        {
            $mod=(isset($_GET['mod'])) ? $_GET['mod'] : 0;


            $ins_rec= new RecetaController();
            $DataReceta=$ins_rec->DataRecetaController("unic",$mod);
            $DataReceta=$DataReceta->fetch();

            $ins_doc= new UsuarioController();
            $Datadoc=$ins_doc->DataUserController("unic",MainModel::Encryption($DataReceta['id_usuario']));
            if ($Datadoc->rowCount()==1) 
            {
                $Datadoc=$Datadoc->fetch();
                    //echo var_dump($Datadoc);
            }


            $ins_pac= new PacienteController();
            $Datapac=$ins_pac->DataPacienteController("unic",MainModel::Encryption($DataReceta['paciente_id']));
            if ($Datapac->rowCount()==1) 
            {
                $Datapac=$Datapac->fetch();
                    //echo var_dump($Datadoc);
            }


            // Posición: a 1,5 cm del final
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',6);
            // Número de página
            //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
            $this->Cell(0,10,'Fecha: '.$DataReceta['receta_fecha'].'',0,0,'L');
            $this->Cell(-66,15,'Paciente: '.$Datapac['pac_nom'].' '.$Datapac['pac_ape'],0,0,'C');

        }
    }


   /// $DataReceta=$DataReceta->fetch();


    $Dataitems=$ins_rec->DataRecetaController("item",MainModel::Encryption($DataReceta['receta_cod']));
   // $Dataitems->fetchall();
    // Creación del objeto de la clase heredada
    $pdf = new PDF('P','mm','b5');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',8);
    //$pdf->Cell(0,5,$DataReceta['receta_cod'],0,1);

    foreach ($Dataitems as $item) 
    {
       $pdf->Cell(0,5,$item['ireceta_cant'].' '.$item['ireceta_nom'].' '.$item['item_tipo'],0,1);
        $pdf->Cell(0,5,$item['ireceta_detalle'].'ppppppp',0,1);
    }

   // for($i=1;$i<=10;$i++)
     //   $pdf->Cell(0,5,'Imprimiendo linea numero '.$i,0,1);
    $pdf->Output("I","Factura_1.pdf",true);