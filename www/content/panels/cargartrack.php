<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">Eblooms</a></li>
    <li>Venta </li>
    <li>Cargar </li>
    <li class="active">Cargar tracking</li>
</ul>
<!-- FIN BREADCRUMB -->

<!-- CARGAR TRACKING -->
<div class="page-title">                    
    <h2><span class="fa fa-cloud-upload"></span> Cargar Trackings por achivo</h2>
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
                        <button type="submit" id="myButton" data-loading-text="Cargando..." class="btn btn-primary pull-right" autocomplete="off" data-toggle="tooltip" data-placement="right" title="Cargar trackings">Cargar tracking</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- FIN CARGAR TRACKING -->
