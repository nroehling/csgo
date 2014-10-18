<?php
session_start();

include 'steamapi.php';
include 'db_connect.php';

$url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$steamapi&steamids=$steamid";
$json = file_get_contents($url);
$json_decoded_comment = json_decode($json);
$name = $json_decoded_comment->response->players[0]->personaname;
$comment = $_POST['data'];
$image = $json_decoded_comment->response->players[0]->avatar;
$pageid = $_POST['ids'];
if ($_POST['ids'] == null) {
    $pageid = $steamid;
}


//query
$query = "INSERT INTO comments (comment, steamid, pageid) VALUES ('$comment','$steamid','$pageid') ";
mysqli_query($mysqli, $query);

?>

<li class="box">
    <img style="border:1px solid rgb(63,149,218);" src="
    <?php
    echo $image;
    ?>"/>
    <a href="http://www.my-csgo.de/profil.php?id=<?php echo $steamid; ?>"><?php echo $name; ?></a> wrote: <br />
    <?php echo $comment; ?>
</li>