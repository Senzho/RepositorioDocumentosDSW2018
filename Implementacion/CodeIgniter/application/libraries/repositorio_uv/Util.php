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
}