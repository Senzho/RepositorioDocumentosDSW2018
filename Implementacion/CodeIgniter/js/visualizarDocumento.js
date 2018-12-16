 async function obtenerArchivo(){
    var documento = $("#loaded-layout").attr("name");
    var extension = documento.split(".")[1];
    let response = await fetch('http://'+getDominioPagina() +'/CodeIgniter/index.php/repositorio_uv/Documento_Controller/descargar_documento/' + documento.split(".")[0]);
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
    }else if(extension === "pdf"){
        metadata = {
            type: 'application/pdf'
        };
    }
    return new File([data], documento, metadata);
}
$(document).ready(function(){
    obtenerArchivo().then(function(archivo){
        console.log(archivo);
        mostrarArchivo(archivo);
    });
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
    }else if(extension === "pdf"){



        PDFObject.embed(URL.createObjectURL(archivo), "#loaded-layout");
        //var fileURL = URL.createObjectURL(archivo);
        //$('#loaded-layout').append("<iframe src ="+fileURL+ " class=edicion></iframe>");
    }
}