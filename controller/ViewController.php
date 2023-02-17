<?php 	

	require_once './models/ViewModel.php';

	class ViewController extends ViewModel
	{
		/*--------- Controlador obtener plantilla ---------*/
		public function ObtenerTemplateController()
		{
			return require_once './view/Template.php';
		}

		/*--------- Controlador obtener vistas ---------*/
		public function ObtenerViewController(){
			if (isset($_GET['views'])){
				$ruta=explode("/", $_GET['views']);
				$respuesta=ViewModel::ObtenerViewModel($ruta[0]);
			}else{
				$respuesta="login";
			}
			return $respuesta;
		}
		
	}










