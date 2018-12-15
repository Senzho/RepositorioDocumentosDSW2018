var io = require("socket.io")(9000);
console.log("Servidor corriendo en el puerto 9000...");
var usuarios = new Array();
var usuariosDocumentos = new Array();

function Usuario(idUsuario, nombre){
    this.idUsuario = idUsuario;
    this.nombre = nombre;
}
function UsuarioDocumento(idUsuario, idDocumento, socket){
    this.idUsuario = idUsuario;
    this.idDocumento = idDocumento;
    this.socket = socket;
}

function obtenerUsuario(idUsuario){
    var usuario;
    for (var i = 0; i < usuarios.length; i ++){
        var usuarioEncontrado = usuarios[i];
        if (usuarioEncontrado.idUsuario == idUsuario){
            usuario = usuarioEncontrado;
            break;
        }
    }
    return usuario;
}
function usuarioExiste(idUsuario){
    var existe = false;
    for (var i = 0; i < usuarios.length; i ++){
        var usuarioEncontrado = usuarios[i];
        if (usuarioEncontrado.idUsuario == idUsuario){
            existe = true;
            break;
        }
    }
    return existe;
}
function usuarioDocumentoExiste(idUsuario, idDocumento){
    var existe = false;
    for (var i = 0; i < usuariosDocumentos.length; i ++){
        var encontrado = usuariosDocumentos[i];
        if (encontrado.idUsuario == idUsuario && encontrado.idDocumento == idDocumento){
            existe = true;
            break;
        }
    }
    return existe;
}
function usuariosRepetidos(idUsuario){
    var cuenta = 0;
    for (var i = 0; i < usuariosDocumentos.length; i ++){
        var encontrado = usuariosDocumentos[i];
        if (encontrado.idUsuario == idUsuario){
            cuenta = cuenta + 1;
        }
    }
    return cuenta;
}
function eliminarUsuario(idUsuario){
    for (var i = 0; i < usuarios.length; i ++){
        var encontrado = usuarios[i];
        if (encontrado.idUsuario == idUsuario){
            usuariosDocumentos.splice(i, 1);
            break;
        }
    }
}

io.on("connection", function (socket){
    console.log("Nueva conección establecida");
    socket.on("usuarioConectado", function (json){
        if (!usuarioExiste(json.idUsuario)){
            usuario = new Usuario(json.idUsuario, json.nombre);
            usuarios.push(usuario);
        }
        if (!usuarioDocumentoExiste(json.idUsuario, json.idDocumento)){
            usuarioDocumento = new UsuarioDocumento(json.idUsuario, json.idDocumento, socket);
            usuariosDocumentos.push(usuarioDocumento);
        }
        console.log(json.nombre + " con Id: " + json.idUsuario + " y documento: " + json.idDocumento + " se conectó.");
    });

    socket.on("enviarMensaje", function (mensaje){
        var usuario = obtenerUsuario(mensaje.idUsuario);
        for (var i = 0; i < usuariosDocumentos.length; i ++){
            var elemento = usuariosDocumentos[i];
            if (elemento.idDocumento == mensaje.idDocumento){
                var usuarioEnviar = obtenerUsuario(elemento.idUsuario);
                var nombre = elemento.idUsuario == usuario.idUsuario ? "Yo" : usuario.nombre;
                elemento.socket.emit("mensajeRecibido", nombre, mensaje.mensaje);
            }
        }
    });

    socket.on("disconnect", function (){
        var elemento;
        for (var i = 0; i < usuariosDocumentos.length; i ++){
            elemento = usuariosDocumentos[i];
            if (elemento.socket == socket){
                if (usuariosRepetidos(elemento.idUsuario) == 1){
                    eliminarUsuario(elemento.idUsuario);
                }
                usuariosDocumentos.splice(i, 1);
                console.log("Usuario con Id: " + elemento.idUsuario + " y documento: " + elemento.idDocumento + " se desconectó.");
                break;
            }
        }
    });
});