$(document).ready(function(){
	$('#imgLapiz').on('click', function(event){
		$('#file_input').trigger('click');
	});
	$('#file_input').on('change', function(event){
  		mostrarImagen(event);
  	});
	$('#registrar_usuario').on('submit',function(event){
		if(!validar_datos_usuario()){
			event.preventDefault();
			$('#mensaje_usuario').html("Faltan datos para registrar");
		}else if(!validar_contrasenas()){
			event.preventDefault();
			$('#mensaje_usuario').html("Las contraseñas no coinciden. Intentelo de nuevo");
		}else if ($("#file_input").val()===""){
			event.preventDefault();
			alert("debe elegir una imagen");
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
	var contrasena = $("#contrasenaUsuario").val();
	var confirmar = $("#confirmarUsuario").val();
	if(contrasena === confirmar){
		contrasena_valida = true;
		var contrasena_encriptada = CryptoJS.SHA256(contrasena).toString();
		$("#contrasena").val(contrasena_encriptada);
		$("confirmar").val(contrasena_encriptada);
	}
	return contrasena_valida;
}
function mostrarImagen(event) {
	var file = event.target.files[0];
	if(file){
		var reader = new FileReader();
	    reader.readAsDataURL(file);
		var nombre = $("#file_input").val();
		if(validarExtension(nombre)){
			reader.onload = function(event) {
				$('#imgFotoUsuario').attr('src',event.target.result);
			}
		}else{
			alert("La extensión del archivo debe ser .jpg");
		}
	}else{
		alert("debe seleccionar una imagen");
	}
}
function validarExtension(nombre){
	var valido = false;
	nombre = nombre.trim();
	if (nombre.endsWith("jpg")){
		valido = true;
	}
	return valido;
}