<div class="page-content-wrap">                   
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3><span class="fa fa-upload"></span> Seleccione el archivo de ordenes</h3>                                    
                    <p>Seleccione el archivo o arrastrelo hasta la pantalla para cargar</p>
                    <form action="php/subirarchivo.php" method="post" enctype="multipart/form-data" name="Upload" id="Upload">
                        <input style="margin-bottom: 10px" type="file" name="archivo[]"/>
                        <button type="submit" id="myButton" data-loading-text="Cargando..." class="btn btn-primary" autocomplete="off" data-toggle="tooltip" data-placement="right" title="Cargar órdenes">Cargar órdenes</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>