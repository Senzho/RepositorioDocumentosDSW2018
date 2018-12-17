<?php
require APPPATH . 'vendor/autoload.php';
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
class Firma_Modelo extends CI_Model
{
	public function __construct(){
		$this->load->database('repositorio_uv');
	}

	public function documento_firmado($id_academico, $id_documento){
		return file_exists(APPPATH . "firmas/" . $id_academico . 'signature' . $id_documento . '.sign');
	}
	public function firma_valida($id_academico, $id_documento, $extension)
	{
		$cliente = new Client(['base_uri' => base_url() . '/index.php/firma_digital/']);
		$peticion = new Request('GET', 'Firma/verificar_firma/id_academico/' . $id_academico . '/id_documento/' . $id_documento . '/extension/' . $extension, []);
		$respuesta = $cliente->send($peticion, []);
		$json = json_decode($respuesta->getBody());
		$verificacion = $json->verificado;
		return $verificacion;
	}
	public function firmar_documento($id_academico, $id_documento, $extension){
		$cliente = new Client(['base_uri' => base_url() . '/index.php/firma_digital/']);
		$peticion = new Request('POST', 'Firma/firmar/id_academico/' . $id_academico . "/id_documento/" . $id_documento . "/extension/" . $extension, []);
		$respuesta = $cliente->send($peticion, []);
		$json = json_decode($respuesta->getBody());
		$firma = $json->firmado;
		return $firma;
	}
	public function generar_llaves($id_academico){
		$cliente = new Client(['base_uri' => base_url() . '/index.php/firma_digital/']);
		$peticion = new Request('POST', 'Firma/generar_claves/id_academico/' . $id_academico, []);
		$respuesta = $cliente->send($peticion, []);
		$json = json_decode($respuesta->getBody());
		$firma = $json->generadas;
		return $firma;
	}
	public function obtener_firmas($id_documento)
	{
		$firmas = array();
		$this->db->select('d.idDocumento, d.extension, dc.idAcademicoEmisor, dc.idAcademicoReceptor');
		$this->db->from('documento d, documentocompartido dc');
		$this->db->where('d.idDocumento = dc.idDocumento');
		$this->db->where('dc.idDocumento', $id_documento);
		$this->db->where('d.habilitado', True);
		$consulta = $this->db->get();
		$result = $consulta->result();
		if (count($result) > 0){
			$fila = $result[0];
			$academico = $this->Usuario_Modelo->obtener_usuario($fila->idAcademicoEmisor);
			$firma = array(
				'propietario' => True,
				'nickname' => $academico['nickname'],
				'firmado' => $this->documento_firmado($fila->idAcademicoEmisor, $id_documento),
				'firma_valida' => $this->firma_valida($fila->idAcademicoEmisor, $id_documento, $fila->extension)
			);
			$firmas[0] = $firma;
		}
		if (count($result) > 0){
			for ($i = 1; $i < count($result) + 1; ++ $i) {
				$fila = $result[$i - 1];
				$academico = $this->Usuario_Modelo->obtener_usuario($fila->idAcademicoReceptor);
				$firma = array(
					'propietario' => False,
					'nickname' => $academico['nickname'],
					'firmado' => $this->documento_firmado($fila->idAcademicoReceptor, $id_documento),
					'firma_valida' => $this->firma_valida($fila->idAcademicoReceptor, $id_documento, $fila->extension)
				);
				$firmas[$i] = $firma;
			}
		}
		return $firmas;
	}
}