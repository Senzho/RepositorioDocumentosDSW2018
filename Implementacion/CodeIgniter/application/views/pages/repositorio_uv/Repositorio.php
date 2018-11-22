    <div id="panel" class="panelScroll">
    	<div id="contenedor" class="contenedor">
            <?php
                for ($i = 0; $i < count($documentos); $i ++){
                    $id = $documentos[$i]['id'];
                    $nombre = $documentos[$i]['nombre'];
                    $fecha_registro = $documentos[$i]['fecha_registro'];
                    $recurso = base_url() . '/recursos/pdf.png';
                    echo "<div class='documento' id=$id>
                            <div class='seccionIcono'>
                                <img src=$recurso class='icono'/>
                            </div>
                            <div class='fuente nombre'>$nombre</div>
                            <div class='fuente fecha'>$fecha_registro</div>
                        </div>";
                }
            ?>
    	</div>
    </div>
</body>
</html>