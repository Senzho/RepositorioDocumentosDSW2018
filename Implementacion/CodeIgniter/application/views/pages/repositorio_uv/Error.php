﻿<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Error</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/estilos_repositorio.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>css/media_gen.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="cabecera" class="cabecera">
        <img src="<?php echo base_url(); ?>recursos/logouv.png" class="uv" />
        <img src="<?php echo base_url(); ?>recursos/Imagen1.png" class="logo" />
    </div>
    <div class="center">
        <h1 class="fuente h"><?php echo $mensaje;?></h1>
        <div class="link">
            <a href="<?php echo base_url(); ?>index.php/repositorio_uv/Documento_Controller/vista/repositorio" class="fuente">Regresar a mi repositorio</a>
        </div>
    </div>
</body>
</html>