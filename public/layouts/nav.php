<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Vaccination Appointment</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <?php if (!isset($_SESSION['user'])) { ?>
                    <li><a href="#Register" data-toggle="modal" data-target="#register">Register</a></li>
                    <li><a href="#Login" data-toggle="modal" data-target="#login">Login</a></li>
                <?php }else{ ?>
                    <li><a href="#Profile" data-toggle="modal" data-target="#profile" id="pop_profile">Profile</a></li>
                    <li><a href="admin/Logout.php" >Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>