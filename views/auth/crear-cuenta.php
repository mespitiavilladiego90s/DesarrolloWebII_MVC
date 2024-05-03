<div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
            <div class="card" style="border-radius: 1rem;">
                <div class="card-body p-4 p-lg-5 text-black">
                    <h1 class="nombre-pagina text-center">Crear Cuenta</h1>
                    <p class="descripcion-pagina text-center">Llena el siguiente formulario para crear una cuenta</p>
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
                    <form class="formulario" method="POST" action="/crear-cuenta">
                        <div class="campo form-outline mb-4">
                            <input type="text" id="nombre" class="form-control w-100" name="nombre" placeholder="Tu Nombre" value="<?php echo s($usuario->nombre); ?>" />
                            <label class="form-label" for="nombre">Nombre</label>
                        </div>
                        <div class="campo form-outline mb-4">
                            <input type="text" id="apellido" class="form-control w-100" name="apellido" placeholder="Tu Apellido" value="<?php echo s($usuario->apellido); ?>" />
                            <label class="form-label" for="apellido">Apellido</label>
                        </div>
                        <div class="campo form-outline mb-4">
                            <input type="tel" id="telefono" class="form-control w-100" name="telefono" placeholder="Tu Teléfono" value="<?php echo s($usuario->telefono); ?>" />
                            <label class="form-label" for="telefono">Teléfono</label>
                        </div>
                        <div class="campo form-outline mb-4">
                            <input type="email" id="email" class="form-control w-100" name="email" placeholder="Tu E-mail" value="<?php echo s($usuario->email); ?>" />
                            <label class="form-label" for="email">E-mail</label>
                        </div>
                        <div class="campo form-outline mb-4">
                            <input type="password" id="password" class="form-control w-100" name="password" placeholder="Tu Password" />
                            <label class="form-label" for="password">Password</label>
                        </div>
                        <input type="submit" value="Crear Cuenta" class="btn btn-primary btn-block mb-4 w-100">
                    </form>
                    <div class="text-center">
                        <div class="acciones">
                            <a href="/login">¿Ya tienes una cuenta? Inicia Sesión</a>
                            <a href="/olvide">¿Olvidaste tu password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
