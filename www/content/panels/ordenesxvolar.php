<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">BurtonTech</a></li>
    <li class="active">Inicio</li>
</ul>
<!-- BREADCRUMB --> 
<div class="page-content-wrap">   
    <div class="row">
        <?php
        $result_wgs = mysql_query("SELECT wg_lpb,wg_ord,wg_pvo,wg_par,wg_ecf,wg_reh FROM tblusuario WHERE cpuser ='" . $user . "'");
        $wgs = mysql_fetch_array($result_wgs);
        if ($wgs[0] == '1') {
            require ("content/widgets/wg_ord.php");
        }
        if ($wgs[1] == '1') {
            require ("content/widgets/wg_pvo.php");
        }
        if ($wgs[2] == '1') {
            require ("content/widgets/wg_par.php");
        }
        if ($wgs[3] == '1') {
            require ("content/widgets/wg_ecf.php");
        }
        if ($wgs[4] == '1') {
            require ("content/widgets/wg_reh.php");
        }
        if ($wgs[5] == '1') {
            require ("content/widgets/wg_reh.php");
        }
        ?>
    </div>
</div>