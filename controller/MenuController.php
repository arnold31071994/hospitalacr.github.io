<?php 
	require_once './models/MenuModel.php';
/**
 * 
 */
class MenuController extends MenuModel
{
	public function GetMenuController($privilegios)
	{
		$respuestas=MenuModel::GetMenuModel($privilegios);
		$respuestas2=MenuModel::GetSubMenuModel($privilegios);


		foreach ($respuestas as $key => $menu) 
		{
			if ($menu['titulo']=="INICIO") 
			{
				echo'<li ><a href=" '.SERVERURL.'home/"><i class="'.$menu["icono"].' fa-fw"></i> &nbsp; '.$menu['titulo'].'</a><ul>';
			}
			else
			{
				echo'<li ><a href="#" class="nav-btn-submenu"><i class="'.$menu["icono"].' fa-fw"></i> &nbsp; '.$menu['titulo'].' <i class="fa fa-chevron-down"></i></a><ul>';
			}
			
			foreach ($respuestas2 as $row => $menu2)
			{
				if ($menu["id"]==$menu2["parent_id"] ) 
				{
					#echo'';
					echo'<li class""><a href="'.SERVERURL.$menu2['enlace'].'/"><i class="'.$menu2['icono'].'"></i> &nbsp;'.$menu2['titulo'].'</a></li>';
				}
			}	echo'</ul>';		
										
		}		
		echo'</li>';		
				
	}#endGetMenuController

	

}
	