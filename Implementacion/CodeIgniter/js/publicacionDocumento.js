$(document).ready(function(){
	$('#formularioSubirDocumento').submit(function (e){
		e.preventDefault();
		valido = $(this).valido();
		if (valido === "OK"){
			$(this).enviar(this);
		}else{
			var mensaje = "Por favor, ";
			if (valido === "NOMBRE"){
				alert(mensaje + "ingresa el nombre");
			}else if (valido === "ARCHIVOL"){
				alert(mensaje + "selecciona un archivo");
			}else if (valido === "ARCHIVOE"){
				alert(mensaje + "selecciona un archivo en pdf, xlsx, docx o pptx");
			}
		}
	});
});
$.fn.valido = function(){
	var respuesta;
	var campoNombre = $("#nombreDocumentoSubir");
	var campoArchivo = $("#archivo");
	var nombre = campoNombre.val();
	var archivo = campoArchivo.val();
	if (nombre.trim().length === 0){
		respuesta = "NOMBRE";
	}else if (archivo.trim().length === 0){
		respuesta = "ARCHIVOL";
	}else if (!validarExtensionDocumento(archivo)){
		respuesta = "ARCHIVOE";
	}
	else{
		respuesta = "OK";
	}
	return respuesta;
}
$.fn.enviar = function(formulario){
	$.ajax({
	    url:'http://localhost/CodeIgniter/index.php/repositorio_uv/Documento_Controller/subir_documento',
	    type:"post",
	    data:new FormData(formulario),
	    processData:false,
	    contentType:false,
	    cache:false,
	    async:true,
	    success: function(data){
	    	var mensaje;
	    	var json = JSON.parse(data);
	        if (json['creado']){
	        	$(this).actualizar(json['documento']);
	        	mensaje = "Documento registrado!"
	        }else{
	        	mensaje = "Lo sentimos, el documento no pudo registrarse"
	        }
	        alert(mensaje);
	    },
	    error: function(data){
	    	alert("Lo sentimos, ocurrió un error al subir tu documento");
	    }
	});
}
$.fn.actualizar = function (documento){
	$("#contenedor").append("<div class='documento' id=" 
		+ documento['idDocumento'] 
		+ "><div class='seccionIcono'><img src='http://localhost/CodeIgniter/recursos/pdf.png' class='icono'/></div><div class='fuente nombre'>" 
		+ documento['nombre'] 
		+ "</div><div class='fuente fecha'>" 
		+ documento['fechaRegistro'] 
		+ "</div></div>");
	$("#modalFormularioSubir").modal("toggle");
}
function validarExtensionDocumento(nombre){
	var valido = false;
	nombre = nombre.trim();
	if (nombre.endsWith("pdf") || nombre.endsWith("docx") || nombre.endsWith("xlsx") || nombre.endsWith("pptx")){
		valido = true;
	}
	return valido;
}