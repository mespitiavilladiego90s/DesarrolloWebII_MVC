<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 col-lg-4">
            <div class="card my-5">
                <div class="card-header">
                    <h3>Login</h3>
                    <div class="d-flex justify-content-end social_icon">
                        <span><i class="fab fa-facebook-square"></i></span>
                        <span><i class="fab fa-google-plus-square"></i></span>
                        <span><i class="fab fa-twitter-square"></i></span>
                    </div>
                </div>
                <div class="card-body">

                

                    <form class="formulario" method="POST" action="/login">

                        <div class="input-group form-group mb-2 d-flex justify-content-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="email" id="email" placeholder="username" name="email">
                        </div>

                        <div class="input-group form-group mb-2 d-flex justify-content-center">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" id="password" placeholder="password" name="password">
                        </div>

                        <div class="form-group d-flex justify-content-center">
                            <input type="submit" value="Login" class="btn float-right login_btn">
                        </div>

                    </form>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-center links">
                        No tienes una cuenta?<a href="/crear-cuenta">Registrate</a>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="/olvide">Olvidaste tu contraseña?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

