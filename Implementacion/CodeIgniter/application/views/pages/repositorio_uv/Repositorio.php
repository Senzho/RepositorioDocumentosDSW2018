    <div id="panel" class="vistaPrincipal scrollVertical">
    	<div id="contenedor" class="contenedor">
            <?php
                for ($i = 0; $i < count($documentos); $i ++){
                    $documento = $documentos[$i];
                    $id = $documento['id'];
                    $nombre = $documento['nombre'];
                    $fecha_registro = $documento['fecha_registro'];
                    $icono = $documento['extension'];
                    $recurso = base_url() . '/recursos/' . $icono . '.png';
                    $permiso;
                    if (array_key_exists('edicion', $documento)){
                        $permiso = $documento['edicion'] ? ' editar' : ' ver';
                    }else{
                        $permiso = ' todo';
                    }
                    echo "<div class='documento" . $permiso . " $icono'" . "' id=$id data-toggle='modal' data-target='#modalOpcionesDocumento'>
                            <div class='center'>
                                <img src=$recurso class='icono'/>
                            </div>
                            <div class='fuente nombre'>$nombre</div>
                            <div class='fuente fecha'>$fecha_registro</div>";
                    if (array_key_exists('academico', $documento)){
                        $academico = 'De: ' . $documento['academico'];
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
                		<li id="opcionVer" name="opcionVer" class="linea">
                			<a id="urlVerDocumento" href="<?php echo base_url().'index.php/repositorio_uv/Documento_Controller/vista/visualizar/'?>">
                                <div class="item">
                                    <div class="center">
                                        <img src="<?php echo base_url(); ?>/recursos/view.png" class='iconoItem'/>
                                    </div>
                                    <div class="fuente nombre center">Ver</div>
                                </div>         
                            </a>
                		</li>
                		<li id="opcionEditar" name="opcionEditar" class="linea">
                			<a id="urlEditarDocumento"  href="<?php echo base_url().'index.php/repositorio_uv/Documento_Controller/vista/editar/'?>">
                                <div class="item">
                                    <div class="center">
                                        <img src="<?php echo base_url(); ?>/recursos/edit.png" class='iconoItem'/>
                                    </div>
                                    <div class="fuente nombre center">Editar</div>
                                </div>        
                            </a>
                		</li>
                        <li id="opcionFirmar" name="opcionFirmar" class="linea">
                            <div id="linkFirmar" class="item" name="<?php echo base_url()?>index.php/repositorio_uv/Firma_Controller/firmar_documento/">
                                <div class="center">
                                    <img src="<?php echo base_url(); ?>/recursos/signature.png" class='iconoItem'/>
                                </div>
                                <div class="fuente nombre center">Firmar</div>
                            </div>
                        </li>
                        <li id="opcionVerFirmas" name="opcionVerFirmas" class="linea" data-toggle="modal" data-target='#modalFirmas'>
                            <div id="linkVerFirmas" class="item" name="<?php echo base_url()?>index.php/repositorio_uv/Firma_Controller/obtener_firmas/">
                                <div class="center">
                                    <img src="<?php echo base_url(); ?>/recursos/signature.png" class='iconoItem'/>
                                </div>
                                <div class="fuente nombre center">Ver firmas</div>
                            </div>
                        </li>
                		<li id="opcionCompartir" name="opcionCompartir" class="linea" data-toggle="modal" data-target='#modalCompartir'>
                			<div class="item">
		                		<div class="center">
		                			<img src="<?php echo base_url(); ?>/recursos/share.png" class='iconoItem'/>
		                		</div>
		                		<div class="fuente nombre center">Compartir</div>
		                	</div>
                		</li>
                        <li id="opcionExportar" name="opcionExportar" class="linea" data-toggle="modal" data-target='#modalExportar'>
                            <div class="item">
                                <div class="center">
                                    <img src="<?php echo base_url(); ?>/recursos/download.png" class='iconoItem'/>
                                </div>
                                <div class="fuente nombre center">Exportar</div>
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
    <div class="modal fade" id="modalCompartir" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="fuente h3">Compartir</h3>
                </div>
                <form id="formularioCompartir" action="<?php echo base_url()?>index.php/repositorio_uv/Documento_Controller/solicitar_comparticion/">
                    <div class="modal-body center">
                        <input id="correoCompartir" type="email" name="correo" placeholder="Correo" class="campoTexto campoTextoMed"/>
                        <select id="selectEdicion" name="edicion">
                            <option value="si" selected="selected">Puede editar</option>
                            <option value="no">No puede editar</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <input id="botonCompartir" type="submit" value="Compartir" class="boton botonOk botonReg" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalExportar" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="fuente h3">Exportar</h3>
                </div>
                <form id="formularioExportar" action="<?php echo base_url()?>index.php/repositorio_uv/Documento_Controller/descargar_documento/">
                    <div class="modal-body center">
                        <select id="selectExtension" name="extension">
                            <option value="docx" selected="selected">.docx</option>
                            <option value="pdf">.pdf</option>
                            <option value="xlsx">.xlsx</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <input id="botonExportar" type="submit" value="Exportar" class="boton botonOk botonReg" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalFirmas" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="fuente h3">Firmas</h3>
                </div>
                <div id="cuerpoModalFirmas" class="modal-body scrollVertical"></div>
            </div>
        </div>
    </div>
</body>
</html>
