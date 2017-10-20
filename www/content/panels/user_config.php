<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">BurtonTech</a></li>
    <li class="active">Configuración</li>
</ul>
<!-- BREADCRUMB -->
<div class="page-title">                    
    <h2><span class="fa fa-cog"></span> Editar configuración de la cuenta</h2>
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
    <div class="col-md-3">
        <form action="#" class="form-horizontal">
            <div class="panel panel-default">                                
                <div class="panel-body">
                    <h3><span class="fa fa-user"></span> <?php
                        $select_username = mysqli_query($link, "SELECT cpnombre,finca FROM tblusuario WHERE cpuser='$user'");
                        $username = mysqli_fetch_array($select_username);
                        echo $username[0];
                        ?></h3>
                    <p><span class="fa fa-industry"></span>  <?php
                        echo $username[1];
                        ?></p>
                    <div class="text-center" id="user_image">
                        <img src="<?php
                        $isavatar = "img/users/" . $user . ".jpg";
                        if (file_exists($isavatar)) {
                            echo "img/users/" . $user;
                        } else {
                            echo "img/users/default";
                        }
                        ?>.jpg" class="img-thumbnail"/>
                    </div>                                    
                </div>
                <div class="panel-body form-group-separated">
                    <div class="form-group">                                        
                        <div class="col-md-12 col-xs-12">
                            <a href="#" class="btn btn-info btn-block btn-rounded" data-toggle="modal" data-target="#modal_change_photo">Cambiar Foto</a>
                        </div>
                    </div>
                    <div class="form-group">                                        
                        <div class="col-md-12 col-xs-12">
                            <a href="#" class="btn btn-danger btn-block btn-rounded" data-toggle="modal" data-target="#modal_change_password">Cambiar contraseña</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <form id="themeform" name="themeform" class="form-horizontal" action="scripts/cambiartema.php" method="post" enctype="multipart/form-data">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="fa fa-edit"></span> Cambiar tema </h3>
                </div>
                <div class="panel-body">
                    <p> Seleccione un tema de la lista</p>
                    <div class="panel-body list-group">
                        <a style="background-color: #3780BF;" class="list-group-item"><span class="fa fa-paint-brush"></span> Azul <label class="pull-right check"><input type="radio" class="icheckbox iradio" name="theme" value="blue" checked="checked"/></label></a>
                        <a style="background-color: #37bf39;" class="list-group-item"><span class="fa fa-paint-brush"></span> Verde <label class="pull-right check"><input type="radio" class="icheckbox iradio" name="theme" value="green"/></label></a>
                        <a style="background-color: #bf3737;" class="list-group-item"><span class="fa fa-paint-brush"></span> Rojo <label class="pull-right check"><input type="radio" class="icheckbox iradio" name="theme" value="red"/></label></a>
                        <a style="background-color: #bdbdbd;" class="list-group-item"><span class="fa fa-paint-brush"></span> Claro <label class="pull-right check"><input type="radio" class="icheckbox iradio" name="theme" value="light"/></label></a>
                        <a style="background-color: #2d3945;" class="list-group-item"><span class="fa fa-paint-brush"></span> Oscuro <label class="pull-right check"><input type="radio" class="icheckbox iradio" name="theme" value="dark"/></label></a>
                    </div>
                </div>
                <div class="panel-footer">
                    <button class="pull-right btn btn-default" name="newtheme"  type="submit"  id="newtheme" data-toggle="tooltip" data-placement="right" title="Cambiar tema"><span class="fa fa-edit"></span> Cambiar tema</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-3">
        <?php
        $result_wgs_controls = mysqli_query($link, "SELECT wg_lpb,wg_ord,wg_pvo,wg_par,wg_ecf,wg_reh,wg_odi,wg_pod,wg_auo,wg_irr,wg_tco,wg_nol FROM tblusuario WHERE cpuser ='" . $user . "'");
        $wgs_controls = mysqli_fetch_array($result_wgs_controls);
        ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <h3><span class="fa fa-cube"></span> Widgets</h3>
                <p>Activar o desactivar Widgets del inicio</p>
            </div>
            <div class="panel-body form-horizontal form-group-separated"> 
                <form action="scripts/actualizarwidgets.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-md-8 col-xs-8 control-label">Mensaje Bienvenida</label>
                        <div class="col-md-4 col-xs-4">
                            <label class="switch">
                                <input name='wg_lpb' id='wg_lpb' type="checkbox" <?php
                                if ($wgs_controls[0] == '1') {
                                    echo "checked";
                                }
                                ?> value="1"/>
                                <span></span>
                            </label>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-8 col-xs-8 control-label">Ordenes nuevas</label>
                        <div class="col-md-4 col-xs-4">
                            <label class="switch">
                                <input name='wg_ord' id='wg_ord' type="checkbox" <?php
                                if ($wgs_controls[1] == '1') {
                                    echo "checked";
                                }
                                ?> value="1"/>
                                <span></span>
                            </label>
                        </div>
                    </div>                                    
                    <div class="form-group">
                        <label class="col-md-8 col-xs-8 control-label">Por volar</label>
                        <div class="col-md-4 col-xs-4">
                            <label class="switch">
                                <input name="wg_pvo" id="wg_pvo" type="checkbox" <?php
                                if ($wgs_controls[2] == '1') {
                                    echo "checked";
                                }
                                ?> value="1"/>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <?php if ($rol == 3) {
                        
                    } else { ?>
                        <div class="form-group">
                            <label class="col-md-8 col-xs-8 control-label">Por arreglar</label>
                            <div class="col-md-4 col-xs-4">
                                <label class="switch">
                                    <input name="wg_par" id="wg_par" type="checkbox" <?php
                                    if ($wgs_controls[3] == '1') {
                                        echo "checked";
                                    }
                                    ?> value="1"/>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-8 col-xs-8 control-label">En cuarto frio</label>
                            <div class="col-md-4 col-xs-4">
                                <label class="switch">
                                    <input name="wg_ecf" id="wg_ecf" type="checkbox" <?php
                                    if ($wgs_controls[4] == '1') {
                                        echo "checked";
                                    }
                                    ?> value="1"/>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-8 col-xs-8 control-label">Recibidas hoy</label>
                            <div class="col-md-4 col-xs-4">
                                <label class="switch">
                                    <input name="wg_reh" id="wg_reh" type="checkbox" <?php
                                    if ($wgs_controls[5] == '1') {
                                        echo "checked";
                                    }
                                    ?> value="1"/>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-8 col-xs-8 control-label">&Oacute:rdenes diarias</label>
                            <div class="col-md-4 col-xs-4">
                                <label class="switch">
                                    <input name="wg_odi" id="wg_odi" type="checkbox" <?php
                                    if ($wgs_controls[6] == '1') {
                                        echo "checked";
                                    }
                                    ?> value="1"/>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-8 col-xs-8 control-label">Por Despachar</label>
                            <div class="col-md-4 col-xs-4">
                                <label class="switch">
                                    <input name="wg_pod" id="wg_pod" type="checkbox" <?php
                                    if ($wgs_controls[7] == '1') {
                                        echo "checked";
                                    }
                                    ?> value="1"/>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-8 col-xs-8 control-label">Auditar &Oacute;rdenes</label>
                            <div class="col-md-4 col-xs-4">
                                <label class="switch">
                                    <input name="wg_auo" id="wg_auo" type="checkbox" <?php
                                    if ($wgs_controls[8] == '1') {
                                        echo "checked";
                                    }
                                    ?> value="1"/>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-8 col-xs-8 control-label">Irregulares</label>
                            <div class="col-md-4 col-xs-4">
                                <label class="switch">
                                    <input name="wg_irr" id="wg_irr" type="checkbox" <?php
                                    if ($wgs_controls[9] == '1') {
                                        echo "checked";
                                    }
                                    ?> value="1"/>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-8 col-xs-8 control-label">Compras</label>
                            <div class="col-md-4 col-xs-4">
                                <label class="switch">
                                    <input name="wg_tco" id="wg_tco" type="checkbox" <?php
                                    if ($wgs_controls[10] == '1') {
                                        echo "checked";
                                    }
                                    ?> value="1"/>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-8 col-xs-8 control-label">Compromiso de venta</label>
                            <div class="col-md-4 col-xs-4">
                                <label class="switch">
                                    <input name="wg_nol" id="wg_nol" type="checkbox" <?php
                                    if ($wgs_controls[11] == '1') {
                                        echo "checked";
                                    }
                                    ?> value="1"/>
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <?php }  ?>
                    <div class="panel-footer">
                        <button class="pull-right btn btn-info" type="submit" name="submit">
                            <span class="fa fa-save"></span> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal animated fadeIn" id="modal_change_photo" tabindex="-1" role="dialog" aria-labelledby="smallModalHead" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title" id="smallModalHead">Cambiar Foto</h4>
            </div>                    
            <form action="scripts/subiravatar.php" method="post" enctype="multipart/form-data">
                <div style="margin-top: 10px; margin-bottom: 10px;margin-left: 20px;">
                    <input  class="fileinput btn-info" type="file" name="fileToUpload" id="fileToUpload" data-filename-placement="inside" title="Buscar imagen">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Salir</button>
                    <input class="btn btn-success" type="submit" value="Cambiar foto" name="submit">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal animated fadeIn" id="modal_change_password" tabindex="-1" role="dialog" aria-labelledby="smallModalHead" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="passupdate" action="scripts/cambiarclave.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="smallModalHead">Cambiar contraseña</h4>
                </div>
                <div class="modal-body">
                    <p>Por favor introduzca los datos en los formularios a continuación para realizar el cambio de su contraseña</p>
                </div>
                <div class="modal-body form-horizontal form-group-separated">                        
                    <div class="form-group">
                        <label class="col-md-3 control-label">Contraseña anterior</label>
                        <div class="col-md-9">
                            <input name="oldpass" id="oldpass" type="password" class="form-control"/>
                            <span class="help-block">ingrese su contraseña actual</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nueva contraseña</label>
                        <div class="col-md-9">
                            <input name="newpass" id="newpass" type="password" class="form-control"/>
                            <span class="help-block">ingrese una contraseña entre 8 a 16</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Repetir contraseña</label>
                        <div class="col-md-9">
                            <input name="reppass" id="reppass" type="password" class="form-control"/>
                            <span class="help-block">repita la contraseña anterior</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Salir</button>
                    <button name="submit" type="submit" class="btn btn-success" ><span class="fa fa-save"></span> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div> 