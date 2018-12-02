    <div id="panel" class="panelScroll">
    	<div id="contenedor" class="contenedor">
            <?php
                for ($i = 0; $i < count($documentos); $i ++){
                    $documento = $documentos[$i];
                    $id = $documento['id'];
                    $nombre = $documento['nombre'];
                    $fecha_registro = $documento['fecha_registro'];
                    $recurso = base_url() . '/recursos/pdf.png';
                    echo "<div class='documento' id=$id data-toggle='modal' data-target='#modalOpcionesDocumento'>
                            <div class='center'>
                                <img src=$recurso class='icono'/>
                            </div>
                            <div class='fuente nombre'>$nombre</div>
                            <div class='fuente fecha'>$fecha_registro</div>";
                    if (array_key_exists('academico', $documento)){
                        $academico = $documento['academico'];
                        echo "<div class='fuente emisor'>$academico</div>";
                    }
                    echo "</div>";
                }
            ?>
    	</div>
    </div>
    <div class="modal fade" id="modalOpcionesDocumento" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
            	<div class="modal-header">
            		<h3 id="nombreDocumento" class="fuente h3">kjhg</h3>
            	</div>
                <div class="modal-body center">
                	<ul name="menuDocumento" class="lista">
                		<li name="opcionVer" class="linea">
                			<div class="item">
		                		<div class="center">
		                			<img src="<?php echo base_url(); ?>/recursos/view.png" class='iconoItem'/>
		                		</div>
		                		<div class="fuente nombre center">Ver</div>
		                	</div>
                		</li>
                		<li name="opcionEditar" class="linea">
                			<div class="item">
		                		<div class="center">
		                			<img src="<?php echo base_url(); ?>/recursos/edit.png" class='iconoItem'/>
		                		</div>
		                		<div class="fuente nombre center">Editar</div>
		                	</div>
                		</li>
                		<li name="opcionCompartir" class="linea">
                			<div class="item">
		                		<div class="center">
		                			<img src="<?php echo base_url(); ?>/recursos/share.png" class='iconoItem'/>
		                		</div>
		                		<div class="fuente nombre center">Compartir</div>
		                	</div>
                		</li>
                		<li id="opcionEliminar" name="opcionEliminar" class="linea">
                			<div id="linkEliminar" class="item" name="<?php echo base_url()?>index.php/repositorio_uv/Documento_Controller/eliminar_documento/">
		                		<div class="center">
		                			<img src="<?php echo base_url(); ?>/recursos/delete.png" class='iconoItem'/>
		                		</div>
		                		<div class="fuente nombre center">Eliminar</div>
		                	</div>
                		</li>
                	</ul> 
                </div>
            </div>
        </div>
    </div>
</body>
</html>