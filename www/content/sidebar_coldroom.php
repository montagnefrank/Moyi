<div class="page-sidebar page-sidebar-fixed scroll mCustomScrollbar _mCS_1 mCS-autoHide">
    <ul class="x-navigation">
        <li class="xn-logo">
            <a href="main.php?panel=mainpanel.php">BurtonTech</a>
            <a href="#" class="x-navigation-control"></a>
        </li>
        <li class="xn-profile">
            <a href="#" class="profile-mini">
                <img src="<?php
                $isavatar = "img/users/" . $user . ".jpg";
                if (file_exists($isavatar)) {
                    echo "img/users/" . $user;
                } else {
                    echo "img/users/default";
                }
                ?>.jpg" alt="<?php echo $user ?>"/>
            </a>
            <div class="profile">
                <div class="profile-image">
                    <img src="<?php
                    $isavatar = "img/users/" . $user . ".jpg";
                    if (file_exists($isavatar)) {
                        echo "img/users/" . $user;
                    } else {
                        echo "img/users/default";
                    }
                    ?>.jpg" alt="<?php echo $user ?>"/>
                </div>
                <div class="profile-data">
                    <div class="profile-data-name">
                        <?php
                        $result_profile_info = mysqli_query($link, "SELECT cpnombre FROM tblusuario WHERE cpuser = '" . $user . "'");
                        $row_profile_info = mysqli_fetch_array($result_profile_info, MYSQLI_ASSOC);
                        echo $row_profile_info['cpnombre'];
                        ?>
                        <div class="profile-data-title">
                            <span class="fa fa-user"></span> 
                            <?php echo $user ?></div>
                    </div>
                    <div class="profile-data-title">
                        <span class="fa fa-industry"></span> 
                        <?php
                        echo $finca;
                        ?>
                    </div>
                </div>
                <div class="profile-controls">
<!--                    <a href="pages-profile.html" class="profile-control-left"><span class="fa fa-info"></span></a>
                    <a href="pages-messages.html" class="profile-control-right"><span class="fa fa-envelope"></span></a>-->
                </div>
            </div>                                                                        
        </li>
        <li class="xn-title">Menú principal</li>
        <li class="xn-openable"><a href="#"> Movimientos</a>
            <ul>
                <li><a href="/php/reg_entrada.php"> Registro cajas</a></li>
                <li><a href="/php/verificartrack.php"> Chequear tracking</a></li>
            </ul>
        </li> 
        <li><a href="/php/asig_guia.php"> Asignar guía</a></li>
        <li><a href="/php/reutilizar.php"> Reutilizar cajas</a></li>
        <li><a href="/php/etiqxfincas.php"> Etiquetas</a>
        </li> 
        <li><a href="/php/crearProductos.php"> Registro de productos</a></li>
        <li class="xn-openable"><a href="#"> Reportes</a>
            <ul>
                <li class="xn-openable"><a href="#"> Cajas recibidas</a>
                    <ul>
                        <li><a href="/php/reportecold.php?id=1"> Por productos</a></li>
                        <li><a href="/php/reportecold.php?id=2"> Por fincas</a></li>
                        <li><a href="/php/reportecold.php?id=3"> Por código</a></li>
                        <li><a href="/php/reportecold.php?id=4"> Total</a></li>
                    </ul>
                </li>
                <li class="xn-openable"><a href="#"> Cajas traqueadas</a>
                    <ul>
                        <li><a href="/php/reportecold1.php?id=1"> Por producto</a></li>
                        <li><a href="/php/reportecold1.php?id=2"> Por finca</a></li>
                        <li><a href="/php/reportecold1.php?id=3"> Por código</a></li>
                        <li><a href="/php/reportecold1.php?id=4"> Total</a></li>
                    </ul>
                </li>
                <li class="xn-openable"><a href="#"> Cajas rechazadas</a>
                    <ul>
                        <li><a href="/php/reportecold2.php?id=1"> Por producto</a></li>
                        <li><a href="/php/reportecold2.php?id=2"> Por finca</a></li>
                        <li><a href="/php/reportecold2.php?id=3"> Por código</a></li>
                    </ul>
                </li> 
                <li><a href="/php/voladasxfinca.php"> Cajas voladas</a></li>
                <li><a href="/php/novoladasxfinca.php"> Cajas no voladas</a></li>
                <li><a href="/php/guiasasig.php"> Guías asignadas</a></li>
                <li><a href="/php/reporte_palets.php"> Guías master trackeadas</a></li>
            </ul>
        </li>
        <li><a href="/php/modificar_guia.php"> Editar guias</a></li>
        <li><a href="/php/closeday.php"> Cerrar día</a></li>
    </ul>
</div>