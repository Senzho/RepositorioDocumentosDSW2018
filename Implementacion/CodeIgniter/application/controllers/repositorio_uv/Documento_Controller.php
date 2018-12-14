<?php
require APPPATH.'third_party/phpword/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
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
		$this->load->view('templates/repositorio_uv/header', array('titulo' => '', 'nombre' => $academico['nombre'], 'id' => $id_academico));
		if($editar){
			$this->load->view('pages/repositorio_uv/Editar_usuario', array('id'=> $id_academico,'nombre'=>$academico['nombre'],'correo'=>$academico['correo'],'nickname'=>$academico['nickname'],'mensaje'=>''));
		}else{
			$this->load->view('pages/repositorio_uv/repositorio', array('documentos' => $documentos));
		}
	}
	/*aqui empieza la visualizacion del documento*/
	public function cargar_documento($id_academico, $id_documento){
		$academico = $this->Usuario_Modelo->obtener_usuario($id_academico);
		$this->load->view('templates/repositorio_uv/menu', array('titulo' => 'Documento'));
		$documento = $this->Documento_Modelo->obtener_documento($id_documento);
		$this->load->view('templates/repositorio_uv/header', array('titulo' => $documento['nombre'], 'nombre' => $academico['nombre'], 'id' =>$id_academico));
		if(file_exists(APPPATH . 'documentos/'.$id_documento.'.docx')){
			$id_documento = $id_documento . '.docx';
		}else if(file_exists(APPPATH . 'documentos/'.$id_documento.'.xlsx')){
			$id_documento = $id_documento . '.xlsx';
		}else if (file_exists(APPPATH . 'documentos/'.$id_documento.'.pdf')){
			$id_documento = $id_documento . '.pdf';
		}
		$this->load->view('pages/repositorio_uv/visualizar_documento', array('idDocumento' => $id_documento));
	}

	/*aqui empieza la visualizacion del documento*/
	private function validar_documento()
	{
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|max_length[50]|min_length[6]');
		$this->form_validation->set_rules('archivo', 'Ruta', 'required');
		return $this->form_Validation->run();
	}
	private function validar_visualizacion_descarga($id, $id_documento)
	{
		$valido = False;
		if ($this->Documento_Modelo->documento_pertenece($id, $id_documento)){
			$valido = True;
		}else if($this->Documento_Modelo->documento_es_compartido($id, $id_documento)){
			$valido = True;
		}else{
			$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, No tienes permiso para visualizar el documento'));
		}
		return $valido;
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
        //$this->load->library('repositorio_uv/word');
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
				if ($this->validar_visualizacion_descarga($id, $id_documento)){
					$this->cargar_documento($id,$id_documento);
				}
			}else if($pagina === 'crear_documento'){
				$this->vista_nuevo_documento($id);
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
	public function vista_nuevo_documento($id_academico,$mensaje = '',$texto = '')
	{
		$academico = $this->Usuario_Modelo->obtener_usuario($id_academico);
		$this->load->view('templates/repositorio_uv/menu', array('titulo' => 'Crear documento'));
		$this->load->view('templates/repositorio_uv/header', array('titulo' => '', 'nombre' => $academico['nombre'], 'id' =>$id_academico));
		$this->load->view('pages/repositorio_uv/crear_documento',array('mensaje'=>$mensaje,'texto'=>$texto));
	}
	private function guardar_documento_docx($id_documento, $texto){
		$documento = new PhpWord();
		$seccion = $documento->addSection();
		$texto = explode("!--!", $texto);
		$tamano = sizeof($texto);
		for ($i=0; $i < $tamano; $i++) { 
			if(strlen(strstr($texto[$i], 'h1'))){
				$seccion->addText(
						htmlspecialchars(
							strip_tags(html_entity_decode($texto[$i]))
						),
						array('name' => 'Arial', 'size' => '20', 'bold' => 'true')
				);
			}else if(strlen(strstr($texto[$i], 'p'))){
				$seccion->addText(
					htmlspecialchars(
				 		strip_tags(html_entity_decode($texto[$i]))
					),
					array('name' => 'Arial', 'size' => '12', 'bold' => 'false')
				);	
			}
		}
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, 'Word2007');
		$objWriter->save(APPPATH.'documentos'.'/'.$id_documento.'.docx');
		$documento_guardado = False;
		if(file_exists(APPPATH.'documentos'.'/'.$id_documento.'.docx')){
			$documento_guardado = True;
		}
		return $documento_guardado;
	}
	public function crear_documento()
	{
		$id = $this->session->userdata('id');
		if($id){
			$nombre_documento = $this->input->post('nombre');
			$texto = $this->input->post('texto');
			if(isset($nombre_documento) && isset($texto)){
				$extension = $this->input->post('extension');
				$fecha_registro = date('Y-m-d');
				$documento = array('idDocumento' => 0, 'nombre' => $nombre_documento, 'fechaRegistro' => $fecha_registro, 'idAcademico' => $id, 'habilitado' => True, 'extension' => $extension);
				$resultado = $this->Documento_Modelo->registrar_documento($documento);
				if($resultado['resultado']){
					if($this->guardar_documento_docx($resultado['id'],$texto)===True){
						redirect('repositorio_uv/Documento_Controller/vista', 'location');
					}else{
						$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, no se ha podido guardar tu documento'));
					}
				}
			}
			else{
			   vista_nuevo_documento($id,'Su documento no tiene contenido');
			}
		}else{
			redirect('repositorio_uv/Documento_Controller/vista', 'location');
		}
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
		$id = $this->session->userdata('id');
		if ($id){
			if ($this->Documento_Modelo->documento_pertenece($id, $id_documento)){
				$documento = $this->Documento_Modelo->obtener_documento($id_documento);
				$respuesta['eliminado'] = $this->Documento_Modelo->borrar_documento($id_documento);
				if ($respuesta['eliminado']){
					unlink(APPPATH . 'documentos/' . $id_documento . '.' . $documento['extension']);
				}
				echo json_encode($respuesta);
			}else{
				$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, No tienes permiso para eliminar el documento'));
			}
		}else{
			redirect('repositorio_uv/Documento_Controller/vista', 'location');
		}
	}
	public function solicitar_comparticion($id_documento)
	{
		$id_fuente = $this->session->userdata('id');
		if ($id_fuente){
			if ($this->Documento_Modelo->documento_pertenece($id_fuente, $id_documento)){
				$respuesta;
				$correo = $this->input->post('correo');
				$edicion = $this->input->post('edicion');
				$objetivo = $this->Usuario_Modelo->obtener_usuario_correo($correo);
				if ($objetivo['id'] > 0){
					$this->load->library('repositorio_uv/util');
					$fecha = date('Y-m-d-B');
					$solicitud = $this->util->obtener_solicitud($id_documento, $id_fuente, $objetivo['id'], $fecha);
					$academico = $this->Usuario_Modelo->obtener_usuario($id_fuente);
					if ($this->Documento_Modelo->registrar_solicitud_documento($id_documento, $solicitud, $edicion)){
						$respuesta['compartido'] = True;
						$this->load->helper('repositorio_uv/Correo_Helper');
						enviar_solicitud_documento($academico, $correo, $id_documento, $fecha);
					}else{
						$respuesta['compartido'] = False;
					}	
				}else{
					$respuesta['compartido'] = False;
				}
				echo json_encode($respuesta);
			}else{
				$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, No tienes permiso para compartir el documento'));
			}
		}else{
			redirect('repositorio_uv/Documento_Controller/vista', 'location');
		}
	}
	public function aceptar_solicitud($id_documento, $id_academico, $fecha)
	{
		$id = $this->session->userdata('id');
		if ($id){
			$this->load->library('repositorio_uv/util');
			$solicitud = $this->util->obtener_solicitud($id_documento, $id_academico, $id, $fecha);
			$documento_solicitud = $this->Documento_Modelo->obtener_documento_solicitud($solicitud);
			if ($documento_solicitud['id'] === 1){
				if ($this->Documento_Modelo->compartir_documento($id_documento, $id_academico, $id, $documento_solicitud['edicion'])){
					$this->Documento_Modelo->borrar_documento_solicitud($solicitud);
					redirect('repositorio_uv/Documento_Controller/vista/compartidos', 'location');
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
		$id_fuente = $this->session->userdata('id');
		if ($id_fuente){
			if($this->Documento_Modelo->documento_pertenece($id_fuente, $id_documento)){
				$documento = $this->Documento_Modelo->obtener_documento($id_documento);
				$firmado = $this->Documento_Modelo->firmar_documento($id_fuente, $id_documento, $documento['extension']);
				$respuesta['firmado'] = $firmado;
				echo json_encode($respuesta);
			}else{
				$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, no tienes permiso para firmar el documento'));
			}
		}else{
			redirect('repositorio_uv/Documento_Controller/vista', 'location');
		}
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
			$ruta = $this->input->post('ruta');
			$fecha_registro = date('Y-m-d');
			$this->load->library('repositorio_uv/util');
			$extension = $this->util->obtener_extension($ruta);
			$documento = array('idDocumento' => 0, 'nombre' => $nombre, 'fechaRegistro' => $fecha_registro, 'idAcademico' => $id, 'habilitado' => True, 'extension' => $extension);
			$resultado = $this->Documento_Modelo->registrar_documento($documento);
			if ($resultado['resultado']){
				$config['upload_path'] = APPPATH . 'documentos';
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
		Recibe el id de un documento.
	*/
	public function descargar_documento($id_documento)
	{
		$id = $this->session->userdata('id');
		if ($id){
			if ($this->validar_visualizacion_descarga($id, $id_documento)){
				$documento = $this->Documento_Modelo->obtener_documento($id_documento);
				$data = file_get_contents(APPPATH . 'documentos/' . $id_documento . '.' . $documento['extension']);
				$this->load->helper('download');
		       	force_download($id_documento . '.' . $documento['extension'], $data, True); 
			}
		}else{
			redirect('repositorio_uv/Usuario_Controller/vista', 'location');
		}	
	}
}