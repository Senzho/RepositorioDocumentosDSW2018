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
		$this->load->view('templates/repositorio_uv/header', array('titulo' => '', 'nombre' => $academico['nombre'], 'id' => $id_academico));
		if($editar){
			$this->load->view('pages/repositorio_uv/Editar_usuario', array('id'=> $id_academico,'nombre'=>$academico['nombre'],'correo'=>$academico['correo'],'nickname'=>$academico['nickname'],'mensaje'=>''));
		}else{
			$this->load->view('pages/repositorio_uv/repositorio', array('documentos' => $documentos));
		}
	}
	/*aqui empieza la visualizacion del documento*/
	private function cargar_documento($id_academico, $id_documento){
		$academico = $this->Usuario_Modelo->obtener_usuario($id_academico);
		$this->load->view('templates/repositorio_uv/menu', array('titulo' => 'Documento'));
		$documento = $this->Documento_Modelo->obtener_documento($id_documento);
		$this->load->view('templates/repositorio_uv/header', array('titulo' => $documento['nombre'], 'nombre' => $academico['nombre'], 'id' =>$id_academico));
		if(file_exists(APPPATH . 'documentos/'.$id_documento.'.'.$documento['extension'])){
			$id_documento = $id_documento . '.' . $documento['extension'];
			$this->load->view('pages/repositorio_uv/visualizar_documento', array('idDocumento' => $id_documento));
			$this->load->view('templates/repositorio_uv/chat', array('usuarioChat' => $id_academico . '.' . $academico['nickname'], 'documentoChat' => $id_documento));
		}else{
			$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, parece que el documento seleccionado ya no existe'));
		}
	}

	/*aqui empieza la visualizacion del documento*/
	private function validar_visualizacion_descarga($id, $id_documento)
	{
		$valido = False;
		if ($this->Documento_Modelo->documento_pertenece($id, $id_documento)){
			$valido = True;
		}else if($this->Documento_Modelo->documento_es_compartido($id, $id_documento)['compartido']){
			$valido = True;
		}else{
			log_message('info', 'Intento de acceso a visualización/descarga de documento no permitido con Id: ' . $id_documento . ' por parte del usuario con Id: ' . $id . '.');
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
			}else if($pagina === 'editar'){
				if ($this->validar_edicion_documento($id, $id_documento)){
					$this->editar_documento($id,$id_documento);
				}
			}else{
				show_404();
			}
		}else{
			log_message('info', 'Intento de acceso a página: ' . $pagina . ' por usuario no autenticado.');
			redirect('repositorio_uv/Usuario_Controller/vista', 'location');
		}	
	}
	public function validar_edicion_documento($id,$id_documento){
		$valido = False;
		$documento_es_compartido = $this->Documento_Modelo->documento_es_compartido($id, $id_documento);
		if ($this->Documento_Modelo->documento_pertenece($id, $id_documento)){
			$valido = True;
		}else if($documento_es_compartido['compartido']){
			if($documento_es_compartido['edicion']){
				$valido= true;
			}else{
				log_message('info', 'Intento de acceso a edicion de documento no permitido con Id: ' . $id_documento . ' por parte del usuario con Id: ' . $id . '.');
			}
		}else{
			log_message('info', 'Intento de acceso a edicion de documento no permitido con Id: ' . $id_documento . ' por parte del usuario con Id: ' . $id . '.');
			$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, No tienes permiso para editar el documento'));
		}
		return $valido;
	}
	private function editar_documento($id_academico,$id_documento){
		$documento = $this->Documento_Modelo->obtener_documento($id_documento);
		$objetoDocumento = new Doc2Txt(APPPATH . 'documentos/' . $id_documento . '.' . $documento['extension']);
		$texto = $objetoDocumento->convertToText();
		$academico = $this->Usuario_Modelo->obtener_usuario($id_academico);
		$this->load->view('templates/repositorio_uv/menu', array('titulo' => 'Crear documento'));
		$this->load->view('templates/repositorio_uv/header', array('titulo' => '', 'nombre' => $academico['nombre'], 'id' =>$id_academico));
		$this->load->view('pages/repositorio_uv/Editar_documento',array('nombre'=> $documento['nombre'],'texto_documento'=>$texto, 'id_documento'=>$id_documento));
		$this->load->view('templates/repositorio_uv/chat', array('usuarioChat' => $id_academico . '.' . $academico['nickname'], 'documentoChat' => $id_documento));
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
		$this->load->view('pages/repositorio_uv/crear_documento',array('mensaje'=>$mensaje));
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
					if($extension ==='docx'){
						if($this->Documento_Modelo->guardar_documento_docx($resultado['id'],$texto)===True){
							$this->Documento_Modelo->guardar_documento_pdf($resultado['id'],$texto,true);
							redirect('repositorio_uv/Documento_Controller/vista', 'location');
						}else{
							$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, no se ha podido guardar tu documento'));
						}
					}else{
						if($this->Documento_Modelo->guardar_documento_pdf($resultado['id'],$texto)===True){
							$this->Documento_Modelo->guardar_documento_docx($resultado['id'],$texto);
							redirect('repositorio_uv/Documento_Controller/vista', 'location');
						}else{
							$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, no se ha podido guardar tu documento'));
						}
					}
				}
			}else{
			   $this->vista_nuevo_documento($id,'Su documento no tiene contenido');
			}
		}else{
			log_message('info', 'Intento de creación de documento por parte de usuario no autenticado.');
			redirect('repositorio_uv/Documento_Controller/vista', 'location');
		}
	}
	/*Actualiza un documento.
		Recibe los datos de un Documento por PUT.
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		Redirecciona a la vista de 'repositorio'.
	*/
	public function modificar_documento($id_documento)
	{
		$id = $this->session->userdata('id');
		if($id){
			if($this->validar_edicion_documento($id,$id_documento)){
				$nombre_documento = $this->input->post('nombre');
				$texto = $this->input->post('texto');
				if(isset($nombre_documento) && isset($texto)){
					$extension = $this->input->post('extension');
					$fecha_registro = date('Y-m-d');
					$documento = array('idDocumento' => $id_documento, 'nombre' => $nombre_documento, 'fechaRegistro' => $fecha_registro, 'idAcademico' => $id, 'habilitado' => True, 'extension' => $extension);
					$resultado = $this->Documento_Modelo->modificar_documento($documento);
					if($resultado){
						if($this->Documento_Modelo->guardar_documento_docx($id_documento,$texto,True)===True){
							$this->Documento_Modelo->guardar_documento_pdf($id_documento,$texto,true);
							redirect('repositorio_uv/Documento_Controller/vista', 'location');
						}else{
							$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, no se ha podido editar tu documento'));
						}
					}
				}else{
				   $this->vista_nuevo_documento($id,'Su documento no tiene contenido');
				}
			}else{
				log_message('info', 'Intento de edicion de documento por parte de usuario '.$id.' documento '.$id_documento.'.docx');
				redirect('repositorio_uv/Documento_Controller/vista', 'location');
			}
		}else{
			log_message('info', 'Intento de edicion de documento por parte de usuario no autenticado.');
			redirect('repositorio_uv/Documento_Controller/vista', 'location');
		}
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
				log_message('info', 'Intento de eliminación de documento con Id: ' . $id_documento . ' por parte de usuario con Id: ' . $id . ', el cual no tiene permiso para eliminar el documento.');
				$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, No tienes permiso para eliminar el documento'));
			}
		}else{
			log_message('info', 'Intento de eliminación de documento con Id: ' . $id_documento . ' por parte de usuario no autenticado.');
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
				if (isset($correo)){
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
							log_message('error', 'No se pudo registrar la solicitud de compartición del documento con Id: ' . $id_documento . ' por parte del usuario con Id: ' . $id_fuente . ' hacia el usuario con Id: ' . $objetivo['id'] . '.');
							$respuesta['compartido'] = False;
						}	
					}else{
						log_message('info', 'No se pudo encontrar al usuario con correo: ' . $correo . ' para compartir el documento con Id: ' . $id_documento . '.');
						$respuesta['compartido'] = False;
					}
				}else{
					$respuesta['compartido'] = False;
				}
				echo json_encode($respuesta);
			}else{
				log_message('info', 'Intento de solicitar compartición de documento con Id: ' . $id_documento . ' por parte de usuario con Id: ' . $id_fuente . ', el cual no es propietario del documento.');
				$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, No tienes permiso para compartir el documento'));
			}
		}else{
			log_message('info', 'Intento de solicitar compartición de documento con Id: ' . $id_documento . ' por parte de usuario no autenticado.');
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
					log_message('error', 'No se pudo compartir el documento con Id: ' . $id_documento . ' de parte del usuario con Id: ' . $id_academico . ' hacia el usuario con Id: ' . $id . '.');
					$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, no se pudo compartir el documento contigo'));
				}
			}else{
				log_message('info', 'El usuario con Id: ' . $id . ' intentó aceptar la solicitud del documento compartido con Id: ' . $id_documento . ' de parte del usuario con Id: ' . $id_academico . '.');
				$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, la solicitud no es para ti'));
			}
		}else{
			log_message('info', 'Un usuario no autenticado intentó aceptar la solicitud del documento compartido con Id: ' .$id_documento  . ' del usuario con Id: ' . $id_academico . '.');
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
	            $config['allowed_types'] = 'pdf|xlsx|docx';
	            $config['file_name'] = $resultado['id'];
	            $this->load->library('upload', $config);
	            if ($this->upload->do_upload('archivo')){
	            	$respuesta['creado'] = True;
	            	$documento['idDocumento'] = $resultado['id'];
	            	$respuesta['documento'] = $documento;
	            }else{
	            	log_message('error', 'No se pudo almacenar el documento subido con nombre: ' . $nombre . '.' . $extension . ' para el usuario con Id: ' . $id . '.');
	            	$respuesta['creado'] = False;
	            }
			}else{
				log_message('error', 'No se pudo registrar el documento subido con nombre: ' . $nombre . '.' . $extension . ' para el usuario con Id: ' . $id . '.');
				$respuesta['creado'] = False;
			}
			echo json_encode($respuesta);
		}else{
			log_message('info' . 'Un usuario no autenticado intentó subir un documento.');
			redirect('repositorio_uv/Usuario_Controller/vista', 'location');
		}
	}
	/*Ubica un documento y regresa la ruta.
		Recibe el id de un documento.
	*/
	public function descargar_documento($id_documento, $extension = 'null')
	{
		$id = $this->session->userdata('id');
		if ($id){
			if ($extension === 'docx' || $extension === 'pdf' || $extension === 'xlsx' || $extension === 'null'){
				if ($this->validar_visualizacion_descarga($id, $id_documento)){
					$extension_documento;
					if ($extension != 'null'){
						$extension_documento = $extension;
					}else{
						$documento = $this->Documento_Modelo->obtener_documento($id_documento);
						$extension_documento = $documento['extension'];
					}
					$ruta = APPPATH . 'documentos/' . $id_documento . '.' . $extension_documento;
					if (file_exists($ruta)){
						$data = file_get_contents($ruta);
						$this->load->helper('download');
				       	force_download($id_documento . '.' . $extension_documento, $data, True); 
					}else{
						$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, el formato solicitado no está disponible para descarga'));
					}
				}
			}else{
				redirect('repositorio_uv/Documento_Controller/vista', 'location');
			}
		}else{
			log_message('info', 'Un usuario no autenticado intentó descargar/visualizar el documento con Id: ' . $id_documento . '.');
			redirect('repositorio_uv/Documento_Controller/vista', 'location');
		}	
	}
}