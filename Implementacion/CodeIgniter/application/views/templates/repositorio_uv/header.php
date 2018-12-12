<header class="header">
	<div id="divGeneral">
		<div id="divTituloArchivo">
			<h1><?php echo $titulo; ?></h1>
		</div>
		<div id="divDatosUsuario">
			<div id="divImagenUsuario">
				<?php
					$recurso = base_url();
					if (!$this->util->url_existe(base_url() . 'usuarios/' . $id . '.jpg')){
						$recurso = $recurso . 'recursos/usuario.png';
					}else{
						$recurso = $recurso . 'usuarios/' . $id . '.jpg';
					}
					echo "<img src=$recurso id='imagenUsuario'>";
				?>
			</div>
			<div id="divNombreUsuario">
				<div><label><?php echo $nombre; ?></label></div>
			</div>
		</div>
	</div>
	<div id="divLinea"></div>
</header>