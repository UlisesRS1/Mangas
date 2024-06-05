<div class="modal fade" id="uploadResultModal" tabindex="-1" aria-labelledby="uploadResultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadResultModalLabel">Resultados de la Subida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                if (isset($_SESSION['messages'])) {
                    foreach ($_SESSION['messages'] as $message) {
                        echo "<p>$message</p>";
                    }
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="upload_manga.php" class="btn btn-primary" onclick="location.reload()">Volver al formulario</a>
            </div>
        </div>
    </div>
</div>
