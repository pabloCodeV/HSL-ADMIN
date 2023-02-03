<nav id="menu">
        <div id="logoSL">
            <img src="css/background/logo-black.svg"/>
            <h2>Portal Administrativo</h2>
        </div>
        <?php
        if($_COOKIE["rules"] == 'admin'){
        ?>
    <ul>
        <li class="has-sub"><a title="" href="novedades.php">Novedades</a></li>
        <li class="has-sub"><a title="" href="banners.php">Banners (Secciones)</a>
        <li class="has-sub"><a title="" href="sliders.php">Sliders</a>
        <li class="has-sub"><a title="" href="servicios-medicos.php">Servicios Medicos</a></li>
        <li class="has-sub"><a title="" href="coverturas.php">Coverturas</a></li>
        <li class="has-sub"><a title="" href="telemedicina.php">Especialidades de Telemedicina</a></li>
        <li class="has-sub"><a title="" href="autoridades.php">Autoridades</a></li>
        <li class="has-sub"><a title="" href="especialidadesExterno.php">Especialidades de Consultorios Externos</a></li>
        <li class="has-sub"><a title="" href="eventos.php">Eventos</a></li>
    </ul>
    <h3 class="tituloadministrador">Hemodinamia</h3>
    <ul>
        <li class="has-sub"><a title="" href="preintervencionismo.php">Formulario de Pre Intervencion</a>
    </ul>
    <h3 class="tituloenfermeria">Enfermeria</h3>
    <ul>
        <li class="has-sub"><a title="" href="inscripciones.php">Inscripciones</a>
    </ul>
    <?php
    }
    if($_COOKIE["rules"] == 'hemodinamia'){ ?>
        <ul>
            <li class="has-sub"><a title="" href="preintervencionismo.php">Formulario de Pre Intervencion</a>
        </ul>
    <?php } ?>

</nav>