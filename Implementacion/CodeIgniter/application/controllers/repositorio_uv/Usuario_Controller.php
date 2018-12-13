<?php

class Usuario_Controller extends CI_Controller
{
	private function mostrar_login($mensaje)
	{
		$this->load->view('pages/repositorio_uv/Login', array('mensaje' => $mensaje));
	}
	private function mostrar_confirmacion($mensaje)
	{
		$academico = $this->Usuario_Modelo->obtener_usuario_proceso($this->session->flashdata('idp'));
		$this->load->view('pages/repositorio_uv/Confirmacion_Registro', array('correo' => $academico['correo'], 'mensaje' => $mensaje));
	}
	private function mostrar_registro_usuario($datos_usuario)
	{
		$this->load->view('pages/repositorio_uv/Registrar_usuario', array('nombre'=>$datos_usuario['nombre'],'correo'=>$datos_usuario['correo'],'nickname'=>$datos_usuario['nickname'],'mensaje'=>$datos_usuario['mensaje']));
	}
	private function mostrar_bienvenida($mensaje)
	{
		$id = $this->session->userdata('id');
		$academico = $this->Usuario_Modelo->obtener_usuario($id);
		$this->load->view('pages/repositorio_uv/Ingresar', array('nombre' => $academico['nombre'], 'mensaje' => $mensaje));
	}
	private function generar_llaves($id_academico)
	{
		$this->load->model('repositorio_uv/Documento_Modelo');
		return $this->Documento_Modelo->generar_llaves($id_academico);
	}

