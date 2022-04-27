<?php
include("header.php");
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Bienvenido</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">
                <strong>Inicio</strong>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                </div>
                <div class="ibox-content get-module" data-module="inicio">
                    <h3 class="welcome-title">gfsd.</h3>
                    <?php
                    /*print_r($_SESSION);
                    echo $_ENV['theme'];*/
                    ?>
                    <div class="notify-response"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include("footer.php");
?>