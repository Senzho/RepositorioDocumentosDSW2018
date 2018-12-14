$(document).ready(function(){
	$('#crear').on('click',function(){
		var contenido = CKEDITOR.instances['editor'].getData();
		if(contenido.length == 0){
			alert('Su documento no tiene contenido');
		}else{
			$('#modalCrearDocumento').modal('toggle');
		}
	});
	$("#crear_documento").on("submit", function(event){
		event.preventDefault();
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
		$('#texto').val(eliminarEspacios(palabras).toString());
		console.log(document.getElementById('texto').value);
		if($('#nombreDocumentoCrear').val()==""){
			alert("Debe ingresar el nombre del documento");
		}else{
			document.getElementById('crear_documento').submit();
		}
	});
	function eliminarEspacios(palabrasConEspacios){
		var palabrasSinEspacio='';
		for(var i = 0; i < palabrasConEspacios.length; i++){
			if(palabrasConEspacios[i]!=''){
				palabrasSinEspacio = palabrasSinEspacio + palabrasConEspacios[i]+'!--!';
			}
		}
		return palabrasSinEspacio;
	}
});