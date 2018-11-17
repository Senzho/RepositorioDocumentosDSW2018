<?php

class Usuario_Modelo extends CI_Model
{
	public function __construct(){
		$this->load->database('repositorio_uv');
	}
	/*Obtiene un Académico.
		Recibe el id del usuario.
		Regresa un Academico.
	*/
	public function obtener_usuario($id_usuario)
	{
		$academico;
		$query = $this->db->get_where('academico', array('idAcademico' => $id_usuario));
		if ($query->num_rows() > 0){
			$fila = $query->row();
			$academico = array('id' => $fila->idAcademico,
				'nombre' => $fila->nombre,
				'correo' => $fila->correo);
		}else{
			$academico = array('id' => 0);
		}
		return $academico;
	}
	/*Obtiene un Académico.
		Recibe el usuario y contraseña (hash).
		Regresa un Académico.
	*/
	public function iniciar_sesion($nombre, $contraseña)
	{
		$academico;
		$query = $this->db->get_where('academico', array('nickname' => $nombre, 'contrasena' => $contraseña));
		if ($query->num_rows() > 0){
			$fila = $query->row();
			$academico = array('id' => $fila->idAcademico,
				'nombre' => $fila->nombre,
				'correo' => $fila->correo);
		}else{
			$academico = array('id' => 0);
		}
		return $academico;
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