<script type="text/javascript" src="<?php echo base_url(); ?>js/crear_documento.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ckeditor/samples/js/sample.js"></script>
<link href="<?php echo base_url(); ?>js/ckeditor/samples/css/samples.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>js/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css" rel="stylesheet" type="text/css">
<div id="main">
	<?php echo form_open('repositorio_uv/Documento_Controller/crear_documento', array('id' => 'crear_documento')); ?>
		<div class="panelScroll">
			<div class="grid-width-100">
				<div id="editor">	
				</div>
			</div>
				<input type="hidden" name="texto" id="texto">
				<input type="submit" class="boton botonOk" value="Generar archivo"/>
		</div>	
	</form>
</div>
<script>initSample();</script>
