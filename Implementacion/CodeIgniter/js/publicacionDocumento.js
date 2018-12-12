$(document).ready(function(){
	$('#formularioSubirDocumento').submit(function (e){
		e.preventDefault();
		valido = $(this).valido();
		if (valido === "OK"){
			var nombre = $("#archivo").val().trim();
			$(this).enviar(this, nombre);
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
$.fn.enviar = function(formulario, nombre){
	var data = new FormData(formulario);
	data.append("ruta", nombre);
	$.ajax({
	    url:'http://localhost/CodeIgniter/index.php/repositorio_uv/Documento_Controller/subir_documento',
	    type:"post",
	    data:data,
	    processData:false,
	    contentType:false,
	    cache:false,
	    async:true,
	    success: function(data){
	    	var mensaje;
	    	var json = JSON.parse(data);
	        if (json['creado']){
	        	$(this).actualizar(json['documento'], nombre);
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
$.fn.actualizar = function (documento, nombre){
	var extension = nombre.endsWith('pdf') ? 'pdf' : (nombre.endsWith('docx') ? 'docx' : 'xlsx');
	$("#contenedor").append("<div class='documento' id=" 
		+ documento['idDocumento'] 
		+ " data-toggle='modal' data-target='#modalOpcionesDocumento'><div class='seccionIcono center'><img src='http://localhost/CodeIgniter/recursos/" + extension + ".png' class='icono'/></div><div class='fuente nombre'>" 
		+ documento['nombre'] 
		+ "</div><div class='fuente fecha'>" 
		+ documento['fechaRegistro'] 
		+ "</div></div>");
	$("#modalFormularioSubir").modal("toggle");
}
function validarExtensionDocumento(nombre){
	var valido = false;
	nombre = nombre.trim();
	if (nombre.endsWith("pdf") || nombre.endsWith("docx") || nombre.endsWith("xlsx")){
		valido = true;
	}
	return valido;
}