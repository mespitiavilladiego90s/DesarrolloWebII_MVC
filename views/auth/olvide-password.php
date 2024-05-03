<div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
            <div class="card" style="border-radius: 1rem;">
                <div class="card-body p-4 p-lg-5 text-black">
                    <h1 class="nombre-pagina text-center my-4">Olvidé Password</h1>
                    <p class="descripcion-pagina text-center mb-4">Reestablece tu password escribiendo tu email a continuación</p>
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
                    <form class="formulario" action="/olvide" method="POST">
                        <div class="formulario-container">
                            <div class="campo form-outline mb-4">
                                <input type="email" id="email" class="form-control" name="email" placeholder="Tu Email" />
                                <label class="form-label" for="email">Email</label>
                            </div>
                            <input type="submit" class="btn btn-primary btn-block mb-4 w-100" value="Enviar Instrucciones">
                        </div>
                    </form>
                    <div class="text-center">
                        <div class="acciones">
                            <a href="/login">¿Ya tienes una cuenta? Inicia Sesión</a>
                            <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear Una</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
