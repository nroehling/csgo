
<?php
session_start();
if (isset($_SESSION['identity'])) :



    include ("header.php");
    ?>
    <div id="main">
        <div id="content">
            <div id="profil">
                <?php
                getPlayerSum($steamapi, $steamid, $userid);
                getPlayerEntries($steamid,$mysqli,$userid);
                ?>
            </div>
            <div id="info">
                <div class="hoverbox" id="stay">

                </div>
                <?php getInventory($steamapi, $steamid, $userid) ?>
            </div>
            <div id="friends">
                <span style="border-bottom: 1px solid rgb(210,210,210);">Kommentare / Handelsangebote</span>
                <br/>
                <ol id="update" class="timeline">
                    <?php                                    getComments($userid, $mysqli, $steamid);?>
                </ol>
                <div id="load"></div>
                <div id="flash"></div>
                <div >
                    <form action="#" method="post">
                        <textarea id="comment" name="comment"></textarea><br />
                        <input type="submit" class="submit" value=" Submit Comment " />
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include ("footer.php"); ?>

<?php else : ?>
    <h1>bitte logge dich zuerst ein.</form>
    <form action = "includes/auth.php?login" method = "post" id = "signin">
        <input type = "image" src = "http://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_large_noborder.png" />
    </form></h1>
<?php endif; ?>
