<?php
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/controllers/api_example/Person.php';
class Firma extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('firma_digital/openssl');
	}

	public function verificar_firma_get()
	{
		$id_academico = $this->get('id_academico');
		$id_documento = $this->get('id_documento');
		$extension = $this->get('extension');
		$verificacion = $this->openssl->verificar($id_academico, $id_documento, $extension);
		$respuesta['verificado'] = $verificacion;
		$this->response($respuesta, 200);
	}
	public function generar_claves_post()
	{
		$id_academico = $this->get('id_academico');
		$generacion = $this->openssl->generar_claves($id_academico);
		$respuesta['generadas'] = $generacion;
		$this->response($respuesta, 200);
	}
	public function firmar_post()
	{
		$id_academico = $this->get('id_academico');
		$id_documento = $this->get('id_documento');
		$extension = $this->get('extension');
		$firma = $this->openssl->firmar($id_academico, $id_documento, $extension);
		$respuesta['firmado'] = $firma;
		$this->response($respuesta, 200);
	}
}