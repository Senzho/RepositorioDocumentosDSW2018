<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Confirmación</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/estilos_repositorio.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>css/media_gen.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/validacionLogin.js"></script>
</head>
<body>
    <div id="cabecera" class="cabecera">
        <img src="<?php echo base_url(); ?>recursos/logouv.png" class="uv" />
        <img src="<?php echo base_url(); ?>recursos/Imagen1.png" class="logo" />
    </div>
    <center>
    	<h1 class="fuente h">Confirmación de registro</h1>
	    <div id="formulario">
            <?php echo form_open('repositorio_uv/Usuario_Controller/confirmar_registro', array('id' => 'confirmacionRegistro')); ?>
                <div class="divTexto">
                    <input id="codigo" name="codigo" type="text" placeholder="Código" class="fuente campoTexto campoTextoMed" />
                </div>
                <input type="submit" value="Confirmar" class="fuente boton botonOk botonReg" />
            </form>
	    </div>
	    <div class="mensaje">
	    	<label><?php echo $mensaje; ?></label>
	    </div>
    </center>
</body>
</html>