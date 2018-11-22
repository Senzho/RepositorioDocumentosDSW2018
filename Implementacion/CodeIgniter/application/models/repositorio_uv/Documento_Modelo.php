<?php

class Documento_Modelo extends CI_Model
{
	public function __construct(){
		$this->load->database('repositorio_uv');
	}
	/*Elimina el registro de un documento.
		Recibe el id del documento.
		Regresa un valor booleano indicando el resultado.
	*/
	public function borrar_documento($id_documento)
	{

	}
	/*Registra un documento compartido.
		Recibe el id del documento, el id del academico al cual se comparte y un booleano
		para el permiso de edición.
		Regresa un valor booleano indicando el resultado.
	*/
	public function compartir_documento($id_documento, $id_academico, $edicion)
	{

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

	}
	/*Obtiene los documentos personales de un usuario.
		Recibe el id del académico.
		Regresa un arreglo asociativo con los documentos encontrados.
	*/
	public function obtener_documentos($id_usuario)
	{
		$documentos;
		$query = $this->db->get('documento');
		$result = $query->result();
		for ($i = 0; $i < count($result); ++ $i) {
			$row = $result[$i];
			$documento = array(
				'id' => $row->idDocumento,
				'nombre' => $row->nombre,
				'fecha_registro' => $row->fechaRegistro
			);
			$documentos[$i] = $documento;
		}
		return $documentos;
	}
	/*Registra un documento.
		Recibe un documento.
		Regresa un arreglo con un booleano indicando el resultado y un entero indicando el id asignado.
	*/
	public function registrar_documento($documento)
	{
		$resultado = $this->db->insert('documento', $documento);
		$id = $this->db->insert_id();
		$respuesta = array('resultado' => $resultado, 'id' => $id);
		return $respuesta;
	}
}