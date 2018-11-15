<?php

class Usuario_Controller extends CI_Controller
{
	public function __construct()
	{
        parent::__construct();
        $this->load->model('repositorio_uv/Usuario_Modelo');
        $this->load->helper('url');
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
			$this->load->view('pages/repositorio_uv/Login');
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
			
		}else{

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