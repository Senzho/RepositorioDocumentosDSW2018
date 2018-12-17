<?php
class DocumentoTest_Controller extends CI_Controller
{
	public function __construct()
	{
        parent::__construct();
        $this->load->model('repositorio_uv/Documento_Modelo');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('repositorio_uv/util');
        $this->load->library('form_validation');
        $this->load->helper('file');
        $this->load->library('session');
        $this->load->library('unit_test');
    }
    public function index(){
            $this->borrarDocumentoTest();
            $this->borrarDocumentoSolicitudTest();
            $this->compartirDocumentoTest();
            $this->registrarSolicitudDocumentoTest();
            $this->obtenerDocumentoSolicitudTest();
            $this->modificarDocumentoTest();
            //$this->obtenerCompartidosTest();
            $this->obtenerDocumentosTest();
            $this->registrarDocumentoTest();
            $this->documentoPerteneceTest();
            $this->documentoEsCompartidoTest();
            $this->guardarDocumentoDocxTest();
            $this->guardarDocumentoPdfTest();
    }
    public function borrarDocumentoTest(){
        $prueba =$this->Documento_Modelo->borrar_documento(53);
        echo  $this->unit->run($prueba,"is_true","borrarDocumentoTest");
    }
    public function borrarDocumentoSolicitudTest(){
        $prueba =$this->Documento_Modelo->borrar_documento_solicitud(53);
        echo  $this->unit->run($prueba,"is_true","borrarDocumentoSolicitudTest");
    }
    public function compartirDocumentoTest(){
        $prueba =$this->Documento_Modelo->compartir_documento(52,10,11,true);
        echo  $this->unit->run($prueba,"is_true","compartirDocumentoTest");
    }
    public function registrarSolicitudDocumentoTest(){
        $solicitud = $this->util->obtener_solicitud(52, 10, 11, '12-12-2012');
        $prueba = $this->Documento_Modelo->registrar_solicitud_documento(52,$solicitud,true);
        echo  $this->unit->run($prueba,"is_true","registrarSolicitudDocumentoTest");
    }
    public function obtenerDocumentoSolicitudTest(){
        $solicitud = $this->util->obtener_solicitud(52, 10, 11, '12-12-2012');
        $prueba = $this->Documento_Modelo->obtener_documento_solicitud($solicitud);
        $prueba =$prueba['id'];
        echo  $this->unit->run($prueba,1,"obtenerDocumentoSolicitudTest");
    }
    public function modificarDocumentoTest(){
        $documento = $this->Documento_Modelo->obtener_documento(52);
        $resultado = $this->Documento_Modelo->modificar_documento($documento);
        echo  $this->unit->run($resultado,"is_true","modificarDocumentoTest");  
    }
    public function obtenerCompartidosTest(){
        $resultado = $this->Documento_Modelo->obtener_compartidos(11);
        echo  $this->unit->run($resultado,5,"obtenerCompartidosTest");   
    }
    public function obtenerDocumentosTest(){
        $resultado = $this->Documento_Modelo->obtener_documentos(10);
        $resultado = sizeof($resultado);
        echo  $this->unit->run($resultado,13,"obtenerDocumentosTest");   
    }
    public function obtenerDocumentoTest(){
        $resultado = $this->Documento_Modelo->obtener_documento(52);
        $resultado = $resultado['idDocumento'];
        echo  $this->unit->run($resultado,10,"obtenerDocumentoTest");   
    }
    public function registrarDocumentoTest(){
        $documento = array('idDocumento' => 0, 'nombre' => 'documento', 'fechaRegistro' => '2012-12-12', 'idAcademico' => 10, 'habilitado' => True, 'extension' => "docx");
        $resultado = $this->Documento_Modelo->registrar_documento($documento);
        echo  $this->unit->run($resultado['resultado'],"is_true","registrarDocumentoTest");   
    }
    public function documentoPerteneceTest(){
        $resultado = $this->Documento_Modelo->documento_pertenece(10,54);
        echo  $this->unit->run($resultado,"is_true","documentoPerteneceTest");
    }
    public function documentoEsCompartidoTest(){
        $resultado = $this->Documento_Modelo->documento_es_compartido(11,52);
        echo  $this->unit->run($resultado['compartido'],"is_true","documentoEsCompartidoTest");
    }
    public function guardarDocumentoDocxTest(){
        $prueba = $this->Documento_Modelo->guardar_documento_docx(80,"un texto largo !--!");
        echo  $this->unit->run($prueba,"is_true","guardarDocumentoDocxTest");

    }
    public function guardarDocumentoPdfTest(){
         $prueba = $this->Documento_Modelo->guardar_documento_pdf(30,"un texto");
        echo  $this->unit->run($prueba,"is_true","guardarDocumentoPdfTest");
    }
}
