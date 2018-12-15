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
		$("#opcionFirmar").click(function (){
			var url = $("#linkFirmar").attr("name") + id;
			$(this).firmar(id, url)
		});
		var rutaDocumento = $("#urlVerDocumento").attr("href");
		rutaDocumento = rutaDocumento.split('/visualizar/')[0] + '/visualizar/'+id;
		console.log(rutaDocumento);
		$("#urlVerDocumento").attr("href", rutaDocumento);
		rutaDocumento = $("#urlEditarDocumento").attr("href");
		rutaDocumento = rutaDocumento.split('/editar/')[0] + '/editar/'+id;
		$("#urlEditarDocumento").attr("href", rutaDocumento);
		$(this).mostrarPermisos();
		$(this).esconderPermisos(documento);
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
$.fn.firmar = function(id, url){
	$.ajax({
	    url:url,
	    type:"post",
	    processData:false,
	    contentType:false,
	    cache:false,
	    async:true,
	    success: function(data){
	    	var json = JSON.parse(data);
	    	var mensaje = json['firmado'] ? 'Documento firmado!' : 'El documento no pudo firmar';
	    	alert(mensaje);
	    },
	    error: function(data){
	    	alert("Lo sentimos, ocurrió un error al firmar tu documento");
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
$.fn.esconderPermisos = function(documento){
	if ($(documento).hasClass("ver")){
		$("#opcionEditar").hide();
		$("#opcionFirmar").hide();
		$("#opcionCompartir").hide();
		$("#opcionEliminar").hide();
	}else if ($(documento).hasClass("editar")){
		$("#opcionCompartir").hide();
		$("#opcionEliminar").hide();
	}
}
$.fn.mostrarPermisos = function(){
	$("#opcionVer").show();
	$("#opcionEditar").show();
	$("#opcionFirmar").show();
	$("#opcionCompartir").show();
	$("#opcionEliminar").show();
}