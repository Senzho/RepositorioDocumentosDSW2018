<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio de Sesión</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/estilos_repositorio.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>css/media_gen.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/validacionLogin.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/functions_cryptography.js"></script>
</head>
<body>
    <div id="cabecera" class="cabecera">
        <img src="<?php echo base_url(); ?>recursos/logouv.png" class="uv" />
        <img src="<?php echo base_url(); ?>recursos/Imagen1.png" class="logo" />
    </div>
    <center>
    	<h1 class="fuente h">Iniciar sesión</h1>
	    <div id="formulario">
            <?php echo form_open('repositorio_uv/Usuario_Controller/iniciar_sesion', array('id' => 'login')); ?>
                <div class="divTexto">
                    <input id="usuario" name="usuario" type="text" placeholder="Usuario" class="fuente campoTexto campoTextoMed" />
                </div>
                <div class="divTexto">
                    <input id="contraseña" name="contraseña" type="password" placeholder="Contraseña" class="fuente campoTexto campoTextoMed" />
                    <input type="hidden" id="hash" name="hash" />
                </div>
                <input type="submit" value="Entrar" class="fuente boton botonOk botonReg" />
            </form>
            <div class="link">
                <a href="<?php echo base_url(); ?>index.php/repositorio_uv/Usuario_Controller/vista/registrar_usuario" class="fuente">No tengo una cuenta</a>
            </div>
	    </div>
	    <div class="mensaje">
	    	<label><?php echo $mensaje; ?></label>
	    </div>
    </center>
</body>
</html>