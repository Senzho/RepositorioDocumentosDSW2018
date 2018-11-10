<?php

class Documento_Controller extends CI_Controller
{
	public function __construct()
	{
        parent::__construct();
        $this->load->model('respositorio_uv/Documento_Model');
    }
	/*Carga la vista dependiendo de la página y verificando su existencia:
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		'repositorio': pagina del repositorio personal.
		'compartidos': página de documentos compartidos.
		'documento_mod': página de edición de documento.
		'documento_vista: página de visualización de documento.
	*/
	public function vista($pagina = 'repositorio', $id_documento = '0', $tipo_documento = 'null')
	{
		
	}
	/*Crea un nuevo documento.
		Recibe los datos de un Documento por POST.
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		Regresa una cadena JSON indicando el resultado: ['registrado': True | False].
	*/
	public function crear_documento()
	{

	}
	/*Actualiza un documento.
		Recibe los datos de un Documento por PUT.
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		Redirecciona a la vista de 'repositorio'.
	*/
	public function modificar_documento()
	{
		
	}
	/*Elimina un documento.
		Recibe el id del documento.
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		Regresa un cadena JSON indicando el resultado: ['eliminado': True | False].
	*/
	public function eliminar_documento($id_documento)
	{

	}
	/*Comparte un documento.
		Recibe el id del documento, el correo destinatario y un booleano para el permiso de edición.
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		Regresa un cadena JSON indicando el resultado: 
			['compartido': True | False, 'correo_valido': True | False].
	*/
	public function compartir_documento($id_documento, $correo, $edicion)
	{

	}
	/*Firma un documento.
		Recibe el id del documento.
		Consulta id de usuario en caché, si no se encuentra, redirije a la vista de 'login'.
		Regresa un cadena JSON indicando el resultado: ['firmado': True | False].
	*/
	public function firmar_documento($id_documento)
	{

	}
	/*Carga un documento en el servidor.
		Recibe los datos de un documento por POST.
		Recibe un archivo.
		Regresa una cadena JSON indicando el resultado: ['creado': True | False].
	*/
	public function subir_documento()
	{

	}
	/*Ubica un documento y regresa la ruta.
		Recibe el id de un documento por POST.
	*/
	public function descargar_documento()
	{

	}
}