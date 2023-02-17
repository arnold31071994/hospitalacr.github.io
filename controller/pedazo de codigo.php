<?php 
if (isset($_POST['usuario_cedula_bus'])) 
		{
			$cedula=MainModel::CleanString($_POST['usuario_cedula_bus']);
			

			if (MainModel::VerificarDatos("[0-9-]{10,20}",$cedula)) 
			{
				echo "
				<script>
					Swal.fire({
  					type: 'error',
 					title: 'Ocurrió un error inesperado',
  					text: 'No ha Intruducido Datos Validos' 
					}).then((result) => {
						if(result.value){
							window.location.href = 'http://localhost:8080/Arnold/user-new';
						}
					});
				</script>";

			}					//ConsultaJCE
			$respuesta=MainModel::ConsultaSimple("SELECT CEDULA_COMPLETA,NOMBRES, APELLIDO1, APELLIDO2, CALLE,TELEFONO FROM cedulados WHERE CEDULA_COMPLETA=".$cedula." OR (NUM_PASAPO=".$cedula.")");

			if ($respuesta->rowCount()>0) 
			{
				
				$datos = array('cedula' => $cedula );
				$ced=UsuarioModel::BuscarUsuariosModel($datos);
				foreach ($ced as $row => $item) 
				{

					echo "
						<script>
							Swal.fire({
  								type: 'error',
 								title: '".$item['CEDULA_COMPLETA']."',
  								text: 'Encontraste tu Cedula' 
							}).then((result) => {
							if(result.value){
								

								$('#usuario_dni').val('".$item['CEDULA_COMPLETA']."');
								$('#usuario_nombre').val('".$item['NOMBRES']."');
								$('#usuario_apellido').val('".$item['APELLIDO1']." ".$item['APELLIDO2']."');
								$('#usuario_telefono').val('".$item['TELEFONO']."');
								$('#usuario_direccion').val('".$item['CALLE']."');

								$('#usuario_dni').prop('readonly', true);
								$('#usuario_nombre').prop('readonly', true);
								$('#usuario_apellido').prop('readonly', true);
								$('#usuario_telefono').prop('readonly', true);
								$('#usuario_direccion').prop('readonly', true);
								$('#cedula').empty();
								
							}
							});						
							
						</script>";	
				}
		
			}
			else
			{
				echo "
				<script>
					Swal.fire({
  					type: 'error',
 					title: 'Ocurrió un error inesperado',
  					text: 'Esta Cedula No Existe' 
					}).then((result) => {
						if(result.value){
							window.location.href = 'http://localhost:8080/Arnold/user-new';
						}
					});
				</script>";
			}


			
		}





function generarCodigo($longitud) {
    $key = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXVZ';
    $max = strlen($pattern)-1;
    for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
    return $key;
} 

echo generarCodigo(6); 




 ?>



 	let timerInterval
Swal.fire({
  title: 'Auto close alert!',
  html: 'I will close in <b></b> milliseconds.',
  timer: 2000,
  timerProgressBar: true,
  onBeforeOpen: () => {
    Swal.showLoading()
    timerInterval = setInterval(() => {
      const content = Swal.getContent()
      if (content) {
        const b = content.querySelector('b')
        if (b) {
          b.textContent = Swal.getTimerLeft()
        }
      }
    }, 100)
  },
  onClose: () => {
    clearInterval(timerInterval)
  }
}).then((result) => {
  /* Read more about handling dismissals below */
  if (result.dismiss === Swal.DismissReason.timer) {
    console.log('I was closed by the timer')
  }
})



respuesta original

	Swal.fire({
		title: '¿Estás seguro?',
		text: texto_alerta,
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Aceptar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if(result.value){

			fetch(action,config)
			.then(respuesta => respuesta.json())
			.then(respuesta => {

				return alertas_ajax(respuesta);
			});
		}
	});



			/*$cedu=UsuarioModel::BuscarUsuariosModel($ced);

		if (empty($cedu) ||is_null($cedu)) 
		{
			$alerta=
				[
					"Alerta"=>"simple",
					"Titulo"=>"La Cedula ".$ced." No Existe",
					#"Texto"=>"La Cedula ".$datos['cedula']." No Existe",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
		}
		elseif ($nom!==$cedu['NOMBRES']) 
		{
			$alerta=
				[
					"Alerta"=>"simple",
					"Titulo"=>"Esta cedula no le corresponde a esta persona",
					#"Texto"=>"La Cedula ".$datos['cedula']." No Existe",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();			
		}*/

	/*"user"=>$user,"telefono"=>$tel,"direccion"=>$dir,*/
		
		
		



		java

Swal.fire({
				title: 'Estas seguro que quieres cerrar sesion?',
				text: "Estas Por cerrar sesion y salir del sistema",
				type: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Si, Salir',
				cancelButtonText: 'No, Cancel'
		}).then((result) => {

		});

	});



		/*  Exit system buttom 
	$('.btn-exit-system').on('click', function(e){
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure to close the session?',
			text: "You are about to close the session and exit the system",
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, exit!',
			cancelButtonText: 'No, cancel'
		}).then((result) => {
			if (result.value) {
				window.location="index.html";
			}
		});
	});*/
    



    php


    /*	$PeticionAjax=true;
	require_once "../config/app.php";
	
	require_once "../controller/VillaController.php";
#$especialidad=$_POST['especialidad'];
	$ins_medico = new VillaController();
	$ins_medico->BuscarVilla();*/