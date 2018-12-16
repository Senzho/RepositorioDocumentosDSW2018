<?php
require APPPATH.'third_party/phpword/Autoloader.php';
require APPPATH.'third_party/tcpdf/tcpdf.php';
require APPPATH.'third_party/Doc2Txt.php';
\PhpOffice\PhpWord\Autoloader::register();
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;

require APPPATH . 'vendor/autoload.php';
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
class Documento_Modelo extends CI_Model
{
	private function documento_firmado($id_academico, $id_documento, $extension)
	{
		$cliente = new Client(['base_uri' => base_url() . '/index.php/firma_digital/']);
		$peticion = new Request('GET', 'Firma/verificar_firma', [], json_encode(array('id_academico' => $id_academico, 'id_documento' => $id_documento, 'extension' => $extension)));
		$respuesta = $cliente->send($peticion, []);
		$json = json_decode($respuesta->getBody());
		$verificacion = $json->verificado;
		return $verificacion;
	}

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
		$consulta = $this->db->get_where('solicituddocumento', array('solicitud' => $solicitud));
		if ($consulta->num_rows() > 0){
			$fila = $consulta->row();
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
		$data = array(
			'nombre' =>  $documento['nombre']);
			$this->db->where('idDocumento', $documento['idDocumento']);
		$resultado = $this->db->update('documento',$data);
		return $resultado;
	}
	/*Obtiene los documentos compartidos que tiene un académico.
		Recibe el is del academico.
		Regresa un arreglo asociativo con los documentos.
	*/
	public function obtener_compartidos($id_usuario)
	{
		$documentos = array();
		$this->db->select('d.idDocumento, d.nombre, d.fechaRegistro, d.extension, dc.idAcademicoEmisor, dc.edicion');
		$this->db->from('documento d, documentocompartido dc');
		$this->db->where('d.idDocumento = dc.idDocumento');
		$this->db->where('d.habilitado', True);
		$this->db->where('dc.idAcademicoReceptor', $id_usuario);
		$consulta = $this->db->get();
		$result = $consulta->result();
		for ($i = 0; $i < count($result); ++ $i) {
			$fila = $result[$i];
			$academico = $this->Usuario_Modelo->obtener_usuario($fila->idAcademicoEmisor);
			$documento = array(
				'id' => $fila->idDocumento,
				'nombre' => $fila->nombre,
				'fecha_registro' => $fila->fechaRegistro,
				'academico' => $academico['nombre'],
				'extension' => $fila->extension,
				'edicion' => $fila->edicion,
				'firmado' => $this->documento_firmado($academico['id'], $fila->idDocumento, $fila->extension)
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
		$consulta = $this->db->get_where('documento', array('idAcademico' => $id_usuario, 'habilitado' => True));
		$result = $consulta->result();
		for ($i = 0; $i < count($result); ++ $i) {
			$fila = $result[$i];
			$documento = array(
				'id' => $fila->idDocumento,
				'nombre' => $fila->nombre,
				'fecha_registro' => $fila->fechaRegistro,
				'extension' => $fila->extension
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
		$consulta = $this->db->get_where('documento', array('idDocumento' => $id_documento, 'habilitado' => True));
		if ($consulta->num_rows() > 0){
			$fila = $consulta->row();
			$documento = array('idDocumento' => $fila->idAcademico,
				'nombre' => $fila->nombre,
				'fechaRegistro' => $fila->fechaRegistro,
				'idAcademico' => $fila->idAcademico,
				'habilitado' => $fila->habilitado, 'extension' => $fila->extension);
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
	public function documento_pertenece($id_academico, $id_documento)
	{
		$consulta = $this->db->get_where('documento', array('idDocumento' => $id_documento, 'habilitado' => True, 'idAcademico' => $id_academico));
		return $consulta->num_rows() > 0;
	}
	public function documento_es_compartido($id_academico, $id_documento)
	{
		$this->db->select('dc.edicion, d.nombre');
		$this->db->from('documento d, documentocompartido dc');
		$this->db->where('d.habilitado', True);
		$this->db->where('dc.idDocumento', $id_documento);
		$this->db->where('dc.idAcademicoReceptor', $id_academico);
		$consulta = $this->db->get();
		$editable = false;
		if($consulta->num_rows()> 0){
			$fila = $consulta->row();
			$editable = $fila->edicion;
		}
		return array('compartido'=>$consulta->num_rows() > 0, 'edicion'=>$editable);
	}
	public function firmar_documento($id_academico, $id_documento, $extension){
		$cliente = new Client(['base_uri' => base_url() . '/index.php/firma_digital/']);
		$peticion = new Request('POST', 'Firma/firmar', [], json_encode(array('id_academico' => $id_academico, 'id_documento' => $id_documento, 'extension' => $extension)));
		$respuesta = $cliente->send($peticion, []);
		$json = json_decode($respuesta->getBody());
		$firma = $json->firmado;
		return $firma;
	}
	public function generar_llaves($id_academico){
		$cliente = new Client(['base_uri' => base_url() . '/index.php/firma_digital/']);
		$peticion = new Request('POST', 'Firma/generar_claves', [], json_encode(array('id_academico' => $id_academico)));
		$respuesta = $cliente->send($peticion, []);
		$json = json_decode($respuesta->getBody());
		$firma = $json->generadas;
		return $firma;
	}
	public function guardar_documento_docx($id_documento, $texto, $editar = false){
		$documento = new PhpWord();
		$seccion = $documento->addSection();
		$texto = explode("!--!", $texto);
		$tamano = sizeof($texto);
		for ($i=0; $i < $tamano; $i++) { 
			if(strlen(strstr($texto[$i], 'h1'))){
				$seccion->addText(
						htmlspecialchars(
							strip_tags(html_entity_decode($texto[$i]))
						),
						array('name' => 'Arial', 'size' => '22', 'bold' => 'true')
				);
			}else if(strlen(strstr($texto[$i], 'p'))){
				$seccion->addText(
					htmlspecialchars(
				 		strip_tags(html_entity_decode($texto[$i]))
					),
					array('name' => 'Arial', 'size' => '12', 'bold' => 'false')
				);	
			}
		}
		$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, 'Word2007');
		if($editar === True){
			unlink(APPPATH.'documentos'.'/'.$id_documento.'.docx');
			$this->guardar_documento_pdf($id_documento,$texto);
		}
		$objWriter->save(APPPATH.'documentos'.'/'.$id_documento.'.docx');
		$documento_guardado = False;
		if(file_exists(APPPATH.'documentos'.'/'.$id_documento.'.docx')){
			$documento_guardado = True;
		}else{
			log_message('error', 'La comprobación de escritura de documento (.docx) con Id: ' . $id_documento . ' resultó negativa.');
		}
		return $documento_guardado;
	}
	public function guardar_documento_pdf($id_documento,$texto,$editar = false){
		$documento_guardado = false;
		header('Content-type: application/pdf');
		$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$obj_pdf->SetCreator(PDF_CREATOR);
		$obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
		$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$obj_pdf->SetDefaultMonospacedFont('helvetica');
		$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
		$obj_pdf->setPrintHeader(false);
		$obj_pdf->setPrintFooter(false);
		$obj_pdf->SetAutoPageBreak(TRUE, 10);
		$obj_pdf->SetFont('helvetica', '', 12);
		$obj_pdf->AddPage();
		$texto = explode("!--!", $texto);
		$tamano = sizeof($texto);
		$texto_documento='';
		for($i = 0; $i < $tamano; $i++){
			$texto_documento = $texto_documento . $texto[$i];
		}
		$obj_pdf->writeHTML($texto_documento);
		//$obj_pdf->Output($id_documento.'.pdf', 'D');
		if($editar){
			if(file_exists(APPPATH.'documentos'.'/'.$id_documento.'.pdf')){
				unlink(APPPATH.'documentos'.'/'.$id_documento.'.pdf');
			}
		}
		$obj_pdf->Output(APPPATH.'documentos'.'/'.$id_documento.'.pdf', 'F'); 
		if(file_exists(APPPATH.'documentos'.'/'.$id_documento.'.pdf')){
			$documento_guardado = True;
		}else{
			log_message('error', 'La comprobación de escritura de documento (.pdf) con Id: ' . $id_documento . ' resultó negativa.');
		}
		return $documento_guardado;
	}
}