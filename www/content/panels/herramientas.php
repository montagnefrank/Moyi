<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">BurtonTech</a></li>
    <li class="active">Herramientas</li>
</ul>
<div class="col-md-3">
    <div class="widget widget-primary widget-item-icon" title='Analiza todos los archivos existentes en la base (que no hayan sido analizados previamente), extrae la informacion principal y la vac&iacute;a en un archivo Excel, todos los archivos son migrados de la carpeta Auditados a la carpeta XMLOSD ///////ESTE PROCEDIMIENTO ES IRREVERSIBLE/////// Este proceso se ejecutar&aacute; hasta agotarse el tiempo disponible, para continuar, ejecute nuevamente' >
        <div class="widget-item-left">
            <a href="php/Xmlosd.php"><i class="fa fa-bug" aria-hidden="true"></i></a>
        </div>
        <div class="widget-data">
            <div class="widget-int num-count">
                XMLOSD
            </div>
            <div class="widget-title">Archivos entrantes desglosadas por item</div>
            <div class="widget-subtitle">EDI Connect</div>
        </div>
        <div class="widget-controls">                                
            <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
        </div>                            
    </div>
</div>
<div class="col-md-3">
    <div class="widget widget-primary widget-item-icon" title='Analiza todos los archivos existentes en la base (que no hayan sido analizados previamente), extrae la informacion principal y la compara contra la base de datos, luego la envia a un archivo excel, todos los archivos son migrados de la carpeta Auditados a la carpeta Validate ///////ESTE PROCEDIMIENTO ES IRREVERSIBLE/////// Este proceso se ejecutar&aacute; hasta agotarse el tiempo disponible, para continuar, ejecute nuevamente'>
        <div class="widget-item-left">
            <a href="php/Xmlvalidate.php"><i class="fa fa-refresh" aria-hidden="true"></i></a>
        </div>
        <div class="widget-data">
            <div class="widget-int num-count">
                VALIDADOR
            </div>
            <div class="widget-title">Validar archivos contra Base de datos</div>
            <div class="widget-subtitle">EDI Connect</div>
        </div>
        <div class="widget-controls">                                
            <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
        </div>                            
    </div>
</div>
<div class="col-md-3">
    <div class="widget widget-primary widget-item-icon" title='Toma los pedidos con errores y los inserta en la base con una fecha ingresada por el usuario'>
        <div class="widget-item-left">
            <a data-target="#modal_fecha_tablaerror" data-toggle="modal" href="#modal_fecha_tablaerror"><i class="fa fa-calendar" aria-hidden="true"></i></a>
        </div>
        <div class="widget-data">
            <div class="widget-int num-count">
                Tabla Error
            </div>
            <div class="widget-title">Insertar</div>
            <div class="widget-subtitle">Archivos que tienen problemas de RAD</div>
        </div>
        <div class="widget-controls">                                
            <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="top" title="Ocultar Widget"><span class="fa fa-times"></span></a>
        </div>                            
    </div>
</div>
<!-- CARGAR TRACKING -->
<div class="page-title">                    
    <h2><span class="fa fa-cloud-upload"></span> Cargar Trackings por achivo <b>(VERSION BurtonTech 3.0)</b></h2>
</div>
<div class="page-content-wrap">                   
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Seleccione el archivo de trackings</h3>                                    
                    <p>Seleccione el archivo o arrastrelo hasta el botón para cargar</p>
                    <form action="php/subirarchivo3.php" method="post" enctype="multipart/form-data" name="Upload" id="Upload">
                        <input style="display: block; margin-right: 25px;" type="file" class="fileinput btn-danger" name="archivo[]" id="trackingacargar" data-filename-placement="inside" title="Buscar archivo de trackings"/>
                        <button type="submit" id="myButton" name="fileupload" value="cartrack" data-loading-text="Cargando..." class="btn btn-primary pull-right" autocomplete="off" data-toggle="tooltip" data-placement="right" title="Cargar trackings">Cargar tracking</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- FIN CARGAR TRACKING -->
<!-- ELIMINAR TRACKING -->
<div class="page-title">                    
    <h2><i class="fa fa-trash" aria-hidden="true"></i> Eliminar Trackings por achivo</h2>
</div>
<div class="page-content-wrap">                   
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Seleccione el archivo de trackings</h3>                                    
                    <p>Seleccione el archivo o arrastrelo hasta el botón para cargar</p>
                    <form action="scripts/fileupload.php" method="post" enctype="multipart/form-data" name="Upload" id="Upload">
                        <input style="display: block; margin-right: 25px;" type="file" class="fileinput btn-danger" name="archivo2[]" id="trackingaeliminar" data-filename-placement="inside" title="Buscar archivo de trackings"/>
                        <button type="submit" id="myButton" name="fileupload" value="deletetrackings" data-loading-text="Cargando..." class="btn btn-primary pull-right" autocomplete="off" data-toggle="tooltip" data-placement="right" title="Cargar trackings">Eliminar trackings</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- FIN ELIMINAR TRACKING -->
<!--MODAL DE TABLA ERROR-->
<div style="z-index: 999;" class="modal animated fadeIn" id="modal_fecha_tablaerror" tabindex="-1" role="dialog" aria-labelledby="largeModalHead" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="scripts/tablaerror_rad.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                    <h4 class="modal-title" id="smallModalHead">Introduzca la fecha del RAD</h4>
                </div> 
                <div id="hacped_nuevopedido" class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Introdzca la fecha</label>
                        <div class="col-md-5">
                            <input type="text" name="fecha_error" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                    <input class="btn btn-success" value="Registrar" type="submit">
                </div>
            </form>
        </div>
    </div>
</div>
<!--FIN MODAL TABLA ERROR-->