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
		$cmd = "openssl req -nodes -x509 -sha256 -newkey rsa:4096 -keyout " . escapeshellarg(APPPATH . "llaves_privadas/" . $id_academico . "key.key") . " -out " . escapeshellarg(APPPATH . "llaves_publicas/" . $id_academico . "key.crt") . " -days 365 -subj " . escapeshellarg("/C=MX/ST=Veracruz/L=Xalapa/O=RepositorioUV/OU=firmas/CN=" . $id_academico . "key.key") . " 2>&1";
		$salida = shell_exec($cmd);
		return $salida != null;
	}
	function verificar($id_academico, $id_documento, $extension)
	{
		$cmd = "openssl dgst -sha256 -verify <(openssl x509 -in " . escapeshellarg(APPPATH . "llaves_publicas/" . $id_academico . "key.crt") . " -pubkey -noout) -signature " . escapeshellarg(APPPATH . "firmas/" . $id_academico . "signature" . $id_documento . ".sign ") . " " . escapeshellarg(APPPATH . "documentos/" . $id_documento . "." . $extension) . " 2>&1";
		$salida = shell_exec($cmd);
		return $salida === "Verified OK";
	}
	function firmar($id_academico, $id_documento, $extension)
	{
		$cmd = "openssl dgst -sha256 -sign " . escapeshellarg(APPPATH . "llaves_privadas/" . $id_academico . "key.key") . " -out " . escapeshellarg(APPPATH . "firmas/" . $id_academico . "signature" . $id_documento . ".sign") . " " . escapeshellarg(APPPATH . "documentos/" . $id_documento . "." . $extension) . " 2>&1";
		$salida = shell_exec($cmd);
		return $salida !== "";
	}
}