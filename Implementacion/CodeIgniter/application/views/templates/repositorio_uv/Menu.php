<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Menú principal</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="<?php echo base_url() ?>css/estilos_general.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>css/estilos_repositorio.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>css/media_menu.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>css/media_gen.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/menu.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/publicacionDocumento.js"></script>
</head>
<body class="body">
    <div id="menu" class="menu">
        <div id="cabecera" class="cabecera">
            <img src="<?php echo base_url() ?>/recursos/logouv.png" class="uv" />
            <img src="<?php echo base_url() ?>/recursos/Imagen1.png" class="logo" />
        </div>
        <div id="opciones" class="opciones">
        	<ul class="lista" name="Menu">
        		<li name="repositorio">
        			<div class="opcion">
		                <img src="<?php echo base_url() ?>/recursos/home.png" class="iconoOpcion"/>
		                <label class="fuente textoOpcion">Mi repositorio</label>
		            </div>
        		</li>
        		<li name="compartidos">
        			<div class="opcion">
		                <img src="<?php echo base_url() ?>/recursos/share.png" class="iconoOpcion"/>
		                <label class="fuente textoOpcion">Compartidos</label>
		            </div>
        		</li>
        		<li name="nuevo">
        			<div class="opcion" data-toggle="modal" data-target="#modalOpcionesNuevo">
		                <img src="<?php echo base_url() ?>/recursos/new.png" class="iconoOpcion"/>
		                <label class="fuente textoOpcion">Nuevo</label>
		            </div>
        		</li>
        		<li name="perfil">
        			<div class="opcion">
		                <img src="<?php echo base_url() ?>/recursos/profile.png" class="iconoOpcion"/>
		                <label class="fuente textoOpcion">Perfil</label>
		            </div>
        		</li>
        	</ul>   
        </div>
        <div id="salir" class="bajo">
            <div class="opcion">
                <img src="<?php echo base_url() ?>/recursos/logoSalir.png" class="iconoOpcion"/>
                <label class="fuente textoOpcion">Salir</label>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalOpcionesNuevo" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<ul class="lista" name="opcionesNuevoDocumento">
						<li>
							<div class="opcion">
								<label class="fuente">Crear</label>
							</div>
						</li>
						<li>
							<div id="opcionSubir" class="opcion" data-toggle="modal" data-target="#modalFormularioSubir">
								<label class="fuente">Subir</label>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
    <div class="modal fade" id="modalFormularioSubir" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formularioSubirDocumento">
                    <div class="modal-body">
                        <input id="nombreDocumento" type="text" name="nombre" placeholder="Nombre del documento" class="campoTexto campoTextoMed"/>
                        <input id="archivo" type="file" name="archivo" class="campoTexto campoTextoMed"/>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" value="Subir" class="boton botonOk botonReg" />
                    </div>
                </form>
            </div>
        </div>
    </div>