	public function __construct()
	{
        parent::__construct();
        $this->load->model('repositorio_uv/Usuario_Modelo');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('repositorio_uv/util');
        $this->load->library('form_validation');
        $this->load->helper('file');
        $this->load->library('session');
    }
	/*Carga la vista dependiendo de la página y verificando su existencia:
		'login': pagina de inicio de sesión.
		'cuenta_nueva': página de registro de usuario.
		'cuenta_mod': página de actualización de la cuenta.
			Consulta id de usuario en caché, si no se encuentra, redirije
			a la vista de 'login'.
		'confirmacion': página de confirmación de registro.
	*/
	public function vista($pagina = 'login', $mensaje = '')
	{
		if ($pagina === 'login')
		{
			if ($this->session->userdata('id')){
				redirect('repositorio_uv/Documento_Controller/vista/repositorio');
			}else{
				$this->mostrar_login('');
			}
		}else if($pagina === 'registrar_usuario'){
			$this->mostrar_registro_usuario(array('nombre'=>'','correo'=>'','nickname'=>'','mensaje'=>''));
		}else if ($pagina === 'confirmacion'){
			if ($this->session->flashdata('idp')){
				$this->session->keep_flashdata('idp');
				$this->mostrar_confirmacion('');
			}else{
				$this->mostrar_login('');
			}
		}else if ($pagina === 'ingresar'){
			if ($this->session->userdata('id')){
				$this->mostrar_bienvenida($mensaje);
			}else{
				$this->mostrar_login('');
			}
		}
	}
	/*Crea la sesión del usuario.
		Recibe un usuario y contraseña (hash) por POST. Redirecciona al
		repositorio, y en caso de no ser un usuario válido, carga la
		vista de 'login' con un mensaje.
	*/
	public function iniciar_sesion()
	{
		$usuario = $this->input->post('usuario');
		$contraseña = $this->input->post('hash');
		$academico = $this->Usuario_Modelo->iniciar_sesion($usuario, $contraseña);
		$id = $academico['id'];
		if ($id > 0)
		{
			$this->session->set_userdata('id', $id);
			redirect('repositorio_uv/Documento_Controller/vista/repositorio');
		}else{
			$this->mostrar_login('Los sentimos, no podemos encontrar tu usuario, verifica que tus datos sean correctos');
		}
	}
	/*Termina la sesión del usuario.
		Redirije a la vista de 'login'.
	*/
	public function cerrar_sesion()
	{
		$this->session->unset_userdata('id');
		redirect('repositorio_uv/Usuario_Controller/vista/login');
	}
	private function validar_datos_usuario(){
		$this->form_validation->set_rules(
			'nombre','nombre',
			'trim|required|min_length[3]|max_length[50]',
			array(
				'required' => 'El campo %s debe ser llenado',
				'min_length' => 'El campo %s debe tener al menos 3 caracteres.',
				'max_length' => 'El Campo %s debe contener un maximo de 50 caracteres.'
			)
		);
		$this->form_validation->set_rules(
			'nickname','nickname',
			'trim|required|min_length[3]|max_length[50]',
			array(
				'required' => 'El campo %s debe ser llenado',
				'min_length' => 'El campo %s debe tener al menos 3 caracteres.',
				'max_length' => 'El Campo %s debe contener un maximo de 50 caracteres.'
			)
		);
		$this->form_validation->set_rules(
			'correo','correo',
			'trim|required|valid_email',
			array(
				'required' => 'El campo %s debe ser llenado.',
				'valid_email' => 'El %s no es valido.',
			)
		);
		$this->form_validation->set_rules(
			'contrasena','contraseña',
			'trim|required',
			array(
				'required' => 'El campo %s debe ser llenado.',
			)
		);
		$this->form_validation->set_rules(
			'confirmar','confirmar',
			'trim|required|matches[contrasena]',
			array(
				'matches' => 'Las contraseñas no coinciden, intentelo de nuevo.',
				'required' => 'necesita llenar el campo contraseña'
			)
		);
		$datos_validos = false;
		if($this->form_validation->run()){
			$datos_validos = true;
		}
		return $datos_validos;
	}
	public function subir_foto($id){
		$foto_subida = false;
		if(file_exists ('./usuarios/'.$id.'.jpg')){
			unlink('./usuarios/'.$id.'.jpg');
		}
		$config['upload_path'] = './usuarios/';
        $config['allowed_types'] = 'jpg';
        $config['file_name'] = $id;
        $this->load->library('upload', $config);
        if(!$this->upload->do_upload('userfile'))
        {
        	$error = array('error' => $this->upload->display_errors());
        	echo $error['error'];
        }else{
        	$foto_subida = true;
        }
        return $foto_subida;
	}
	/*Crea una nueva cuenta de usuario.
		Recibe los datos de la cuenta por POST. Registra la cuenta y redirije a la vista de
		verificación de registro.
		En caso de no lograr el registro, carga la misma vista con un mensaje.
	*/
	public function crear_usuario()
	{
		$nombre = $this->input->post('nombre');
		$correo = $this->input->post('correo');
		$nickname = $this->input->post('nickname');
		$contrasena = $this->input->post('contrasena');
		$confirmar = $this->input->post('confirmar');
		if ($this->validar_datos_usuario()) {
			$academico = array('idAcademico'=>0,'nombre'=>$nombre,'correo'=>$correo,'nickname'=>$nickname,'contrasena'=>$confirmar);
			$this->load->library('repositorio_uv/util');
			$codigo = $this->util->obtener_codigo($academico);
			$academico['codigo'] = $codigo;
			$usuario_registrado = $this->Usuario_Modelo->registrar_usuario_proceso($academico);
			if($usuario_registrado['resultado']){
				$this->session->set_flashdata('idp', $usuario_registrado['id']);
				if($this->subir_foto($nickname)){
					$this->load->helper('repositorio_uv/Correo_Helper');
					validar_correo($academico, $codigo);
					redirect('repositorio_uv/Usuario_Controller/vista/confirmacion');
				}else{
					$this->mostrar_registro_usuario(array('nombre'=>$nombre,'correo'=>$correo,'nickname'=>$nickname, 'mensaje'=> 'No pudo registrarse la foto'));
				}
			}else{
				$this->mostrar_registro_usuario(array('nombre'=>$nombre,'correo'=>$correo,'nickname'=>$nickname, 'mensaje'=> "No pudo registrarse el usuario"));
			}
		}else{
			$this->mostrar_registro_usuario(array('nombre'=>$nombre,'correo'=>$correo,'nickname'=>$nickname, 'mensaje'=> 'Faltan datos para registrar'));
		}
	}
	/*Actualiza una cuenta de usuario.
		Recibe los datos de la cuenta por PUT. Verifica el id del usuario en caché, y en caso de no
		encontrarlo, redirije a la vista de 'login'.
		Actualiza el registro, y en caso de no lograrlo, carga la misma vista con un mensaje.
	*/
	private function mostrar_edicion_usuario($datos_usuario){
		$this->load->view('pages/repositorio_uv/Editar_usuario', array('nombre'=>$datos_usuario['nombre'],'correo'=>$datos_usuario['correo'],'nickname'=>$datos_usuario['nickname'],'mensaje'=>$datos_usuario['mensaje']));
	}
	public function editar_usuario()
	{
		if($this->session->userdata('id')){
			$id = $this->session->userdata('id');
			$nombre = $this->input->post('nombre');
			$nickname = $this->input->post('nickname');
			$correo = $this->input->post('correo');
			$contrasena = $this->input->post('contrasena');
			$confirmar = $this->input->post('confirmar');	
			if($this->validar_datos_usuario()){
				$academico = array('idAcademico'=> $id,'nombre'=>$nombre,'correo'=>$correo,'nickname' =>$nickname,'contrasena'=>$contrasena);
				if($this->Usuario_Modelo->editar_usuario($academico) === true){
					if(isset($_FILES) && @$_FILES['userfile']['error'] == '0'){
				    	$this->subir_foto($id);  
				    }
				    $this->load->view('templates/repositorio_uv/menu', array('titulo' => 'Usuario editado'));
					$this->load->view('templates/repositorio_uv/header', array('titulo' => '', 'nombre' => $academico['nombre'], 'id'=>$id));
					$this->mostrar_edicion_usuario(array('nombre'=>$nombre,'correo'=>$correo,'nickname'=>$nickname,'mensaje'=>'Usuario editado exitosamente'));
				}else{
					$this->load->view('templates/repositorio_uv/menu', array('titulo' => 'Usuario editado'));
					$this->load->view('templates/repositorio_uv/header', array('titulo' => '', 'nombre' => $academico['nombre'], 'id'=>$id));
					$this->mostrar_edicion_usuario(array('nombre'=>$nombre,'correo'=>$correo,'nickname'=>$nickname,'mensaje'=>'El usuario o ha podido editarse, verificar datos ingresados'));
				}
			}else{
				$this->load->view('templates/repositorio_uv/menu', array('titulo' => 'Usuario editado'));
					$this->load->view('templates/repositorio_uv/header', array('titulo' => '', 'nombre' => $academico['nombre'], 'id'=>$id));
				$this->mostrar_edicion_usuario(array('nombre'=>$nombre,'correo'=>$correo,'nickname'=>$nickname,'mensaje'=>'los datos del usuario no son validos'));
				//echo "uno o mas datos son invalidos, favor de verificar";
			}
		}else{
			redirect('repositorio_uv/Usuario_Controller/vista');
		}
	}
	/*Confirma el código de registro.
		Recibe un código de registro.
		Redirige a la misma vista de 'repositorio'.
	*/
	public function confirmar_registro()
	{
		$codigo = $this->input->post('codigo');
		$id = $this->session->flashdata('idp');
		$academico = $this->Usuario_Modelo->obtener_usuario_proceso($id);
		if ($academico['codigo'] === $codigo){
			$this->Usuario_Modelo->eliminar_usuario_proceso($id);
			unset($academico['codigo']);
			$registro = $this->Usuario_Modelo->registrar_usuario($academico);
			if ($registro['resultado']){
				$mensaje = $this->generar_llaves($registro['id']) ? '' : 'Ocurrió un error al generar tus llaves para firma'
				$this->session->set_userdata('id', $registro['id']);
				if(file_exists ('./usuarios/'.$academico['nickname'].'.jpg')){
					rename ('./usuarios/'.$academico['nickname'].'.jpg', './usuarios/'.$registro['id'].'.jpg'); 
				}
				redirect('repositorio_uv/Usuario_Controller/vista/ingresar/' . $mensaje);
			}else{
				$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, ocurrió un error al registrarte'));
			}
		}else{
			$this->session->keep_flashdata('idp');
			$this->mostrar_confirmacion('Lo sentimos, el código es incorrecto');
		}
	}
	public function enviar_correo()
	{
		$id = $this->session->flashdata('idp');
		if ($id){
			$this->session->keep_flashdata('idp');
			$academico = $this->Usuario_Modelo->obtener_usuario_proceso($id);
			$this->load->helper('repositorio_uv/Correo_Helper');
			validar_correo($academico, $academico['codigo']);
			$this->mostrar_confirmacion('');
		}else{
			$this->mostrar_login('');
		}
	}
}