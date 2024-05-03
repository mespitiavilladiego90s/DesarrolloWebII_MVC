<div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
            <div class="card" style="border-radius: 1rem;">
                <div class="card-body p-4 p-lg-5 text-black">
                    <h1 class="nombre-pagina text-center my-4">Recuperar Password</h1>
                    <p class="descripcion-pagina text-center mb-4">Coloca tu nuevo password a continuación</p>
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
                    <?php if($error) return; ?>
                    <form class="formulario" method="POST">
                        <div class="formulario-container">
                            <div class="campo form-outline mb-4">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Tu Nuevo Password"
                                />
                                <label class="form-label" for="password">Password</label>
                            </div>
                            <input type="submit" class="btn btn-primary btn-block mb-4 w-100" value="Guardar Nuevo Password">
                        </div>
                    </form>
                    <div class="text-center">
                        <div class="acciones">
                            <a href="/login">¿Ya tienes cuenta? Iniciar Sesión</a>
                            <a href="/crear-cuenta">¿Aún no tienes cuenta? Obtener una</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
