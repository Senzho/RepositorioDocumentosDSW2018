$(document).ready(function(){
	$('#imgLapiz').on('click', function(event){
		$('#file_input').trigger('click');
	});
	document.getElementById('file_input').addEventListener('change', function(event){
  		mostrarImagen(event);
  	});
	$('#registrar_usuario').on('submit',function(event){
		if(!validar_datos_usuario()){
			event.preventDefault();
			document.getElementById('mensaje_usuario').innerHTML = "Faltan datos para registrar";
		}else if(!validar_contrasenas()){
			event.preventDefault();
			document.getElementById('mensaje_usuario').innerHTML = "Las contraseñas no coinciden. Intentelo de nuevo";
		}else if (document.getElementById("file_input").value===""){
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
	}//
	return datos_validos;
}
function validar_contrasenas(){
	var contrasena_valida = false;
	var contrasena = $("#contrasenaUsuario").val();
	var confirmar = $("#confirmarUsuario").val();
	if(contrasena === confirmar){
		contrasena_valida = true;
		var contrasena_encriptada = CryptoJS.SHA256(contrasena).toString();
		document.getElementById("contrasena").value = contrasena_encriptada;
		document.getElementById("confirmar").value = contrasena_encriptada;
		console.log(contrasena_encriptada);
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
				document.getElementById('imgFotoUsuario').src= event.target.result;
				console.log(event.target.result);	
			}
		}else{
			alert("La extensión del archivo debe ser .png, .jpg o .gif");
		}
	}else{
		alert("debe seleccionar una imagen");
	}
}
function validarExtension(nombre){
	var valido = false;
	nombre = nombre.trim();
	if (nombre.endsWith("pdf") || nombre.endsWith("jpg") || nombre.endsWith("gif")){
		valido = true;
	}
	return valido;
}