<?php

class Documento_Modelo extends CI_Model
{
	public function __construct(){
		$this->load->database('repositorio_uv');
		$this->load->model('repositorio_uv/Usuario_Modelo');
	}
	/*Elimina el registro de un documento.
		Recibe el id del documento.
		Regresa un valor booleano indicando el resultado.
	*/
	public function borrar_documento($id_documento)
	{
		$this->db->where('idDocumento', $id_documento);
		return $this->db->update('documento', array('habilitado' => False));
	}
	public function borrar_documento_solicitud($solicitud)
	{
		$this->db->where('solicitud', $solicitud);
		return $this->db->delete('solicituddocumento');
	}
	/*Registra un documento compartido.
		Recibe el id del documento, el id del académico que comparte ($id_fuente),
		el corre del academico al cual se comparte ($correo) y un booleano
		para el permiso de edición.
		Regresa un valor booleano indicando el resultado.
	*/
	public function compartir_documento($id_documento, $id_fuente, $id_academico, $edicion)
	{
		$registro = $this->db->insert('documentocompartido', array('idDocumentoCompartido' => 0,
			'idAcademicoEmisor' => $id_fuente, 'idAcademicoReceptor' => $id_academico,
			'idDocumento' => $id_documento, 'edicion' => $edicion));
		return $registro;
	}
	public function registrar_solicitud_documento($id_documento, $solicitud, $edicion)
	{
		$registro = $this->db->insert('solicituddocumento', array('idSolicitud' => 0,
				'solicitud' => $solicitud, 'edicion' => $edicion === "si"));
		return $registro;
	}
	public function obtener_documento_solicitud($solicitud){
		$documento;
		$query = $this->db->get_where('solicituddocumento', array('solicitud' => $solicitud));
		if ($query->num_rows() > 0){
			$fila = $query->row();
			$documento = array('id' => 1, 'solicitud' => $fila->solicitud,
				'edicion' => $fila->edicion);
		}else{
			$documento = array('id' => 0);
		}
		return $documento;
	}
	/*Actualiza un Documento.
		Recibe un documente.
		Regresa un valor booleano indicando el resultado.
	*/
	public function modificar_documento($documento)
	{

	}
	/*Obtiene los documentos compartidos que tiene un académico.
		Recibe el is del academico.
		Regresa un arreglo asociativo con los documentos.
	*/
	public function obtener_compartidos($id_usuario)
	{
		$documentos = array();
		$this->db->select('d.idDocumento, d.nombre, d.fechaRegistro, dc.idAcademicoEmisor');
		$this->db->from('documento d, documentocompartido dc');
		$this->db->where('d.idDocumento = dc.idDocumento');
		$this->db->where('d.habilitado', True);
		$this->db->where('dc.idAcademicoReceptor', $id_usuario);
		$query = $this->db->get();
		$result = $query->result();
		for ($i = 0; $i < count($result); ++ $i) {
			$fila = $result[$i];
			$academico = $this->Usuario_Modelo->obtener_usuario($fila->idAcademicoEmisor);
			$documento = array(
				'id' => $fila->idDocumento,
				'nombre' => $fila->nombre,
				'fecha_registro' => $fila->fechaRegistro,
				'academico' => $academico['nombre']
			);
			$documentos[$i] = $documento;
		}
		return $documentos;
	}
	/*Obtiene los documentos personales de un usuario.
		Recibe el id del académico.
		Regresa un arreglo asociativo con los documentos encontrados.
	*/
	public function obtener_documentos($id_usuario)
	{
		$documentos = array();
		$query = $this->db->get_where('documento', array('idAcademico' => $id_usuario, 'habilitado' => True));
		$result = $query->result();
		for ($i = 0; $i < count($result); ++ $i) {
			$fila = $result[$i];
			$documento = array(
				'id' => $fila->idDocumento,
				'nombre' => $fila->nombre,
				'fecha_registro' => $fila->fechaRegistro
			);
			$documentos[$i] = $documento;
		}
		return $documentos;
	}
	/*Registra un documento.
		Recibe un documento.
		Regresa un arreglo con un booleano indicando el resultado y un entero indicando el id asignado.
	*/
	public function obtener_documento($id_documento)
	{
		$documento;
		$query = $this->db->get_where('documento', array('idDocumento' => $id_documento, 'habilitado' => True));
		if ($query->num_rows() > 0){
			$fila = $query->row();
			$documento = array('idDocumento' => $fila->idAcademico,
				'nombre' => $fila->nombre,
				'fechaRegistro' => $fila->fechaRegistro,
				'idAcademico' => $fila->idAcademico,
				'habilitado' => $fila->habilitado);
		}else{
			$documento = array('idDocumento' => 0);
		}
		return $documento;
	}
	public function registrar_documento($documento)
	{
		$resultado = $this->db->insert('documento', $documento);
		$id = $this->db->insert_id();
		$respuesta = array('resultado' => $resultado, 'id' => $id);
		return $respuesta;
	}
}