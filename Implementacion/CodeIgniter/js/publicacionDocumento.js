$(document).ready(function(){
    $('#formularioSubir').submit(function(e){
    	e.preventDefault(); 
     	$.ajax({
		    url:'<?php echo base_url();?>index.php/Documento_Controller/subir_documento',
		    type:"post",
		    data:new FormData(this),
		    processData:false,
		    contentType:false,
		    cache:false,
		    async:false,
		    success: function(data){
		        alert("Upload Image Successful.");
		    }
		});
	});
});