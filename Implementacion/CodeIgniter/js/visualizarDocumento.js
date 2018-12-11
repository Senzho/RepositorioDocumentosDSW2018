 async function obtenerArchivo(){
    var documento = $("#loaded-layout").attr("name");
    let response = await fetch('http://localhost/proyectoFinalWeb/documentos/'+documento+".docx");
    let data = await response.blob();
    let metadata = {
        type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    };
    return new File([data], documento+".docx", metadata);
}
$(document).ready(function(){
        obtenerArchivo().then(function(archivo){
        console.log(archivo);
        mostrarArchivo(archivo);
    });
});
function mostrarArchivo(archivo){
    var docxJS = new DocxJS();
        docxJS.parse(
       archivo,
        function () {
            docxJS.render($('#loaded-layout')[0], function (result) {
                if (result.isError) {
                    console.log(result.msg);
                } else {
                    console.log("Success Render");
                }
            });
        }, function (e) {
            console.log("Error!", e);
        }
    );
}