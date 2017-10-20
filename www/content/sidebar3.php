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
        <li class="xn-openable"><a href="#"><span class="fa fa-credit-card"></span><span class="xn-text"> Venta</span></a>
            <ul>
                <li><a href="main.php?panel=cot.php">Cargar</a></li>
            </ul>
        </li>   
        <li class="xn-openable"><a href="#"><span class="fa fa-list"></span> <span class="xn-text">Órdenes</span></a>
            <ul>
                <li><a href="main.php?panel=verorden.php"> Ver órdenes</a></li>
                <li><a href="/php/asignartrackings.php"> Asignar Trackings</a></li>
                <li><a href="main.php?panel=verorden.php&ponumber=true"> Buscar Po</a></li>
            </ul>
        </li> 
        <li class="xn-openable"><a href="#"><span class="fa fa-plus-square"></span><span class="xn-text"> Registro</span></a>
            <ul>
                <li><a href="/php/crearProductos.php"> Registro de productos</a></li>
            </ul>
        </li>
    </ul>
</div>