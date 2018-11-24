    <div id="panel" class="panelScroll">
    	<div id="contenedor" class="contenedor">
            <?php
                for ($i = 0; $i < count($documentos); $i ++){
                    $documento = $documentos[$i];
                    $id = $documento['id'];
                    $nombre = $documento['nombre'];
                    $fecha_registro = $documento['fecha_registro'];
                    $recurso = base_url() . '/recursos/pdf.png';
                    echo "<div class='documento' id=$id>
                            <div class='seccionIcono'>
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
</body>
</html>