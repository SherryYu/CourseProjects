<!-- Header -->
<header id="main-header">
    <div class="container">
        <div class="main-head">
            <div class="left-side">
                <div class="logo"><a href="index.php"><img src="assets/images/logo.png" alt=""></a></div>
            </div>
            <div class="right-side">
                <div class="cs-search-block">
                    <form method="POST" action="result.php">
                        <label>
                            <input type="submit" value="Search">
                        </label>
                        <input type="text" id="s" name="s" pattern="\w{3,20}" title="letters only" value="Search Project" onfocus="if(this.value =='Search Project') { this.value = ''; }" onblur="if(this.value == '') { this.value ='Search Project'; }" class="form-control">
                    </form>
                </div>
                <?php
                if (isset($_SESSION["username"])) {
                    echo "<div class=\"profile-view\">
          <ul>
            <li>
              <img alt=\"#\" src=\"assets/extra-images/user-img.jpg\">
              <i class=\"icon-arrow-down8\"></i>
             
              <div class=\"dropdown-area\">
                <h5>My stuff</h5>
                <ul class=\"dropdown\">
                    <li><a href=\"myprojects.php\">My projects</a></li>
                    <li><a href=\"pledgedprojects.php\">Pledged projects</a></li>
                    <li><a href=\"likedprojects.php\">Liked projects</a></li>
                    <li><a href=\"followeduser.php\">Followed Users</a></li>
                    <li><a href=\"index.php\">Recommended for you</a></li>
                    <li><a href=\"userprofile.php?uid=".$_SESSION["email"]. "\">My Profile</a></li>
                
                    
                </ul>
                <h5>Settings</h5>
                <ul class=\"dropdown\">
                    <li><a href=\"addcard.php\">Edit Payment Method</a></li>
                    <li><a href=\"profilesetting.php\">Edit profile</a></li>
                </ul>
                <div class=\"user-menu-footer\">
                  You're logged in as <b>";
                    echo $_SESSION["username"];
                    echo "</b>&nbsp<a href=\"logout.php\">Log out</a>
                </div></div></div>
                <a href=\"createproject.php\" class=\"logina\">Start New Project</a>";
                }
                else
                    echo "<a href=\"login.php?#signin\" class=\"logina\">Log in</a>
            <a href=\"login.php?#signup\" class=\"logina\">Sign up</a>";
                ?>

            </div>
        </div>
        <div class="mob-nav"></div>
    </div>
</header>
<!-- Header -->
