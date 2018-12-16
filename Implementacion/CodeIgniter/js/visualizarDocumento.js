 async function obtenerArchivo(){
    var documento = $("#loaded-layout").attr("name");
    var extension = documento.split(".")[1];
    let response = await fetch('http://'+getDominioPagina() +'/proyectoFinalWeb/index.php/repositorio_uv/Documento_Controller/descargar_documento/' + documento.split(".")[0]);
    let data = await response.blob();
    var metadata = "";
    if(extension === "docx"){
        metadata = {
            type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        };
    }else if (extension === "xlsx"){
        metadata = {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        };
    }
    return new File([data], documento, metadata);
}
function mostrarPdf(nombreArchivo){
    var ruta = 'http://'+getDominioPagina() +'/proyectoFinalWeb/index.php/repositorio_uv/Documento_Controller/descargar_documento/' + nombreArchivo;
    $("#loaded-layout").append("<embed src="+ruta+" style='width:100%;height:100%;'></embed>");
}
$(document).ready(function(){
    var documento = $("#loaded-layout").attr("name");
    var extension = documento.split(".")[1];
    if(extension === "pdf"){
        mostrarPdf(documento.split(".")[0]);
    }else{
        obtenerArchivo().then(function(archivo){
            console.log(archivo);
            mostrarArchivo(archivo);
        });
    }
});
function mostrarArchivo(archivo){
    var documento = $("#loaded-layout").attr("name");
    var extension = documento.split(".")[1];
    if(extension === "docx"){
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
    }else if(extension === "xlsx"){
        var cellJS = new CellJS();
        cellJS.parse(
        archivo,
            function () {
                cellJS.render($('#loaded-layout')[0], function (result) {
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
}