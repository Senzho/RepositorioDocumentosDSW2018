<?php
/*Valida un correo existente.
	Recibe un el correo a validar.
	Regresa un valor booleano indicando el resultado.
*/
function validar_correo($academico, $codigo)
{
	$CI =& get_instance();
	$CI->load->library('email');
	$CI->email->from('Repositorio UV');
  	$CI->email->subject('Notificación de registro');
  	$CI->email->message('Bienvenido al repositorio de documentos de la Universidad Veracruzana. Para confirmar tu registro, introduce el siguiente código en la página de confirmación: ' . $codigo);
  	$CI->email->to($academico['correo']);
  	return $CI->email->send();
}