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
}