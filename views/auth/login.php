<div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
            <div class="card" style="border-radius: 1rem;">
                <div class="card-body p-4 p-lg-5 text-black">
                    <h1 class="text-center">Ingresa tus datos para poder iniciar sesión.</h1>
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
                    <form class="formulario" method="POST" action="/login">
                        <!-- Email input -->
                        <div data-mdb-input-init class="campo form-outline mb-4">
                            <input type="email" id="email" class="form-control w-100" />
                            <label class="form-label" for="email" name="email">Dirección Email</label>
                        </div>
                        <!-- Password input -->
                        <div data-mdb-input-init class="campo form-outline mb-4">
                            <input type="password" id="password" class="form-control w-100" />
                            <label class="form-label" for="password" name="password">Password</label>
                        </div>
                        <!-- 2 column grid layout for inline styling -->
                        <div class="row mb-4">
                            <div class="col d-flex justify-content-center">
                                <!-- Checkbox -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="form2Example31" checked />
                                    <label class="form-check-label" for="form2Example31"> Recuérdame</label>
                                </div>
                            </div>
                            <div class="col">
                                <!-- Simple link -->
                                <a href="/olvide">¿Olvidaste tu contraseña?</a>
                            </div>
                        </div>
                        <!-- Submit button -->
                        <input type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4 w-100" value="Iniciar Sesión">
                        <!-- Register buttons -->
                        <div class="text-center">
                            <p>¿No tienes una cuenta? <a href="/crear-cuenta">Regístrate</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Captura las alertas enviadas desde PHP y las almacena en una variable JavaScript
    var alertas = <?php echo json_encode($alertas); ?>;
    console.log(alertas); // Verifica las alertas en la consola del navegador
</script>
