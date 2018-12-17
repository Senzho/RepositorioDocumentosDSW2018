<?php
class Pruebas_Controller extends CI_Controller
{
	public function __construct()
	{
        parent::__construct();
        $this->load->model('repositorio_uv/Usuario_Modelo');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('repositorio_uv/util');
        $this->load->library('form_validation');
        $this->load->helper('file');
        $this->load->library('session');
        $this->load->library('unit_test');
        //$this->load->view('pages/repositorio_uv/login',$datos_prueba);
    }
    public function index(){
            $this->obtenerUsuarioTest();
            $this->noObtenerUsuarioTest();
            $this->registrarUsuarioProcesoTest();
            $this->correoValidoTest();
            $this->correoNoValidoTest();
            $this->correoEdicionValidoTest();
            $this->correoEdicionNoValidoTest();
            $this->nicknameValidoTest();
            $this->nicknameNoValidoTest();
            $this->nicknameEdicionValidoTest();
            $this->nicknameEdicionNoValidoTest();
            $this->editarUsuarioTest();
            $this->eliminarUsuarioProcesoTest();
    }
    public function obtenerUsuarioTest(){
        $prueba =$this->Usuario_Modelo->obtener_usuario(10);
        $id = $prueba["id"];
        $resultado = 10;
        $nombre = "obtenerUsuarioExistenteTest";
        echo  $this->unit->run($id,$resultado,$nombre);
    }
     public function noObtenerUsuarioTest(){
        $prueba =$this->Usuario_Modelo->obtener_usuario(12);
        $id = $prueba["id"];
        $resultado = 0;
        $nombre = "UuarioNoExistenteTest";
        echo  $this->unit->run($id,$resultado,$nombre);
    }
    public function registrarUsuarioProcesoTest(){
        $academico = array('idAcademico'=>0,'nombre'=>'prueba','correo'=>'prueba','nickname'=>'prueba','contrasena'=>'prueba');
        $prueba = $this->Usuario_Modelo->registrar_usuario_proceso($academico);
        $prueba = $prueba['resultado'];
         echo  $this->unit->run($prueba,"is_true","registroAcademicoProcesoTest");
    }
    public function correoValidoTest(){
        $valido = $this->Usuario_Modelo->correo_valido("ddcfvbh@gmail.com");
        echo $this->unit->run($valido,"is_true","correoValidoTest");
    }
    public function correoNoValidoTest(){
        $valido = $this->Usuario_Modelo->correo_valido("marioolopez21@gmail.com");
        echo $this->unit->run($valido,"is_false","correoNoValidoTest");
    }
    public function correoEdicionValidoTest(){
        $valido = $this->Usuario_Modelo->correo_valido("marioolopez21@gmail.com",10);
        echo $this->unit->run($valido,"is_true","correoEdicionValidoTest");
    }
    public function correoEdicionNoValidoTest(){
        $valido = $this->Usuario_Modelo->correo_valido("marioolopez21@gmail.com",11);
        echo $this->unit->run($valido,"is_false","correoEdicionNoValidoTest");
    }
    public function nicknameValidoTest(){
        $valido = $this->Usuario_Modelo->nickname_valido("victor");
        echo $this->unit->run($valido,"is_true","nicknameValidoTest");
    }
    public function nicknameNoValidoTest(){
        $valido = $this->Usuario_Modelo->nickname_valido("mario");
        echo $this->unit->run($valido,"is_false","nicknameNoValidoTest");
    }
    public function nicknameEdicionValidoTest(){
        $valido = $this->Usuario_Modelo->nickname_valido("victor",10);
        echo $this->unit->run($valido,"is_true","nicknameEdicionValidoTest");
    }
    public function nicknameEdicionNoValidoTest(){
        $valido = $this->Usuario_Modelo->nickname_valido("mario",11);
        echo $this->unit->run($valido,"is_false","nicknameEdicionNoValidoTest");
    }
    public function editarUsuarioTest(){
         $academico = array('idAcademico'=>11,'nombre'=>'prueba','correo'=>'prueba','nickname'=>'prueba','contrasena'=>'prueba');
         $valido = $this->Usuario_Modelo->editar_usuario($academico);
          echo $this->unit->run($valido,"is_true","editarUsuarioTest");
    }
    public  function eliminarUsuarioProcesoTest(){
        $eliminado = $this->Usuario_Modelo->eliminar_usuario_proceso(3);
        echo $this->unit->run($eliminado,"is_true","eliminarUsuarioTest");
    }
}