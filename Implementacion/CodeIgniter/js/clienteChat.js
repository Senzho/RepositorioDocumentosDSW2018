$(document).ready(function(){
	const socket = io('http://' + getDominioPagina() + ':9000');
	var name = $("#usuarioChat").attr("value");
	var idUsuario = name.split(".")[0];
	var nombre = name.split(".")[1];
	var idDocumento = $("#documentoChat").attr("value");
	socket.on("connect", function (){
		socket.emit("usuarioConectado", {"idUsuario":idUsuario, "nombre":nombre, "idDocumento":idDocumento});
		socket.on("mensajeRecibido", (nombre, mensaje) => {
			$("#areaMensajes").append("<div>" + "<label class='fuente chatid'>" + nombre + ": " + "</label>" + "<label class='fuente'>" + mensaje + "</label>" + "</div>");
		});
	});
	$("#botonEnviar").click(function (event){
		var mensaje = $("#campoMensaje").val();
		socket.emit("enviarMensaje", {"idUsuario":idUsuario, "idDocumento":idDocumento, "mensaje":mensaje});
	});
	socket.connect();
});