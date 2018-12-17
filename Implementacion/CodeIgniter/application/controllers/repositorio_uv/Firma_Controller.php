<?php
class Firma_Controller extends CI_Controller
{
	public function __construct()
	{
        parent::__construct();
        $this->load->model('repositorio_uv/Documento_Modelo');
        $this->load->model('repositorio_uv/Usuario_Modelo');
        $this->load->model('repositorio_uv/Firma_Modelo');
        $this->load->helper('url');
        $this->load->library('session');
    }

	public function firmar_documento($id_documento)
	{
		$id_fuente = $this->session->userdata('id');
		if ($id_fuente){
			$compartido = $this->Documento_Modelo->documento_es_compartido($id_fuente, $id_documento);
			if ($compartido['compartido']){
				if ($compartido['edicion']){
					$compartido = True;
				}else{
					$compartido = False;
				}
			}else{
				$compartido = False;
			}
			if($this->Documento_Modelo->documento_pertenece($id_fuente, $id_documento) || $compartido){
				$documento = $this->Documento_Modelo->obtener_documento($id_documento);
				$firmado = $this->Documento_Modelo->firmar_documento($id_fuente, $id_documento, $documento['extension']);
				$respuesta['firmado'] = $firmado;
				if (!$firmado){
					log_message('error', 'No se pudo firmar el documento con Id: ' . $id_documento . ' del usuario con Id: ' . $id_fuente . '.');
				}
				echo json_encode($respuesta);	
			}else{
				log_message('info', 'El usuario con Id: ' . $id_fuente . ' intentó firmar el documento con Id: ' . $id_documento . ', que no le pertenece.');
				$this->load->view('pages/repositorio_uv/error', array('mensaje' => 'Lo sentimos, no tienes permiso para firmar el documento'));
			}
		}else{
			log_message('info', 'Un usuario no autenticado intentó firmar el documento con Id: ' . $id_documento . '.');
			redirect('repositorio_uv/Documento_Controller/vista', 'location');
		}
	}
	public function obtener_firmas($id_documento)
	{
		$id = $this->session->userdata('id');
		if ($id){
			$proceder = False;
			$compartido = $this->Documento_Modelo->documento_es_compartido($id, $id_documento);
			if ($this->Documento_Modelo->documento_pertenece($id, $id_documento)){
				$proceder = True;
			}else if ($compartido['compartido']){
				$proceder = True;
			}
			$respuesta['permiso'] = $proceder;
			if ($proceder){
				$firmas = $this->Firma_Modelo->obtener_firmas($id_documento);
				$cuenta = count($firmas);
				$respuesta['cuenta'] = $cuenta;
				$respuesta['firmas'] = $firmas;
			}
			echo json_encode($respuesta);
		}else{
			log_message('info', 'Un usuario no autenticado intentó obtener las firmas del documento con Id: ' . $id_documento . '.');
			redirect('repositorio_uv/Documento_Controller/vista', 'location');
		}
	}
}