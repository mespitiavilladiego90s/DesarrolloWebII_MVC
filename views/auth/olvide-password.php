<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 col-lg-4">
            <div class="card my-5">
                <div class="card-header">
                    <h3>Olvidé</h3>
                    <div class="d-flex justify-content-end social_icon">
                        <span><i class="fab fa-facebook-square"></i></span>
                        <span><i class="fab fa-google-plus-square"></i></span>
                        <span><i class="fab fa-twitter-square"></i></span>
                    </div>
                </div>
                <div class="card-body">

                

                <form class="formulario" action="/olvide" method="POST">

                        <div class="input-group form-group mb-2 d-flex justify-content-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="email" id="email" placeholder="correo" name="email">
                        </div>

                        <div class="form-group d-flex justify-content-center">
                            <input type="submit" value="Enviar" class="btn float-right login_btn">
                        </div>
                </form>

                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-center links">
                        ¿Ya tienes una cuenta?<a href="/login">Inicia Sesión</a>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear Una</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

