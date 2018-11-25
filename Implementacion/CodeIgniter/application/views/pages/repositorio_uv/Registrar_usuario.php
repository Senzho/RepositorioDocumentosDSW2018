<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Nuevo usuario</title>
	<meta charset="UTF-8">
	<link href="<?php echo base_url(); ?>css/estilos_general.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="<?php echo base_url(); ?>js/functions_cryptography.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-3.3.1.min.js"></script>   
</head>
<body>
	<div>
		<img src="<?php echo base_url(); ?>recursos/logouv.png" class="imgLogo">
		<img src="<?php echo base_url(); ?>recursos/Imagen1.png" class="imgLogo2">
	</div>
	 	<?php echo form_open_multipart('repositorio_uv/Usuario_Controller/crear_usuario', array('id' => 'registrar_usuario')); ?>
			<div>
				<center>
					<img src="<?php echo base_url(); ?>recursos/usuario.png" id="imgFotoUsuario">
					<input type="file" id='file_input' name="userfile" />
					<img src="<?php echo base_url(); ?>recursos/lapiz.png" id='imgLapiz' />
					<h1>Registro de usuarios</h1>
				</center>
			</div>
			<div>
				<div class="datoUsuario">
					<label class="lblEtiqueta">Nombre:</label>
					<input type="input" name="nombre" class="campoTexto" placeholder="Nombre" value="<?php echo $nombre; ?>">
				</div>
				<div class="datoUsuario">
					<label class="lblEtiqueta">Correo:</label>
					<input type="email" name="correo" class="campoTexto" placeholder="Correo" value="<?php echo $correo; ?>">
				</div>
				<div class="datoUsuario">
					<label class="lblEtiqueta">Nickname:</label>
					<input type="input" name="nickname" class="campoTexto" placeholder="Nickname" value="<?php echo $nickname; ?>">
				</div>
				<div class="datoUsuario">
					<label class="lblEtiqueta">Contraseña:</label>
					<input type="password" id="contrasenaUsuario" name="contrasenaUsuario" class="campoTexto" placeholder="Contraseña">
					<input type="hidden" id="contrasena" name="contrasena">
				</div>
				<div class="datoUsuario">
					<label class="lblEtiqueta">Confirmar:</label>
					<input type="password" id="confirmarUsuario" name="confirmarUsuario" class="campoTexto" placeholder="Confirmar">
					<input type="hidden" id="confirmar" name="confirmar">
				</div>
			</div>
			<center>
				<input type="submit" name="" class="registrar" value="Registrar">
				<?php echo form_submit('cancelar', 'Cancelar', array('class' => 'cancelar','formaction'=>'vista')); ?>
		
			</center>
	</form>
	<div class="mensaje">
    	<center><label id="mensaje_usuario"><?php echo $mensaje; ?></label></center>
    	<center><label id=""><?php echo validation_errors(); ?></label></center>
    </div>
	<div id="divSalir">
		<div>
			<img src="<?php echo base_url(); ?>recursos/logoSalir.png">
			<div><label>Salir</label></div>
		</div>
	</div>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/validacionRegistroUsuario.js"></script>
</body>
</html>