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
			$this->mostrar_login('');
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
		if ($academico['id'] > 0)
		{
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
		$this->form_validation->set_rules('nombre', 'nombre', 'required');
		$this->form_validation->set_rules('correo', 'correo', 'required');
		$this->form_validation->set_rules('nickname', 'nickname', 'required');
		$this->form_validation->set_rules('contrasena', 'contrasena', 'required');
		$this->form_validation->set_rules('confirmar', 'confirmar', 'required');
		if ($this->form_validation->run()) {
			$academico = array('idAcademico'=>0,'nombre'=>$nombre,'correo'=>$correo,'nickname'=>$nickname,'contrasena'=>$contrasena);
			$usuario_registrado = $this->Usuario_Modelo->Registrar_usuario($academico);
			if($contrasena!=$confirmar){
				$this->mostrar_registro_usuario(array('nombre'=>$nombre,'correo'=>$correo,'nickname'=>$nickname, 'mensaje'=> 'Las contraseñas no coinciden. Intentelo de nuevo'));
			}else if($usuario_registrado===TRUE){
				echo "usuario registrado";
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
	public function editar_usuario()
	{

	}
	/*Confirma el código de registro.
		Recibe un código de registro.
		Redirige a la misma vista de 'repositorio'.
	*/
	public function confirmar_registro()
	{

	}
}