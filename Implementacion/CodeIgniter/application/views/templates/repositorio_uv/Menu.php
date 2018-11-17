<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Menú principal</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="<?php echo base_url() ?>/css/estilos_repositorio.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>/css/media_menu.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>/css/media_gen.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>css/estilos_general.css" rel="stylesheet" type="text/css">
</head>
<body class="body">
    <div id="menu" class="menu">
        <div id="cabecera" class="cabecera">
            <img src="<?php echo base_url() ?>/recursos/logouv.png" class="uv" />
            <img src="<?php echo base_url() ?>/recursos/Imagen1.png" class="logo" />
        </div>
        <div id="opciones" class="opciones">
            <div class="opcion">
                <img src="<?php echo base_url() ?>/recursos/home.png" class="iconoOpcion"/>
                <label class="fuente textoOpcion">Mi repositorio</label>
            </div>
            <div class="opcion">
                <img src="<?php echo base_url() ?>/recursos/share.png" class="iconoOpcion"/>
                <label class="fuente textoOpcion">Compartidos</label>
            </div>
            <div class="opcion">
                <img src="<?php echo base_url() ?>/recursos/new.png" class="iconoOpcion"/>
                <label class="fuente textoOpcion">Nuevo</label>
            </div>
            <div class="opcion">
                <img src="<?php echo base_url() ?>/recursos/profile.png" class="iconoOpcion"/>
                <label class="fuente textoOpcion">Perfil</label>
            </div>
        </div>
        <div id="salir" class="bajo">
            <div class="opcion">
                <img src="<?php echo base_url() ?>/recursos/logoSalir.png" class="iconoOpcion"/>
                <label class="fuente textoOpcion">Salir</label>
            </div>
        </div>
    </div>