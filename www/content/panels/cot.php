<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">BurtonTech</a></li>
    <li>Venta </li>
    <li>Cargar </li>
    <li class="active">Cargar órdenes</li>
</ul>
<!-- FIN BREADCRUMB -->

<!-- CARGAR ORDENES -->
<div class="page-title">                    
    <h2><span class="fa fa-cloud-upload"></span> Cargar ordenes por achivo</h2>
</div>
<div class="page-content-wrap">
    <?php
    if (isset($_SESSION['msg'])) {
        echo '
                <div class="row">
                    <div class="col-md-4">
                        <div class="widget widget-';
        echo $_SESSION['box'];
        echo ' widget-item-icon">
                            <div class="widget-item-left">
                                <span class="fa fa-exclamation"></span>
                            </div>
                            <div class="widget-data">
                                <div class="widget-title">Notificación</div>
                                <div class="widget-subtitle">
                                    <div role="alert">
                                        ' . $_SESSION['msg'] . '
                                    </div>
                                </div>
                            </div>
                            <div class="widget-controls">                                
                                <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Remove Widget"><span class="fa fa-times"></span></a>
                            </div>                             
                        </div>
                    </div>
                </div>
        ';
        unset($_SESSION['msg']);
    }
    ?>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Seleccione el archivo de ordenes</h3>                                    
                    <p>Seleccione el archivo o arrastrelo hasta el botón para cargar</p>
                    <form action="scripts/cot_controller.php" method="post" enctype="multipart/form-data" name="Upload" id="Upload">
                        <input style="display: block; margin-right: 25px;" type="file" class="fileinput btn-danger" name="archivo[]" id="ordenacargar" data-filename-placement="inside" title="Buscar archivo de órdenes"/>
                        <button name="subir_ordenes" type="submit" id="myButton" data-loading-text="Cargando..." class="btn btn-primary pull-right" autocomplete="off" data-toggle="tooltip" data-placement="right" title="Cargar órdenes">Cargar órdenes</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- FIN CARGAR ORDENES -->

<!-- CARGAR TRACKING -->
<div class="page-content-wrap">                   
    <div class="row">
        <div class="col-md-6">
            <div class="page-title">                    
                <h2><span class="fa fa-cloud-upload"></span> Cargar Trackings archivo UPS</h2>                     
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Seleccione el archivo de trackings</h3>                                    
                    <p>Seleccione el archivo o arrastrelo hasta el botón para cargar</p>
                    <?php
                    if ($rol == 3) {
                        echo "<form action=\"php/subirarchivo5.php\" method=\"post\" enctype=\"multipart/form-data\" name=\"Upload\" id=\"Upload\">";
                    } else {
                        echo "<form action=\"scripts/fileupload.php\" method=\"post\" enctype=\"multipart/form-data\" name=\"Upload\" id=\"Upload\">";
                    }
                    ?>
                    <input style="display: block; margin-right: 25px;" type="file" class="fileinput btn-danger" name="archivo[]" id="trackingacargar" data-filename-placement="inside" title="Buscar archivo de trackings"/>
                    <button type="submit" id="myButton" name="fileupload" value="cartrack" data-loading-text="Cargando..." class="btn btn-primary pull-right" autocomplete="off" data-toggle="tooltip" data-placement="right" title="Cargar trackings">Cargar tracking</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="page-title">                    
                <h2><span class="fa fa-cloud-upload"></span> Cargar Trackings archivo FedEx </h2>                    
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Seleccione el archivo de trackings</h3> 
                    <p>Seleccione el archivo o arrastrelo hasta el botón para cargar</p>
                    <?php
                    if ($rol !== "3") {
                        echo "<form action=\"main.php?panel=trackfedex.php\" method=\"post\" enctype=\"multipart/form-data\" name=\"Upload\" id=\"Upload\">";
                    } else {
                        echo "<form action=\"scripts/cot_controller.php\" method=\"post\" enctype=\"multipart/form-data\" name=\"Upload\" id=\"Upload\">";
                    }
                    ?>
                    <input style="display: block; margin-right: 25px;" type="file" class="fileinput btn-danger" name="archivo[]" id="trackingacargar" data-filename-placement="inside" title="Buscar archivo de trackings"/>
                    <button type="submit" id="myButton" name="subir_trackingsfedex_fincas" value="cartrack" data-loading-text="Cargando..." class="btn btn-primary pull-right" autocomplete="off" data-toggle="tooltip" data-placement="right" title="Cargar trackings">Cargar tracking</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- FIN CARGAR TRACKING -->

