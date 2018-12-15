	<script type="text/javascript" src="<?php echo base_url(); ?>js/socket.io-client/dist/socket.io.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/clienteChat.js"></script>
	<input id="usuarioChat" type="hidden" name="usuarioChat" value="<?php echo $usuarioChat?>"/>
	<input id="documentoChat" type="hidden" name="documentoChat" value="<?php echo $documentoChat?>"/>
	<div class="chat">
		<div id="areaMensajes" class="mensajes scrollVertical"></div>
		<div class="enviar">
			<input id="campoMensaje" type="text" name="campoMensaje" placeholder="Mensaje" class="campoTexto campoTextoMin fuente"/>
			<input id="botonEnviar" type="button" name="botonEnviar" value="Enviar" class="boton botonOk botonMin fuente">
		</div>
	</div>
</div>