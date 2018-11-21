$(document).ready(function(){
	$('#registrar_usuario').on('submit',function(event){
		if(!validar_datos_usuario()){
			event.preventDefault();
			document.getElementById('mensaje_usuario').innerHTML = "Faltan datos para registrar";
		}else if(!validar_contrasenas()){
			event.preventDefault();
			document.getElementById('mensaje_usuario').innerHTML = "Las contraseñas no coinciden. Intentelo de nuevo";
		}
	});
});
function validar_datos_usuario(){
	var datos_validos = true;
	var datos_usuario = $(".campoTexto");
	for(var i = 0; i < datos_usuario.length; i++){
		if(datos_usuario[i].value.trim().length===0){
			datos_validos = false;
			break;
		}
	}
	return datos_validos;
}
function validar_contrasenas(){
	var contrasena_valida = false;
	var contrasena = $("#contrasena").val();
	var confirmar = $("#confirmar").val();
	if(contrasena === confirmar){
		contrasena_valida = true;
	}
	return contrasena_valida;
}