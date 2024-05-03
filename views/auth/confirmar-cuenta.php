<div class="container d-flex justify-content-center align-items-center h-100 mb-2">
    <div class="text-center">
        <h1 class="nombre-pagina">Confirmar Cuenta</h1>

        <?php
        foreach ($alertas as $key => $mensajes) :
            foreach ($mensajes as $mensaje) :
                ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $mensaje; ?>
                </div>
            <?php
            endforeach;
        endforeach;
        ?>

        <div class="acciones">
            <a href="/login">Iniciar Sesión</a>
        </div>
    </div>
</div>
