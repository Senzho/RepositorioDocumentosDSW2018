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
				'correo' => $fila->correo,
				'nickname' => $fila->nickname);
		}else{
			$academico = array('id' => 0);
		}
		return $academico;
	}
	/*Obtiene un Académico en proceso de registro.
		Recibe el id del usuario.
		Regresa un Academico.
	*/
	public function obtener_usuario_proceso($id_usuario)
	{
		$academico;
		$query = $this->db->get_where('academicoProceso', array('idAcademico' => $id_usuario));
		if ($query->num_rows() > 0){
			$fila = $query->row();
			$academico = array('idAcademico' => 0,
				'nombre' => $fila->nombre,
				'nickname' => $fila->nickname,
				'contrasena' => $fila->contrasena,
				'correo' => $fila->correo,
				'codigo' => $fila->codigo);
		}else{
			$academico = array('id' => 0);
		}
		return $academico;
	}
	public function obtener_usuario_correo($correo){
		$academico;
		$query = $this->db->get_where('academico', array('correo' => $correo));
		if ($query->num_rows() > 0){
			$fila = $query->row();
			$academico = array('id' => $fila->idAcademico,
				'nombre' => $fila->nombre,
				'correo' => $fila->correo,
				'nickname' => $fila->nickname);
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
				'correo' => $fila->correo,
				'nickname' => $fila->nickname);
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
		$resultado = $this->db->insert('academico', $academico);
		$id = $this->db->insert_id();
		$respuesta = array('resultado' => $resultado, 'id' => $id);
		return $respuesta;
	}
	/*Registra un Academico en proceso de registro.
		Recibe un Academico.
		Regresa un valor booleano indicando el resultado.
	*/
	public function registrar_usuario_proceso($academico)
	{
		$resultado = $this->db->insert('academicoProceso', $academico);
		$id = $this->db->insert_id();
		$respuesta = array('resultado' => $resultado, 'id' => $id);
		return $respuesta;
	}
	public function correo_valido($correo, $id = 0){
		$correo_disponible = True;
		$query = $this->db->get_where('academico', array('correo' => $correo));
		if ($query->num_rows() > 0 && $id === 0){
			$correo_disponible = false;
		}else if ($query->num_rows() > 0 && $id != 0){
			$academico = $query->row();
			$idAcademico = $academico->idAcademico;
			if($idAcademico != $id){
				$correo_disponible = false;
			}
		}
		return $correo_disponible;
	}
	public function nickname_valido($nickname, $id = 0){
		$nickname_disponible = true;
		$query = $this->db->get_where('academico', array('nickname' => $nickname));
		if ($query->num_rows() > 0 && $id === 0){
			$nickname_disponible = false;
		}else if ($query->num_rows() > 0 && $id != 0){
			$academico = $query->row();
			$idAcademico = $academico->idAcademico;
			if($idAcademico != $id){
				$nickname_disponible = false;
			}
		}
		return $nickname_disponible;
	}
	/*Actualiza un Academico.
		Recibe un Academico.
		Regresa un valor booleano indicando el resultado.
	*/
	public function editar_usuario($academico)
	{
		$data = array(
			'nombre' =>  $academico['nombre'],
			'nickname' => $academico['nickname'],
			'contrasena' =>  $academico['contrasena'],
			'correo' =>  $academico['correo']);
			$this->db->where('idAcademico', $academico['idAcademico']);
		return $this->db->update('academico',$data);
	}

	/*Elimina los datos de sesión del usuario.
	*/
	public function cerrar_sesion()
	{

	}
	public function eliminar_usuario_proceso($id)
	{
		$this->db->where('idAcademico', $id);
		return $this->db->delete('academicoProceso');
	}
}