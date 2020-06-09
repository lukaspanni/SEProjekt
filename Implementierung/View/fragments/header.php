<?php
	$pages = Config::$_PAGES;
	if($this->page == null){
	    $this->page = -1;
    }
?>
<header>
    <div class="navbar-fixed">
        <nav>
            <div class="nav-wrapper">
                <a href="#" data-target="mobile-nav" class="sidenav-trigger"><i class="material-icons">menu</i></a>
				<?php if (isset($_SESSION["login"]) && $_SESSION['login'] == true) { ?>
                        <a class="login right" href="/authentication/logout">Logout</a>
                    <?php } else{ ?>
                    <a class="login right <?php if ($this->page == $pages["LOGIN"]) echo 'active'; ?>" href="/authentication/" >Login</a>
                    <?php } ?>
                <ul class="left hide-on-med-and-down">
                    <li <?php if ($this->page == $pages["USER"]) echo 'class="active"'; ?>><a href="/user/">User</a>
                    </li>
                    <li <?php if ($this->page == $pages["PROJECT"]) echo 'class="active"'; ?>><a href="/project/">Project</a>
                    </li>
                    <li <?php if ($this->page == $pages["TIME"]) echo 'class="active"'; ?>><a href="/time/">Time</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- Mobile Nav -->
    <ul class="sidenav" id="mobile-nav">
        <li><a href="/user/">User</a></li>
        <li><a href="/project/">Project</a></li>
        <li><a href="/time/">Time</a></li>
        <?php if (isset($_SESSION["login"]) && $_SESSION['login'] == true) { ?>
            <li><a href="/authentication/logout">Logout</a></li>
        <?php } else{ ?>
            <li><a href="/authentication/">Login</a>
            </li>
        <?php } ?>
    </ul>
</header>
