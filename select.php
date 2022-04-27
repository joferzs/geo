<?php
include("header.php");
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.php">Inicio</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>Select</strong>
            </li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <div class="search-field">
                        <div><h5></h5></div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="fade" id="editor-modal" tabindex="-1" role="dialog" aria-labelledby="editor-title">
                        <div class="modal-dialog" role="document">
                            <form class="modal-content form-horizontal" id="editor" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title" id="editor-title">Nuevo registro</h4>
                                </div>
                                <div class="modal-body">
                                    <input type="number" id="id_base" name="id_base" class="hidden"/>
                                    <div class="form-group required">
                                        <label for="base" class="control-label">Base</label>
                                        <div class="">
                                            <input type="text" name="base" class="form-control" id="base" required>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label for="direccion" class="control-label">Dirección</label>
                                        <div class="">
                                            <input type="text" name="direccion" class="form-control" id="direccion" required>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-custom">
                                        <label for="dob" class="control-label">Activo</label>
                                        <div class="">
                                            No <input type="radio" name="activo" value="N">
                                            Sí <input type="radio" name="activo" value="S" checked>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="btn-guardar" class="btn btn-primary">Guardar registro</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="content-filters">
                        <div>
                            Seleccione el Estado
                            <input name="estado" id="select-estado">
                            <input type="hidden" name="id_estado" id="select-estado-id"> Intenta: Guerrero
                        </div>
                        <div>
                            Seleccione el Municipio
                            <input name="municipio" id="select-municipio" disabled>
                            <input type="hidden" name="id_municipio" id="select-municipio-id"> Intenta: Atlixtac
                        </div>
                        <div>
                            Seleccione el Localidad
                            <input name="localidad" id="select-localidad" disabled>
                            <input type="hidden" name="id_localidad" id="select-localidad-id"> Intenta: Chimixtla
                        </div>
                        <div>
                            <button id="btn-buscar">Ver indicadores</button>
                        </div>
                    </div>
                    <!--<font>NOTA:</font> Si no se encuentra el n&uacute;mero de expediente, cerci&oacute;rese de haberlo subido antes.-->
                    <table id="footable-list" class="tab-list get-module" data-module="select" data-paging="true" data-filtering="true" data-sorting="true" data-filter-placeholder="Buscar por base, dirección..." data-filter-connectors="false" data-paging-limit="3"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include("footer.php");
?>