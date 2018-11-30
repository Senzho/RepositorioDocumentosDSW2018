<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Confirmación</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/estilos_repositorio.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>css/media_gen.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="cabecera" class="cabecera">
        <img src="<?php echo base_url(); ?>recursos/logouv.png" class="uv" />
        <img src="<?php echo base_url(); ?>recursos/Imagen1.png" class="logo" />
    </div>
    <center>
        <h1 class="fuente h">Listo!</h1>
        <div>
            <h2 class="fuente"><?php echo 'Has confirmado tu registro ' . $nombre . ', continúa en el enlace' ?></h2>
        </div>
        <div class="link">
            <a href="<?php echo base_url(); ?>index.php/repositorio_uv/Documento_Controller/vista/repositorio" class="fuente">Continuar a mi repositorio</a>
        </div>
    </center>
</body>
</html>