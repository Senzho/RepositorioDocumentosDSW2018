<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Inicio de Sesión</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/estilos_repositorio.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>css/media_gen.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="cabecera" class="cabecera">
        <img src="<?php echo base_url(); ?>recursos/logouv.png" class="uv" />
        <img src="<?php echo base_url(); ?>recursos/Imagen1.png" class="logo" />
    </div>
    <div id="formulario">
        <center>
            <h1 class="fuente h">Iniciar sesión</h1>
            <form>
                <div class="divTexto">
                    <input type="text" placeholder="Usuario" class="fuente campoTexto campoTextoMed" />
                </div>
                <div class="divTexto">
                    <input type="password" placeholder="Contraseña" class="fuente campoTexto campoTextoMed" />
                </div>
                <input type="submit" value="Entrar" class="fuente boton botonOk botonReg" />
            </form>
            <div class="link">
                <a href="" class="fuente">No tengo una cuenta</a>
            </div>
        </center>
    </div>
</body>
</html>