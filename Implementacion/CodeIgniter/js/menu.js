$(document).ready(function() {
	$("#opcionSubir").click(function() {
		$("#modalOpcionesNuevo").modal("toggle");
	});
	$("#opcionSalir").click(function() {
		$(location).attr("replace", "http://localhost/CodeIgniter/index.php/repositorio_uv/Usuario_Controller/cerrar_sesion");
	});
	$("#opcionCompartidos").click(function() {
		$(location).attr("replace", "http://localhost/CodeIgniter/index.php/repositorio_uv/Documento_Controller/vista/compartidos");
	});
	$("#opcionRepositorio").click(function() {
		$(location).attr("replace", "http://localhost/CodeIgniter/index.php/repositorio_uv/Documento_Controller/vista/repositorio");
	});
});