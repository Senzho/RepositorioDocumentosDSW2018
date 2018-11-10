<?php

class Usuario_Model extends CI_Model
{
	public function __construct(){
		$this->load->database('respositorio_uv');
	}
	/*Obtiene un Académico.
		Recibe el id del usuario.
		Regresa un Academico.
	*/
	public function obtener_usuario($id_usuario)
	{

	}
	/*Obtiene un Académico.
		Recibe el usuario y contraseña (hash).
		Regresa un Académico.
	*/
	public function obtener_usuario($nombre, $contraseña)
	{

	}
	/*Registra un Academico.
		Recibe un Academico.
		Regresa un valor booleano indicando el resultado.
	*/
	public function registrar_usuario($academico)
	{

	}
	/*Actualiza un Academico.
		Recibe un Academico.
		Regresa un valor booleano indicando el resultado.
	*/
	public function editar_usuario($academico)
	{

	}
	/*Elimina los datos de sesión del usuario.
	*/
	public function cerrar_sesion()
	{

	}
}