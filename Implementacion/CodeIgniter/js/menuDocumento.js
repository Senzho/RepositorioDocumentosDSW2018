$(document).ready(function (){
	var id;
	$(".documento").click(function(event) {
		var documento = event.currentTarget;
		id = $(documento).attr("id");
		var nombre = $(documento).find(".nombre").html();
		$("#nombreDocumento").html(nombre);
		$("#opcionEliminar").click(function(){
			if (!$("#opcionEliminar").attr("disabled")){
				$("#opcionEliminar").attr("disabled", true);
				var url = $("#linkEliminar").attr("name") + id;
				$(this).eliminar(id, url);
			}	
		});
		$("#opcionFirmar").click(function (){
			if (!$("#opcionFirmar").attr("disabled")){
				$("#opcionFirmar").attr("disabled", true);
				var url = $("#linkFirmar").attr("name") + id;
				$(this).firmar(id, url);
			}
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
		if ($(documento).hasClass("pdf") || $(documento).hasClass("xlsx")){
			$("#opcionEditar").hide();
		}
	});
	$("#formularioCompartir").on("submit", function (event){
		event.preventDefault();
		if ($("#correoCompartir").val().trim() != ""){
			$("#botonCompartir").attr("disabled", true);
			var action = $("#formularioCompartir").attr("action");
			var url = action + id;
			$(this).compartir(url, this);
		}else{
			alert("Por favor, ingresa el correo del usuario al que deseas compartir el documento");
		}
	});
	var urlExportarOriginal;
	$("#formularioExportar").on("submit", function (event){
		$("#formularioExportar").attr("action", urlExportarOriginal);
		var action = $("#formularioExportar").attr("action");
		urlExportarOriginal = action;
		var extension = $("#selectExtension").val();
		var url = action + id + "/" + extension;
		$("#formularioExportar").attr("action", url);
		$("#modalExportar").modal("toggle");
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
	    	$("#opcionEliminar").attr("disabled", false);
	    },
	    error: function(data){
	    	alert("Lo sentimos, ocurrió un error al eliminar tu documento");
	    	$("#opcionEliminar").attr("disabled", false);
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
	    	var mensaje = json['firmado'] ? 'Documento firmado!' : 'El documento no se pudo firmar';
	    	alert(mensaje);
	    	$("#opcionFirmar").attr("disabled", false);
	    },
	    error: function(data){
	    	alert("Lo sentimos, ocurrió un error al firmar tu documento");
	    	$("#opcionFirmar").attr("disabled", false);
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
	    	$("#botonCompartir").attr("disabled", false);
	    	$(this).restablecerModalCompartir();
	    },
	    error: function(data){
	    	alert("Lo sentimos, ocurrió un error al compartir tu documento");
	    	$("#botonCompartir").attr("disabled", false);
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
	$("#opcionExportar").show();
	$("#opcionEliminar").show();
}
$.fn.restablecerModalCompartir = function(){
	$("#modalCompartir").modal("toggle");
	$("#correoCompartir").val("");
}