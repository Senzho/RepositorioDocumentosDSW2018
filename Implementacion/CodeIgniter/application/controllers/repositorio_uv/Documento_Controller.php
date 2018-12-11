<?php

class Documento_Controller extends CI_Controller
{
	private function cargar_repositorio($id_academico, $propios, $editar = False)
	{
		$documentos;
		$titulo;
		if ($propios){
			$documentos = $this->Documento_Modelo->obtener_documentos($id_academico);
			$titulo = 'Mi repositorio';
		}else{
			$documentos = $this->Documento_Modelo->obtener_compartidos($id_academico);
			$titulo = 'Compartidos conmigo';
		}
		$academico = $this->Usuario_Modelo->obtener_usuario($id_academico);
		$this->load->view('templates/repositorio_uv/menu', array('titulo' => $titulo));
		$this->load->view('templates/repositorio_uv/header', array('titulo' => '', 'nombre' => $academico['nombre'], 'nickname' => $academico['nickname']));
		if($editar){
			$this->load->view('pages/repositorio_uv/Editar_usuario', array('nombre'=>$academico['nombre'],'correo'=>$academico['correo'],'nickname'=>$academico['nickname'],'mensaje'=>''));
		}else{
			$this->load->view('pages/repositorio_uv/repositorio', array('documentos' => $documentos));
		}
	}
	/*aqui empieza la visualizacion del documento*/
	public function cargar_documento($id_academico, $id_documento){
		$academico = $this->Usuario_Modelo->obtener_usuario($id_academico);
		$this->load->view('templates/repositorio_uv/menu', array('titulo' => 'Documento'));
		$documento = $this->Documento_Modelo->obtener_documento($id_documento);
		$this->load->view('templates/repositorio_uv/header', array('titulo' => $documento['nombre'], 'nombre' => $academico['nombre'], 'nickname' => $academico['nickname']));
		$this->load->view('pages/repositorio_uv/visualizar_documento', array('idDocumento' => $id_documento));
	}

	/*aqui empieza la visualizacion del documento*/
	private function validar_documento()
	{
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|max_length[50]|min_length[6]');
		$this->form_validation->set_rules('archivo', 'Ruta', 'required');
		return $this->form_Validation->run();
	}

	public function __construct()
	{
        parent::__construct();
        $this->load->model('repositorio_uv/Documento_Modelo');
        $this->load->model('repositorio_uv/Usuario_Modelo');
        $this->load->helper('url');
        $this->load->library('repositorio_uv/util');
        $this->load->library('session');
        $this->load->library('form_validation');
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
		if ($id){
			if ($pagina === 'repositorio'){
				$this->cargar_repositorio($id, True);
			}else if ($pagina === 'compartidos'){
				$this->cargar_repositorio($id, False);
			}else if($pagina === 'editar_usuario'){
				$this->cargar_repositorio($id,False,True);
			}else if($pagina === 'visualizar'){
				$this->cargar_documento($id,$id_documento);
			}
		}else{
			redirect('repositorio_uv/Usuario_Controller/vista', 'location');
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
		if ($this->session->userdata('id')){
			$respuesta['eliminado'] = $this->Documento_Modelo->borrar_documento($id_documento);
			if ($respuesta['eliminado']){
				//No sirve (borrar archivo):
				//unlink(base_url() . 'documentos/' . $id_documento);
			}
			echo json_encode($respuesta);
		}else{
			redirect('repositorio_uv/Documento_Controller/vista', 'location');
		}
	}
	public function solicitar_comparticion($id_documento)
	{
		$id_fuente = $this->session->userdata('id');
		if ($id_fuente){
			$respuesta;
			$correo = $this->input->post('correo');
			$edicion = $this->input->post('edicion');
			$objetivo = $this->Usuario_Modelo->obtener_usuario_correo($correo);
			if ($objetivo['id'] > 0){
				$this->load->library('repositorio_uv/util');
				$fecha = date('Y-m-d-u');
				$solicitud = $this->util->obtener_solicitud($id_documento, $id_fuente, $objetivo['id'], $fecha);
				$academico = $this->Usuario_Modelo->obtener_usuario($id_fuente);
				if ($this->Documento_Modelo->registrar_solicitud_documento($id_documento, $solicitud, $edicion)){
					$respuesta['compartido'] = True;
					$this->load->helper('repositorio_uv/Correo_Helper');
					enviar_solicitud_documento($academico, $objetivo, $id_documento, $fecha);
				}else{
					$respuesta['compartido'] = False;
				}	
			}else{
				$respuesta['compartido'] = False;
			}
			echo json_encode($respuesta);
		}else{
			redirect('repositorio_uv/Documento_Controller/vista', 'location');
		}
	}
	public function aceptar_solicitud($id_documento, $id_academico, $id_objetivo, $fecha)
	{
		$id = $this->session->userdata('id');
		if ($id){
			$this->load->library('repositorio_uv/util');
			$solicitud = $this->util->obtener_solicitud($id_documento, $id_academico, $id_objetivo, $fecha);
			$documento_solicitud = $this->Documento_Modelo->obtener_documento_solicitud($solicitud);
			if ($documento_solicitud['id'] === 1){
				if ($this->Documento_Modelo->compartir_documento($id_documento, $id_academico, $id_objetivo, $documento_solicitud['edicion'])){
					$this->Documento_Modelo->borrar_documento_solicitud($solicitud);
					$this->cargar_repositorio($id_academico, False, False);
				}else{
					$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, no se pudo compartir el documento contigo'));
				}
			}else{
				$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, la solicitud no es para ti'));
			}
		}else{
			redirect('repositorio_uv/Documento_Controller/vista', 'location');
		}
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
		if ($id){
			$respuesta;
			$nombre = $this->input->post('nombre');
			$ruta = $this->input->post('archivo');
			$fecha_registro = date('Y-m-d');
			$documento = array('idDocumento' => 0, 'nombre' => $nombre, 'fechaRegistro' => $fecha_registro, 'idAcademico' => $id);
			$resultado = $this->Documento_Modelo->registrar_documento($documento);
			if ($resultado['resultado']){
				$config['upload_path'] = './documentos/';
	            $config['allowed_types'] = 'pdf|xlsx|docx|pptx';
	            $config['file_name'] = $resultado['id'];
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
		}else{
			redirect('repositorio_uv/Usuario_Controller/vista', 'location');
		}
	}
	/*Ubica un documento y regresa la ruta.
		Recibe el id de un documento por POST.
	*/
	public function descargar_documento()
	{

	}
}