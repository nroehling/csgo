<?php
session_start();

include 'db_connect.php';
include 'steamapi.php';

$userid = $_POST['ids'];

if ($userid == null) {
    $userid = $steamid;
}

$query = "SELECT steamid, comment, pageid FROM comments WHERE pageid='$userid'";
$result = mysqli_query($mysqli, $query);
while ($row = mysqli_fetch_array($result)) {

    $comment = $row['comment'];
    $steamid = $row['steamid'];
    $data = convertSteamid($steamid);

    echo '<li class="box" >
    <img style="border:1px solid rgb(63,149,218);" src="' .
    $data['imageurl'] . '"/>
    ' . $data['name'] . ' wrote: <br />
    ' . $comment . '
</li>';
}