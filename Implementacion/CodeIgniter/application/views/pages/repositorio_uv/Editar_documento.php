<script type="text/javascript" src="<?php echo base_url(); ?>js/editar_documento.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ckeditor/samples/js/sample.js"></script>
<div id="main">
	<div class="panelScroll">
		<div class="grid-width-100">
			<div id="editor"></div>
		</div>
		<div>
            <button type="button" class="boton botonOk" id="crear">Crear archivo</button> 
        </div>	
	</div>	
</div>
<div class="center">
  <input type="hidden" id="texto_documento" value="<?php echo $texto_documento; ?>">
		<label></label>
</div>

<div class="modal fade" id="modalCrearDocumento" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
           <?php echo form_open('repositorio_uv/Documento_Controller/modificar_documento/'.$id_documento, array('id' => 'modificar_documento')); ?>
                <div class="modal-body">
                    <input id="nombreDocumentoCrear" type="text" name="nombre" placeholder="Nombre del documento" value ='<?php echo $nombre; ?>'class="campoTexto campoTextoMed"/>
                   <label>Extensión</label>
                   <select id="opcionesDocumento" name="extension">
                   	<option value="docx" name="docx" selected="selected">.docx</option>
                   </select>
                   <input type="hidden" name="texto" id="texto">
                </div>
                <div class="modal-footer">
                    <input type="submit" value="Crear documento" class="boton botonOk botonReg" />
                </div>
            </form>
        </div>
    </div>
</div>
<script>initSample();</script>