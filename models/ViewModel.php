<?php 

	/**
	 * 
	 */
	class ViewModel
	{
		protected static function ObtenerViewModel($view)
		{
			$listablanca=["home","icon-new","ban-list","ban-new","ban-search","client-update","company","medicamentos-list","medicamentos-new","medicamentos-search","item-update","reservation-list","reservation-new","reservation-pending","reservation-search","reservation-update","user-list","reservation-reservation","user-new","user-search","user-update","invited-new","ban-new","invited-list","invited-aut","accesos-list","Reportes","privilegios-new","privilegios-list","privilegios-update","paciente-new","paciente-list","paciente-update","villa-update","diagnostico-new","paciente-search","receta-new","receta-list","receta-search"];
			#$listaBlanca=["client-list","client-new","client-search","client-update","company","item-list","item-new","item-search","item-update","reservation-list","reservation-new","reservation-pending","reservation-search","reservation-update","user-list","reservation-reservation","user-new","user-search","user-update"];

			if (in_array($view, $listablanca)) 
			{
				if (is_file("./view/modulos/".$view."-view.php")) 
				{
					$modulo="./view/modulos/".$view."-view.php";
				}
				else
				{
					$modulo="404";
				}
			}
			elseif ($view=="login" || $view=="index") 
			{
				$modulo="login";
			}
			else
			{
				$modulo="404";
			}
			return $modulo;
		}
		
	}







