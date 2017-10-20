<!-- BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="main.php?panel=mainpanel.php">BurtonTech</a></li>
    <li class="active">Inicio</li>
</ul>
<!-- BREADCRUMB --> 
<div class="page-content-wrap">   
    <div class="row">
        <?php
        $result_wgs = mysqli_query($link, "SELECT wg_lpb,wg_ord,wg_pvo,wg_par,wg_ecf,wg_reh,wg_odi,wg_pod,wg_auo,wg_irr,wg_tco,wg_nol FROM tblusuario WHERE cpuser = '" . $user . "'");
        $row_wgs = mysqli_fetch_array($result_wgs, MYSQLI_BOTH);
        if ($row_wgs[0] == '1') {
            require ("content/widgets/wg_lpb.php");
        }
        if ($row_wgs[1] == '1') {
            require ("content/widgets/wg_ord.php");
        }
        if ($row_wgs[2] == '1') {
            require ("content/widgets/wg_pvo.php");
        }
        if ($row_wgs[3] == '1') {
            require ("content/widgets/wg_par.php");
        }
        if ($row_wgs[4] == '1') {
            require ("content/widgets/wg_ecf.php");
        }
        if ($row_wgs[5] == '1') {
            require ("content/widgets/wg_reh.php");
        }
        if ($row_wgs[6] == '1') {
            require ("content/widgets/wg_odi.php");
        }
        if ($row_wgs[7] == '1') {
            require ("content/widgets/wg_pod.php");
        }
        if ($row_wgs[8] == '1') {
            require ("content/widgets/wg_auo.php");
        }
        if ($row_wgs[9] == '1') {
            require ("content/widgets/wg_irr.php");
        }
        if ($row_wgs[10] == '1') {
            require ("content/widgets/wg_tco.php");
        }
        if ($row_wgs[11] == '1') {
            require ("content/widgets/wg_nol.php");
        }
        ?>
    </div>
</div>