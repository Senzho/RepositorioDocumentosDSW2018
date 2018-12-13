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
function enviar_solicitud_documento($academico, $correo, $id_documento, $fecha){
	$CI =& get_instance();
	$CI->load->library('email');
	$CI->email->from('Repositorio UV');
  	$CI->email->subject('Notificación de solicitud de documento');
  	$CI->email->message($academico['nombre'] . ' desea compartirte un documento, ingresa al siguiente enlace para acaptarlo: ' . base_url() . 'index.php/repositorio_uv/Documento_Controller/aceptar_solicitud/' . $id_documento . '/' . $academico['id'] . '/' . $fecha);
  	$CI->email->to($correo);
  	return $CI->email->send();
}