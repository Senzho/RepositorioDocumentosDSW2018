$(document).ready(function (){
	$(".documento").click(function(event) {
		var documento = event.target;
		var id = $(documento).attr("id");
		var nombre = $(documento).find(".nombre").html();
		$("#nombreDocumento").html(nombre);
		$("#opcionEliminar").click(function(url){
			var url = $("#linkEliminar").attr("name") + id;
			$(this).eliminar(id, url)
		});
	});
});
$.fn.eliminar = function(id, url){
	$.ajax({
	    url:url,
	    type:"delete",
	    processData:false,
	    contentType:false,
	    cache:false,
	    async:true,
	    success: function(data){
	    	var json = JSON.parse(data);
	    	var mensaje = json['eliminado'] ? 'Documento eliminado!' : 'El documento no pudo eliminarse';
	    	alert(mensaje);
	    	$(this).borrar(id);
	    },
	    error: function(data){
	    	alert("Lo sentimos, ocurrió un error al eliminar tu documento");
	    }
	});
}
$.fn.borrar = function(id){
	$("#modalOpcionesDocumento").modal("toggle");
	$("#" + id).remove();
}