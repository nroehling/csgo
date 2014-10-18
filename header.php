<?php session_start();$userid = $_GET['id'];?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <?php
        include_once 'includes/auth.php';
        include_once 'includes/openid.php';
        include 'includes/functions.php';
        include_once 'includes/steamapi.php'
        ?>
        <title>CS:GO</title>

        <link rel="stylesheet" href="styles/main.css" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/jquery-ui.js"></script> 
        <script src="js/main.js"></script>
        <script type="text/javascript" >
            $(document).ready(function() {
                
                $('.nav li').mouseover(function() {
                    $(this).find('div').stop(true, true).delay(100).show("clip", {direction: "horizontal"});
                    $(this).children('ul').stop(true, true).delay(100).slideDown();
                });

                $('.nav li').mouseleave(function() {
                    $(this).find('div').stop(true, true).hide();
                });

                $('#wrapper').mouseleave(function() {
                    $(this).find('div').stop(true, true).hide();
                    $('.dropdown').slideUp();
                });

                $('#wrapper1').mouseleave(function() {
                    $(this).find('div').stop(true, true).hide();
                    $('.dropdown').slideUp();
                });
            });//document.ready
        </script>
    </head>
    <body>
        <div id="page">
            <div id="header">

                <div id="nav-div">


                    <ul class="nav">
                        <div id="wrapper">

                            <li><a href="profil.php" class="profil"><span><img src="styles/img/buddy_icon.png" width="20px" height="20px" style="position:relative;margin-right: 8px;"/></span>Profil<div id="strich"></div></a>
                                <ul class="dropdown">
                                    <li><a href="">Inventar</a></li>
                                    <li><a href="">Statistiken</a></li>
                                    <li><a href="">My Bets</a></li>
                                    <li><a href="">My Trades</a></li>
                                    <li><a href="">Wunschliste</a></li>
                                </ul>
                        </div>
                        </li>
                        <li ><a href="matches.php"><span><img src="styles/img/bet_icon.png" width="15px" height="20px" style="position:relative;margin-right:8px;"/></span>Bets&#160;&&#160;Matches<div id="strich"></div></a></li>
                        <div id="wrapper1">
                            <li><a href="#tab3" class="marktplatz"><span><img src="styles/img/trade_icon.png" width="20px" height="20px" style="position:relative;margin-right: 8px;"/></span>Marktplatz<div id="strich"></div></a>
                                <ul class="dropdown">
                                    <li><a href="">Statistiken</a></li>
                                    <li><a href="">Anfragen</a></li>
                                    <li><a href="">Rechner</a></li>
                                </ul>
                        </div>
                        </li>
                        <li><a href="items.php" ><span><img src="styles/img/wc_icon.png" width="25px" height="19px" style="position:relative;margin-right: 8px;"/></span>Items<div id="strich"></div></a></li>
                    </ul>
                </div>
                <?php if (!isset($_SESSION['identity'])) : ?>
                    <form action = "includes/auth.php?login" method = "post" id = "signin">
                        <input type = "image" src = "http://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_large_noborder.png" />
                    </form>
                <?php else : ?>
                <form action = "logout.php" method = "post" id = "signin">
                    <input type = "image" src = "./styles/img/logout.png" />
                    </form>
                <?php endif; ?>
                </div>
            