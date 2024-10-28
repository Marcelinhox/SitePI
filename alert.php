<?php
function exibirAlerta($tipo, $titulo, $mensagem) {
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '$tipo',
                title: '$titulo',
                text: '$mensagem',
            });
        });
    </script>
    ";
}
?>
