$(document).ready(function (){
	$("#login").submit(function(event) {
		var valido = validar();
		if (valido != "OK"){
			event.preventDefault();
			var mensaje = "Por favor, ";
			if (valido === "USUARIO"){
				mensaje = mensaje + "ingresa tu usuario";
			}else if (valido === "CONTRASEÑA"){
				mensaje = mensaje + "ingresa tu contraseña";
			}
			alert(mensaje);
		}
	});
});

function validar(){
	var respuesta;
	var usuario = $("#usuario").val();
	var contraseña = $("#contraseña").val();
	if (usuario.trim().length === 0){
		respuesta = "USUARIO";
	}else if (contraseña.trim().length === 0){
		respuesta = "CONTRASEÑA";
	}else{
		respuesta = "OK";
	}
	return respuesta;
}