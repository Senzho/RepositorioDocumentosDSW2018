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
	private function verificar_correo($correo){
		$academico_registrado = False;
		$query = $this->db->get_where('academico', array('correo' => $correo));
		if ($query->num_rows() > 0){
			$academico_registrado = True;
		}
		return $academico_registrado;
	}
	private function verificar_nickname($nickname){
		$academico_registrado = False;
		$query = $this->db->get_where('academico', array('nickname' => $nickname));
		if ($query->num_rows() > 0){
			$academico_registrado = True;
		}
		return $academico_registrado;
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
	/*Actualiza un Academico.
		Recibe un Academico.
		Regresa un valor booleano indicando el resultado.
	*/
	public function editar_usuario($academico)
	{
		$usuario_registrado = false;
		if($this->verificar_correo($academico['correo'])){
			$usuario_registrado = 'El correo está registrado anteriormente';
		}else if($this->verificar_nickname($academico['nickname'])){
			$usuario_registrado = 'el nickname esta registrado anteriormente';
		}else{
			$this->db->set('idAcademico',$academico['idAcademico']);
			$this->db->set('nombre',$academico['nombre']);
			$this->db->set('correo',$academico['correo']);
			$this->db->set('nickname',$academico['nickname']);	
			$this->db->set('contrasena',$academico['contrasena']);
			$usuario_registrado = $this->db->insert('academico');
		}
		return $usuario_registrado;
	}
	/*Elimina los datos de sesión del usuario.
	*/
	public function cerrar_sesion()
	{

	}
}