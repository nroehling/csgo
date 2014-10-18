
<?php
session_start();
include ("header.php");
?>
<div id="main">
    <div id="content">
        <div class="markethover" id="stay">

        </div>
<?php getAllMarketItems() ?>;
    </div>
</div>
<?php include ("footer.php"); ?>
