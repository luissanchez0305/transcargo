<?php
session_start();
?>

<div class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner">
<div class="container container-menu">
<a class="btn btn-navbar" data-target=".nav-collapse" data-toggle="collapse">
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</a>
<a class="brand" href="/?list"><img alt="transcargo" longdesc="transcargo" src="img/logo.png" style="width: 251px; height: 35px" /></a>
<div class="nav-collapse collapse">
<ul class="nav nav-pills">

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?= $_SESSION["myusername"]?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Opcion 1</a></li>
                        <li><a href="#">Opcion 2</a></li>
                        <li><a href="#">Opcion 3</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Administracion<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Opcion 1</a></li>
                        <li><a href="#">Opcion 2</a></li>
                        <li><a href="#">Opcion 3</a></li>                    </ul>
                </li>
                <li><a href="/logout.php">Salir</a></li>
            </ul></div>
</div>
</div>
</div>
