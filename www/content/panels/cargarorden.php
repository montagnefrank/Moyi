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
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Seleccione el archivo de ordenes</h3>                                    
                    <p>Seleccione el archivo o arrastrelo hasta el botón para cargar</p>
                    <form action="php/subirarchivo.php" method="post" enctype="multipart/form-data" name="Upload" id="Upload">
                        <input style="display: block; margin-right: 25px;" type="file" class="fileinput btn-danger" name="archivo[]" id="ordenacargar" data-filename-placement="inside" title="Buscar archivo"/>
                        <button type="submit" id="myButton" data-loading-text="Cargando..." class="btn btn-primary pull-right" autocomplete="off" data-toggle="tooltip" data-placement="right" title="Cargar órdenes">Cargar órdenes</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- FIN CARGAR ORDENES -->