<!-- CARGAR TRACKING ALTERNATIVO-->
<div class="page-title">                    
    <h2><span class="fa fa-cloud-upload"></span> Cargar Trackings por achivo <b>(S/R)</b></h2>
    <button style="margin-left: 16px; margin-right: 16px;" class="btn btn-success pull-left" data-toggle="modal" data-target="#modal_activar_subida"><span class="fa fa-check-square-o"></span> </span> Activar Subida </button>
</div>
<div class="page-content-wrap">                   
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Seleccione el archivo de trackings</h3>                                    
                    <p>Seleccione el archivo o arrastrelo hasta el botón para cargar</p>
                    <form action="scripts/fileupload.php" method="post" enctype="multipart/form-data" name="Upload" id="Upload">
                        <?php
                        if (isset($_GET['uploadsr'])) {
                            echo "   
                                <input  style=\"display: block; margin-right: 25px;\" type=\"file\" class=\"fileinput btn-danger\" name=\"archivo3[]\" id=\"trackingacargar\" data-filename-placement=\"inside\" title=\"Buscar archivo de trackings\"/>
                                <button type=\"submit\" id=\"myButton\"  name=\"fileupload\" value=\"cartrasr\" data-loading-text=\"Cargando...\" class=\"btn btn-primary pull-right\" autocomplete=\"off\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Cargar trackings sin restricci&oacute;n\">Cargar tracking SIN RESTRICCI&Oacute;N</button>
                            ";
                        } else {
                            echo "   
                                <input disabled=\"disabled\" style=\"display: block; margin-right: 25px;\" type=\"file\" class=\"fileinput btn-danger\" name=\"archivo[]\" id=\"trackingacargar\" data-filename-placement=\"inside\" title=\"Buscar archivo de trackings\"/>
                                <button disabled=\"disabled\" type=\"submit\" id=\"myButton\" data-loading-text=\"Cargando...\" class=\"btn btn-primary pull-right\" autocomplete=\"off\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Usted debe activar la subida\">Cargar tracking SIN RESTRICCI&Oacute;N</button>
                            ";
                        }
                        ?>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- FIN CARGAR TRACKING ALTERNATIVO-->

<div  class="modal animated fadeIn" id="modal_activar_subida" tabindex="-1" role="dialog" aria-labelledby="largeModalHead" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="col-md-12">
                <div class="login-container login-v2">
                    <div class="login-body" style="margin-bottom: 50px;width: 400px;margin-left: 250px;margin-top: 50px;">
                        <div class="login-title" style="margin-bottom: 16px;"><strong>Por favor introduce tus datos</strong>, para activar el panel.</div>
                        <form action="scripts/activartrackingsSR.php" class="form-horizontal" method="post">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <span class="fa fa-user"></span>
                                        </div>
                                        <input name="user" type="text" class="form-control" placeholder="Nombre de usuario"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <span class="fa fa-lock"></span>
                                        </div>                                
                                        <input name="pass" type="password" class="form-control" placeholder="Contraseña"/>
                                    </div>
                                </div>
                            </div>
                            <!--                            <div class="form-group">
                                                            <div class="col-md-6">
                                                                <a href="#">Forgot your password?</a>
                                                            </div>          
                                                            <div class="col-md-6 text-right">
                                                                <a href="#">Create an account</a>
                                                            </div>              
                                                        </div>-->
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">Continuar</button>
                                </div>
                                <div style="margin-top: 16px;" class="col-md-12">
                                    <button type="button" class="btn btn-default btn-lg btn-block" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </form>
                    </div>                                    
                </div>
            </div>
        </div>
    </div>
</div>
<div id="cot_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header label-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:black;text-align: center;"><i class="fa fa-file"></i> Reporte de Subida</h4>
            </div>
            <div class="modal-body" style="text-align: center">
                <?php if (isset($_SESSION['modalcontent'])) {
                    echo $_SESSION['modalcontent'];
                } ?>
            </div>
            <div class="modal-footer label-primary">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
