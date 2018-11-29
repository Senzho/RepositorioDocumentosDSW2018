<?php
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
	return hash('sha256', $id . $nombre . $id . $correo . $id . $nickname . $id . $contraseña . $id);
}
/*Valida un correo existente.
	Recibe un el correo a validar.
	Regresa un valor booleano indicando el resultado.
*/
function validar_correo($academico)
{
	$CI =& get_instance();
	$CI->load->library('email');
	$CI->email->from('Repositorio UV');
  	$CI->email->subject('Notificación de registro');
  	$CI->email->message('Bienvenido al repositorio de documentos de la Universidad Veracruzana. Para confirmar tu registro, introduce el siguiente código en la página de confirmación: ' . obtener_codigo($academico));
  	$CI->email->to($academico['correo']);
  	return $CI->email->send();
}