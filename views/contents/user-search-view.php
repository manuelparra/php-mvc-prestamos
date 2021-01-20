<?php
/**
 * Contents of User Search view.
 *
 * Contents of the User Search page view.
 *
 * @package View
 * @author Manuel Parra
 * @version 1.0.0
 */

if ($_SESSION['privilegio_spm'] != 1) {
    echo $insLoginController->force_close_session_controller();
    exit;
}
?>

<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR USUARIO
    </h3>
    <p class="text-justify">
    Esta vista permite realizar la búsqueda de usuarios registrados en el sistema, puede utilizar como parámetro de búsqueda el DNI, Nombre, Apellido, Teléfono, Usuario o Email y se mostrar cualquier registro con el
    que haya alguna coincidencia.
    </p>
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo SERVER_URL; ?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO USUARIO</a>
        </li>
        <li>
            <a href="<?php echo SERVER_URL; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE USUARIOS</a>
        </li>
        <li>
            <a class="active" href="<?php echo SERVER_URL; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR USUARIO</a>
        </li>
    </ul>
</div>

<!-- Content -->
<?php
if (!isset($_SESSION['busqueda_usuario']) || empty($_SESSION['busqueda_usuario'])) {
?>
<div class="container-fluid">
    <form class="form-neon ajax-form" action="<?php echo SERVER_URL; ?>endpoint/search-engine-ajax/" method="POST"
    id="new_search_form" data-form="default" autocomplete="off">
        <input type="hidden" name="modulo" value="usuario">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="inputSearch" class="bmd-label-floating">¿Qué usuario estas buscando?</label>
                        <input type="text" class="form-control" name="busqueda_inicial" id="inputSearch" maxlength="30">
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i> &nbsp; BUSCAR</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
<?php
} else {
?>
<div class="container-fluid">
    <form class="ajax-form" action="<?php echo SERVER_URL; ?>endpoint/search-engine-ajax/" method="POST"
    data-form="search">
        <input type="hidden" name="modulo" value="usuario">
        <input type="hidden" name="eliminar_busqueda" value="eliminar">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <p class="text-center" style="font-size: 20px;">
                        Resultados de la busqueda <strong>“<?php echo $_SESSION['busqueda_usuario']; ?>”</strong>
                    </p>
                </div>
                <div class="col-12">
                    <p class="text-center" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-raised btn-danger"><i class="far fa-trash-alt"></i> &nbsp; ELIMINAR BÚSQUEDA</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="container-fluid">
    <?php

    require_once "./controllers/userController.php";
    $insUser = new userController();

    echo $insUser->paginator_user_controller($current_page[1], 15, $_SESSION['privilegio_spm'], $_SESSION['id_spm'], $current_page[0], $_SESSION['busqueda_usuario']);

    ?>
</div>
<?php
}
?>
