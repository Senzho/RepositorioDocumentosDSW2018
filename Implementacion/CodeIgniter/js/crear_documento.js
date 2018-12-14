$(document).ready(function(){
	$("#crear_documento").on("submit", function(event){
		event.preventDefault();
		console.log("evento detenido");
		var contenido = CKEDITOR.instances['editor'].getData();
		var palabra = "";
		var palabras = [];
		for(var i = 0; i < contenido.length; i++){
			if(contenido[i]!='\n'){
				palabra = palabra + contenido[i];					
			}else{
				palabras.push(palabra);
				palabra = "";
			}
		}
		document.getElementById('texto').value = eliminarEspacios(palabras).toString();
		console.log(document.getElementById('texto').value);
		document.getElementById('crear_documento').submit();
	});
	function eliminarEspacios(palabrasConEspacios){
		var palabrasSinEspacio = [];
		for(var i = 0; i < palabrasConEspacios.length; i++){
			if(palabrasConEspacios[i]!=''){
				palabrasSinEspacio.push(palabrasConEspacios[i]);
			}
		}
		return palabrasSinEspacio;
	}
});