<?php

class Documento_Controller extends CI_Controller
{
	private function cargar_repositorio($id_academico)
	{
		$documentos = $this->Documento_Modelo->obtener_documentos($id_academico);
		$academico = $this->Usuario_Modelo->obtener_usuario($id_academico);
		$this->load->view('templates/repositorio_uv/menu');
		$this->load->view('templates/repositorio_uv/header', array('titulo' => '', 'nombre' => $academico['nombre']));
		$this->load->view('pages/repositorio_uv/repositorio', array('documentos' => $documentos));
	}

	public function __construct()
	{
        parent::__construct();
        $this->load->model('repositorio_uv/Documento_Modelo');
        $this->load->model('repositorio_uv/Usuario_Modelo');
        $this->load->helper('url');
        $this->load->library('session');
    }
	/*Carga la vista dependiendo de la página y verificando su existencia:
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		'repositorio': pagina del repositorio personal.
		'compartidos': página de documentos compartidos.
		'documento_mod': página de edición de documento.
		'documento_vista: página de visualización de documento.
	*/
	public function vista($pagina = 'repositorio', $id_documento = '0', $tipo_documento = 'null')
	{
		$id = $this->session->userdata('id');
		if ($pagina === 'repositorio'){
			$this->cargar_repositorio($id);
		}
	}
	/*Crea un nuevo documento.
		Recibe los datos de un Documento por POST.
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		Regresa una cadena JSON indicando el resultado: ['registrado': True | False].
	*/
	public function crear_documento()
	{

	}
	/*Actualiza un documento.
		Recibe los datos de un Documento por PUT.
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		Redirecciona a la vista de 'repositorio'.
	*/
	public function modificar_documento()
	{
		
	}
	/*Elimina un documento.
		Recibe el id del documento.
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		Regresa un cadena JSON indicando el resultado: ['eliminado': True | False].
	*/
	public function eliminar_documento($id_documento)
	{

	}
	/*Comparte un documento.
		Recibe el id del documento, el correo destinatario y un booleano para el permiso de edición.
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		Regresa un cadena JSON indicando el resultado: 
			['compartido': True | False, 'correo_valido': True | False].
	*/
	public function compartir_documento($id_documento, $correo, $edicion)
	{

	}
	/*Firma un documento.
		Recibe el id del documento.
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		Regresa un cadena JSON indicando el resultado: ['firmado': True | False].
	*/
	public function firmar_documento($id_documento)
	{

	}
	/*Carga un documento en el servidor.
		Recibe los datos de un documento por POST.
		Recibe un archivo.
		Regresa una cadena JSON indicando el resultado: ['creado': True | False].
	*/
	public function subir_documento()
	{
		$id = $this->session->userdata('id');
		$respuesta;
		$nombre = $this->input->post('nombre');
		$fecha_registro = date('Y-m-d');
		$documento = array('idDocumento' => 0, 'nombre' => $nombre, 'fechaRegistro' => $fecha_registro, 'idAcademico' => $id);
		$resultado = $this->Documento_Modelo->registrar_documento($documento);
		if ($resultado['resultado']){
			$config['upload_path'] = './documentos/';
            $config['allowed_types'] = 'pdf|xlsx|docx|pptx';
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('archivo')){
            	$respuesta['creado'] = True;
            	$documento['idDocumento'] = $resultado['id'];
            	$respuesta['documento'] = $documento;
            }else{
            	$respuesta['creado'] = False;
            }
		}else{
			$respuesta['creado'] = False;
		}
		echo json_encode($respuesta);
	}
	/*Ubica un documento y regresa la ruta.
		Recibe el id de un documento por POST.
	*/
	public function descargar_documento()
	{

	}
}