<?php
/*
Archivo de clave privada: [id_academico]key.key
Archivo de clave publica: [id_academico]key.crt
Archivo de firma: [id_academico]signature[id_documento].sign
*/
class OpenSSL
{
	function generar_claves($id_academico)
	{
		$salida = shell_exec("openssl req -nodes -x509 -sha256 -newkey rsa:4096 -keyout '" . APPPATH . "llaves_privadas/" . $id_academico . "key.key' -out '" . APPPATH . "llaves_publicas/" . $id_academico . "key.crt' -days 365 -subj '" . "/C=MX/ST=Veracruz/L=Xalapa/O=RepositorioUV/OU=firmas/CN=" . $id_academico . "key.key'");
		return $salida != null;
	}
	function verificar($id_academico, $id_documento, $extension)
	{
		$salida = shell_exec("openssl dgst -sha256 -verify <(openssl x509 -in '" . APPPATH . "llaves_publicas/" . $id_academico . "key.crt' -pubkey -noout) -signature " . APPPATH . "firmas/" . $id_academico . "signature" . $id_documento . ".sign " . APPPATH . "documentos/" . $id_documento . "." . $extension);
		return $salida === 'Verified OK';
	}
	function firmar($id_academico, $id_documento, $extension)
	{
		$salida = shell_exec("openssl dgst -sha256 -sign '" . APPPATH . "llaves_privadas/" . $id_academico . "key.key' -out " . APPPATH . "firmas/" . $id_academico . "signature" . $id_documento . ".sign " . APPPATH . "documentos/" . $id_documento . "." . $extension);
		return $salida === null;
	}
}