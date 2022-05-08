<?php
include("header.php");
$hidden = "";
if (isset($_GET["x"])) {
    $hidden = $_GET["x"];
}
?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
            </li>
            <li class="breadcrumb-item active">
                <strong>SERVICIO DE LOCALIDADES RURALES</strong>
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
                    <div class="load-data">Cargando información...</div>
                    <div class="content-filters">
                        <input type="hidden" name="debug" id="debug" value="<?php echo $hidden ?>">
                        <div>
                            <div class="head-filter">Seleccione el Estado</div>
                            <select name="id_estado" id=select-estado>
                                <option value="">Seleccione una opción</option>
                            </select>
                        </div>
                        <div>
                            <div class="head-filter">Seleccione el Municipio</div>
                            <input name="municipio" id="select-municipio" disabled>
                            <input type="hidden" name="id_municipio" id="select-municipio-id">
                        </div>
                        <div>
                            <div class="head-filter">Seleccione el Localidad</div>
                            <input name="localidad" id="select-localidad" disabled>
                            <input type="hidden" name="id_localidad" id="select-localidad-id">
                        </div>
                        <div>
                            <div class="head-filter">Seleccione el año de consulta</div>
                            <select name="anio" id=anio>
                                <option value="2010">2010</option>
                                <option value="2020">2020</option>
                                <option value="2010-2020">2010-2020</option>
                            </select>
                        </div>
                        <div>
                            <div class="head-filter">Seleccione un tema</div>
                            <select name="id_tema" id=select-tema>
                            </select>
                        </div>
                        <div>
                            <div class="head-filter">Seleccione un subtema</div>
                            <select name="id_subtema" id=select-subtema>
                            </select>
                        </div>
                        <div>
                            <div class="head-filter head-indicadores">Seleccione los indicadores a consultar</div>
                            <div class="content-indicadores">
                                <div><input type="checkbox" class="indicadores-check" id="check-all">Seleccionar todos</div>
                                <div id="check-indicadores"></div>
                            </div>
                        </div>
                        <div class="btn-indicadores">
                            <button id="btn-buscar">Ver indicadores</button>
                        </div>
                    </div>

                    <div class="res-x"></div>
                    <div class="res-sql"></div>


                    <!--<font>NOTA:</font> Si no se encuentra el n&uacute;mero de expediente, cerci&oacute;rese de haberlo subido antes.-->
                    <table id="footable-list" class="tab-list get-module" data-module="select" data-paging="true" data-filtering="false" data-sorting="true" data-filter-placeholder="Buscar" data-filter-connectors="false" data-paging-limit="3"></table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include("footer.php");
?>