<?php
/**
 * Contents of User's Login view.
 *
 * Contents of the User Login page view.
 *
 * @package View
 * @author Manuel Parra
 * @version 1.0.0
 */

if (isset($_POST['usuario_log']) && isset($_POST['clave_log'])) {
    require_once "./controllers/loginController.php";
    $insLogin = new loginController();
    echo $insLogin->login_controller();
}
?>

<div class="login-container">
    <div class="login-content">
        <p class="text-center">
            <i class="fas fa-user-circle fa-5x"></i>
        </p>
        <p class="text-center">
            Inicia sesión con tu cuenta
        </p>
        <form action="" method="POST" autocomplete="off" >
            <div class="form-group">
                <label for="UserName" class="bmd-label-floating"><i class="fas fa-user-secret"></i> &nbsp; Usuario</label>
                <input type="text" class="form-control" id="UserName" name="usuario_log" pattern="[a-zA-Z0-9]{1,35}" maxlength="35" required="" >
            </div>
            <div class="form-group">
                <label for="UserPassword" class="bmd-label-floating"><i class="fas fa-key"></i> &nbsp; Contraseña</label>
                <input type="password" class="form-control" id="UserPassword" name="clave_log" pattern="^(?=.*\d)(?=.*[\u0021-\u002b\u003c-\u0040])(?=.*[A-Z])(?=.*[a-z])\S{8,100}$" maxlength="100" required="" >
            </div>
            <button type="submit" class="btn-login text-center">INICIAR SESIÓN</button>
        </form>
    </div>
</div>

