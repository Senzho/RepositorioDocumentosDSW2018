<?php

class Util
{
	/*Valida una url existente.
		Recibe la url a comprobar.
		Regresa un valor booleano indicando el resultado.
	*/
	function url_existe($url)
	{
		$cabeceras = get_headers($url);
	    $estatus = array();
	    preg_match('/HTTP\/.* ([0-9]+) .*/', $cabeceras[0] , $estatus);
	    return ($estatus[1] == 200);
	}
	/*Genera un código de confirmación para el registro de un académico.
		Recibe un académico.
		Regresa una cadena SHA256 con el código.
	*/
	function obtener_codigo($academico)
	{
		$id = $academico['idAcademico'];
		$nombre = $academico['nombre'];
		$correo = $academico['correo'];
		$nickname = $academico['nickname'];
		$contraseña = $academico['contrasena'];
		return hash('crc32', $id . $nombre . $id . $correo . $id . $nickname . $id . $contraseña . $id);
	}
	function obtener_solicitud($id_documento, $id_fuente, $id_objetivo, $fecha)
	{
		return hash('sha256', $id_documento . $id_fuente . $id_objetivo . $fecha);
	}
	function obtener_extension($ruta)
	{
		$extension = '';
		$longitud_ruta = strlen($ruta);
		for ($i = $longitud_ruta - 1; $i > -1; $i --){
			$caracter = $ruta[$i];
			if ($caracter === '.'){
				$extension = substr($ruta, $i + 1, $longitud_ruta - $i);
				break;
			}
		}
		return $extension;
	}
}