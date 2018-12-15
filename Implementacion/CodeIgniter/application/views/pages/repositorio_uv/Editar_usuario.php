    <script type="text/javascript" src="<?php echo base_url(); ?>js/validacionRegistroUsuario.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/dominioPagina.js"></script>
<div class="vistaPrincipal scrollVertical">
 	<?php echo form_open_multipart('repositorio_uv/Usuario_Controller/editar_usuario', array('id' => 'editar_usuario')); ?>
		<div>
			<div class="center">
				<?php
					$recurso = base_url();
					if (!$this->util->url_existe(base_url() . 'usuarios/' . $id . '.jpg')){
						$recurso = $recurso . 'recursos/usuario.png';
					}else{
						$recurso = $recurso . 'usuarios/' . $id . '.jpg';
					}
					echo "<img src=$recurso id='imgFotoUsuario'>";
				?>
				<input type="file" id='file_input' name="userfile" />
				<img src="<?php echo base_url(); ?>recursos/lapiz.png" id='imgLapiz' />
			</div>
		</div>
		<div>
			<div class="datoUsuario">
				<label class="lblEtiqueta">Nombre:</label>
				<input type="input" name="nombre" class="campoTexto datoRegistro" placeholder="Nombre" value="<?php echo $nombre; ?>">
			</div>
			<div class="datoUsuario">
				<label class="lblEtiqueta">Correo:</label>
				<input type="email" name="correo" class="campoTexto datoRegistro" placeholder="Correo" value="<?php echo $correo; ?>">
			</div>
			<div class="datoUsuario">
				<label class="lblEtiqueta">Nickname:</label>
				<input type="input" name="nickname" class="campoTexto datoRegistro" placeholder="Nickname" value="<?php echo $nickname; ?>">
			</div>
			<div class="datoUsuario">
				<label class="lblEtiqueta">Contraseña:</label>
				<input type="password" id="contrasenaUsuario" name="contrasenaUsuario" class="campoTexto datoRegistro" placeholder="Contraseña">
				<input type="hidden" id="contrasena" name="contrasena">
			</div>
			<div class="datoUsuario">
				<label class="lblEtiqueta">Confirmar:</label>
				<input type="password" id="confirmarUsuario" name="confirmarUsuario" class="campoTexto datoRegistro" placeholder="Confirmar">
				<input type="hidden" id="confirmar" name="confirmar">
			</div>
		</div>
		<div class="center">
			<input type="submit" name="" class="registrar" value="Registrar">
			<a href="<?php echo base_url()?>index.php/repositorio_uv/Usuario_Controller/vista"><button type="button" name="cancelar" value="Cancelar" class="cancelar">Cancelar</button></a>
		</div>
	</form>
	<div class="mensaje">
		<div class="center"><label id="mensaje_usuario"><?php echo $mensaje; ?></label></div>
		<div class="center"><label id=""><?php echo validation_errors(); ?></label></div>
	</div>
</div>