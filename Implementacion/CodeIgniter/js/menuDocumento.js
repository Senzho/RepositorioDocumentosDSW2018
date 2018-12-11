$(document).ready(function (){
	var id;
	$(".documento").click(function(event) {
		var documento = event.currentTarget;
		id = $(documento).attr("id");
		var nombre = $(documento).find(".nombre").html();
		$("#nombreDocumento").html(nombre);
		$("#opcionEliminar").click(function(){
			var url = $("#linkEliminar").attr("name") + id;
			$(this).eliminar(id, url)
		});
		$("#urlVerDocumento").attr("href", $("#urlVerDocumento").attr("href")+id);
	});
	$("#formularioCompartir").on("submit", function (event){
		event.preventDefault();
		var action = $("#formularioCompartir").attr("action");
		var url = action + id;
		$(this).compartir(url, this);
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
$.fn.compartir = function(url, form){
	$.ajax({
	    url:url,
	    type:"post",
	    data:new FormData(form),
	    processData:false,
	    contentType:false,
	    cache:false,
	    async:true,
	    success: function(data){
	    	var json = JSON.parse(data);
	    	var mensaje = json['compartido'] ? 'Documento compartido!' : 'El documento no pudo compartirse';
	    	alert(mensaje);
	    },
	    error: function(data){
	    	alert("Lo sentimos, ocurrió un error al compartir tu documento");
	    }
	});
}
$.fn.borrar = function(id){
	$("#modalOpcionesDocumento").modal("toggle");
	$("#" + id).remove();
}