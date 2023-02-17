<?php 
	

if ($PeticionAjax) 
{
	require_once '../models/LoginModel.php';
}
else
{
	require_once './models/LoginModel.php';
}

/**
 * 
 */
class LoginController extends LoginModel
{
	
	public function IniciarSesionController()
	{
		$usuario=MainModel::CleanString($_POST['UsuarioLog']);
		$pass=MainModel::CleanString($_POST['ClaveLog']);


		if ($usuario=="" || $pass=="") 
		{
			echo "
			<script>
				Swal.fire({
 			
  			title: 'Ocurrio Un error Inesperdo',
  			text: 'no ha Rellenado los Campos Necesarios',
  			type: 'error',
  			confirmButtonText: 'Aceptar'});
			</script>


			";
		}

		if (MainModel::VerificarDatos("[a-zA-Z0-9$@.-]{7,100}",$pass)) 
		{
			echo "
			<script>
				Swal.fire({
 			
  			title: 'Ocurrio Un error Inesperdo',
  			text: 'La CONTRASEÑA no Cumple con el formato pedido',
  			type: 'error',
  			confirmButtonText: 'Aceptar'});
			</script>


			";			
		}
		if (MainModel::VerificarDatos("[0-9-]{10,20}",$usuario)) 
		{
			echo "
			<script>
				Swal.fire({
 			
  			title: 'Ocurrio Un error Inesperdo',
  			text: 'La CEDULA no Cumple con el formato pedido',
  			type: 'error',
  			confirmButtonText: 'Aceptar'});
			</script>


			";			
		}

		$check_vet=MainModel::ConsultaSimple("SELECT * FROM vetados WHERE ban_ced='".$usuario."'");

		if ($check_vet->rowCount()==1) 
		{
			$a=$check_vet->fetch();
			echo "
			<script>
				Swal.fire({
 			
  			title: 'Ocurrio Un error Inesperdo',
  			text: 'Este usuario ha sido vetado por ".$a['ban_nota']."',
  			type: 'error',
  			confirmButtonText: 'Aceptar'});
			</script>


			";	
			
		}#endif
		else
		{	$pass=MainModel::Encryption($pass);

			$DatosLogin=["usuario"=>$usuario,"pass"=>$pass];
			$DatosCuenta=LoginModel::IniciarSesionModel($DatosLogin);
		
			if ($DatosCuenta->rowCount()==1) 
			{
				$row=$DatosCuenta->fetch();

				session_start(['name'=>'SPM']);

				$_SESSION['id_spm']=$row['id_usuario'];
				$_SESSION['nombre_spm']=$row['nombre'];
				$_SESSION['apellido_spm']=$row['apellido'];
				$_SESSION['usuario_spm']=$row['usuario'];
				$_SESSION['privilegio_spm']=$row['privilegio'];
				$_SESSION['imagen_spm']=$row['imagen'];
				//$_SESSION['img_spm']=$row['img'];
				$_SESSION['token_spm']=md5(uniqid(mt_rand(),true));
				return header("location:".SERVERURL."home/");
				
			}
			else
			{
				echo "
				<script>
					Swal.fire({
	 			
	  			title: 'Ocurrio Un error Inesperdo',
	  			text: 'Usuario o Clave incorectos',
	  			type: 'error',
	  			confirmButtonText: 'Aceptar'});
				</script>


				";
			}
		}
	}

	public function ForceLogoutController()
	{
		session_unset();
		session_destroy();
		if (headers_sent()) 
		{
			return "<script>window.location.href='".SERVERURL."login/';</script>";
		}
		else
		{
			return header("location:".SERVERURL."login/");
		}
	}

	public function CloseSesionController()
	{
		session_start(['name'=>'SPM']);
		$usuario=MainModel::Decryption($_POST['usuario']);
		$token=MainModel::Decryption($_POST['token']);

		if ($usuario==$_SESSION['usuario_spm'] && $token==$_SESSION['token_spm'] ) 
		{
			session_unset();
			session_destroy();
			$alerta=[
				"Alerta"=>"redireccionar",
				"URL"=> SERVERURL."login/"
			];
		}
		else
		{
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrió un error inesperado",
				"Texto"=>"La sesion no ha sido cerrada",
				"Tipo"=>"error"
			];
			
		}
		echo json_encode($alerta);
	}	


}

