<?php

class Usuario_Controller extends CI_Controller
{
	private function mostrar_login($mensaje)
	{
		$this->load->view('pages/repositorio_uv/Login', array('mensaje' => $mensaje));
	}
	private function mostrar_registro_usuario($datos_usuario){
		$this->load->view('pages/repositorio_uv/Registrar_usuario', array('nombre'=>$datos_usuario['nombre'],'correo'=>$datos_usuario['correo'],'nickname'=>$datos_usuario['nickname'],'mensaje'=>$datos_usuario['mensaje']));
	}

	public function __construct()
	{
        parent::__construct();
        $this->load->model('repositorio_uv/Usuario_Modelo');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
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
	public function vista($pagina = 'login')
	{
		if ($pagina === 'login')
		{
			if ($this->session->userdata('id')){
				redirect('repositorio_uv/Documento_Controller/vista/repositorio');
			}else{
				$this->mostrar_login('');
			}
		}else if($pagina === 'registrar_usuario')
		{
			$this->mostrar_registro_usuario(array('nombre'=>'','correo'=>'','nickname'=>'','mensaje'=>''));
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
		$contraseña = $this->input->post('contraseña');
		$academico = $this->Usuario_Modelo->iniciar_sesion($usuario, $contraseña);
		$id = $academico['id'];
		if ($id > 0)
		{
			$this->session->set_userdata(array('id' => $id));
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
	public function subir_foto($nickname){
		$foto_subida = false;
		$config['upload_path'] = './usuarios/';
        $config['allowed_types'] = 'jpg';
        $config['file_name'] = $nickname;
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
		if ($this->validar_datos_usuario()) {
			$academico = array('idAcademico'=>0,'nombre'=>$nombre,'correo'=>$correo,'nickname'=>$nickname,'contrasena'=>$contrasena);
			$usuario_registrado = $this->Usuario_Modelo->Registrar_usuario($academico);
			if($usuario_registrado===TRUE){
				if($this->subir_foto($nickname)){
					echo "usuario registrado";
				}else{
					echo "usuario registrado, no se subio la foto";
				}
			}else{
				$this->mostrar_registro_usuario(array('nombre'=>$nombre,'correo'=>$correo,'nickname'=>$nickname, 'mensaje'=> $usuario_registrado));
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
		$id = $this->session->userdata('id');
		$nombre = $this->input->post('nombre');
		$nickname = $this->input->post('nickname');
		$correo = $this->input->post('correo');
		$contrasena = $this->input->post('contrasena');
		$confirmar = $this->input->post('confirmar');	
		if($this->validar_datos_usuario()){
			$academico = array('idAcademico'=> $id,'nombre'=>$nombre,'correo'=>$correo,'nickname' =>$nickname,'contrasena'=>$contrasena);
			if($this->Usuario_Modelo->editar_usuario($academico)){
				echo "usuario editado exitosamente";
			}else{
				echo "el usuario no se pudo editar";
			}
		}else{
			$this->mostrar_edicion_usuario(array('nombre'=>$nombre,'correo'=>$correo,'nickname'=>$nickname,'mensaje'=>'mensaje'));
			//echo "uno o mas datos son invalidos, favor de verificar";
		}
	}
	/*Confirma el código de registro.
		Recibe un código de registro.
		Redirige a la misma vista de 'repositorio'.
	*/
	public function confirmar_registro()
	{

	}
}