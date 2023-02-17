<?php 


if ($PeticionAjax) 
{
	require_once '../config/SERVER.php';
}
else
{
	require_once './config/SERVER.php';
}


/**
 * 
 */
class MainModel
{
	
	protected static function Conectar()
	{
		/*try 
		{

			$conexion= new PDO(SGBD,USER,PASS);
			$conexion->exec("SET CHARACTER SET utf8");
			return $conexion;
			
		} catch (Exception $e) 
		{ 	 		session_unset();
					session_destroy();
			if (headers_sent()) 
			{


				return "<script>

						Swal.fire({
							title: alerta.Titulo,
							text: alerta.Texto,
							type: alerta.Tipo,
							confirmButtonText: 'Aceptar'
						}).then((result) => {
							if(result.value)
							{
							window.location.href='".SERVERURL."login/';	
					
							}
						});
				</script>";
			}
			else
			{
				return header("location:".SERVERURL."login/");
			}
			
		}*/
		$conexion= new PDO(SGBD,USER,PASS);
		$conexion->exec("SET CHARACTER SET utf8");
		return $conexion;
	}

	protected static function ConectarJCE()
	{
		$conexion= new PDO(JCE,USER2,PASS2);
		$conexion->exec("SET CHARACTER SET utf8");
		return $conexion;
	}

	protected static function ConsultaSimple($consulta)
	{
		$sql=self::Conectar()->prepare($consulta);
		$sql->execute();
		return $sql;
	}

	protected static function ConsultaJCE($consulta)
	{
		$sql=self::ConectarJCE()->prepare($consulta);
		$sql->execute();
		return $sql;
	}

	protected static function ConectarSQL()
	{
		$conexion= new PDO(SQL,SQLUSER,SQLPASS);
		#$conexion->exec("SET CHARACTER SET utf8");
		return $conexion;
	}

                    /*ENCRIPTAR CADENAS*/
	public function Encryption($string)
	{
		$output=FALSE;
		$key=hash('sha256', SECRET_KEY);
		$iv=substr(hash('sha256', SECRET_IV), 0, 16);
		$output=openssl_encrypt($string, METHOD, $key, 0, $iv);
		$output=base64_encode($output);
		return $output;
	}
						/*DESENCRIPTAR CADENAS*/
	protected static function Decryption($string)
	{
		$key=hash('sha256', SECRET_KEY);
		$iv=substr(hash('sha256', SECRET_IV), 0, 16);
		$output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
		return $output;
	}

			/*GENERADOR DE CODIGO ALEATORIO PUEDE USARSE PARA LOS ACESOS*/
	protected static function RandomNumber($letra,$numero,$longitud)
	{
		for ($i=1; $i <= $longitud; $i++) 
		{ 
			$random = rand(0);
			$letra.=$random;
		}
		return $letra."-".$numero;
	}

	protected static function codigo($letra,$numero,$longitud)
	{
		for ($i=1; $i <= $longitud; $i++) 
		{ 
			$random = rand(0,0);
			//$letra.=$random;
			$numero=$random.$numero;

		}
		return $letra."-".$numero;
	}

	protected static function CleanString($cadena)
	{
		
		$cadena=str_ireplace("<script>", "", $cadena);
		$cadena=str_ireplace("</script>", "", $cadena);
		$cadena=str_ireplace("script src", "", $cadena);
		$cadena=str_ireplace("SELECT * FROM", "", $cadena);
		$cadena=str_ireplace("INSERT INTO", "", $cadena);
		$cadena=str_ireplace("DELETE FROM", "", $cadena);
		$cadena=str_ireplace("DROP TABLE", "", $cadena);
		$cadena=str_ireplace("TRUNCATE TABLE", "", $cadena);
		$cadena=str_ireplace("SHOW DATABASE", "", $cadena);
		$cadena=str_ireplace("USE DATABASE", "", $cadena);
		$cadena=str_ireplace("<php?", "", $cadena);
		$cadena=str_ireplace("?>", "", $cadena);
		$cadena=str_ireplace("--", "", $cadena);
		$cadena=str_ireplace("[", "", $cadena);
		$cadena=str_ireplace("]", "", $cadena);
		$cadena=str_ireplace("^", "", $cadena);
		$cadena=str_ireplace("::", "", $cadena);
		$cadena=str_ireplace(";", "", $cadena);
		$cadena=trim($cadena); 
		$cadena=stripcslashes($cadena);

		return $cadena;
	}

	protected static function VerificarDatos($filtro,$cadena)
	{
		if (preg_match("/^".$filtro."$/", $cadena)) 
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	protected static function VerificarFecha($fecha)
	{
		$valores=explode("*", $fecha);
		if (count($valores)==3 && checkdate($valores[1],$valores[2], $valores[0])) 
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	protected static function CodigoRamdon($longitud) 
	{
	    $key = '';
	    $pattern = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXVZ';
	    $max = strlen($pattern)-1;
	    for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
	    return $key;
	} 

	protected static function PaginadorTablas($pag,$npag,$url,$botones)
	{
		$tabla='<nav aria-label="Page navigation example">
		<ul class="pagination justify-content-center">';

		if ($pag==1) 
		{
			$tabla.='<li class="page-item disabled">
						<a class="page-link">
							<i class="fas fa-angle-double-left"></i>
						</a>
					</li>';
		}
		else
		{
			$tabla.='<li class="page-item">
						<a class="page-link" href="'.$url.'1/"><i class="fas fa-angle-double-left"></i></a>
					</li>
					<li class="page-item ">
						<a class="page-link" href="'.$url.($pag-1).'/" >Anterior</a>
					</li>';
		}

		$ci=0;
		for ($i=$pag; $i <=$npag ; $i++) 
		{ 
			if ($ci>=$botones) 
			{
				break;
			}
			if ($pag==$i) 
			{
				$tabla.='<li class="page-item ">
						<a class="page-link active" href="'.$url.$i.'/">'.$i.'</a>
					</li>';
			}
			else
			{
				$tabla.='<li class="page-item ">
						<a class="page-link " href="'.$url.$i.'/">'.$i.'</a>
					</li>';

			}
		}#endFOR



		if ($pag==$npag) 
		{
			$tabla.='<li class="page-item disabled">
						<a class="page-link">
							<i class="fas fa-angle-double-right"></i>
						</a>
					</li>';
		}
		else
		{
			$tabla.='<li class="page-item">
						<a class="page-link" href="'.$url.($pag+1).'/">Proximo</a>
					</li>
					<li class="page-item ">
						<a class="page-link" href="'.$url.'/" >
							<i class="fas fa-angle-double-right"></i>
						</a>
					</li>';
		}

		$tabla.='</ul></nav>';
		return $tabla;

	}
}#ENDCLASS