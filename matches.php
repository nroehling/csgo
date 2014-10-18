<?php
session_start();
include ("header.php");
?>
<div id="main">
    <div id="content">
        <h1 id="live" style="margin:30px;color:rgb(140,140,140);display:none;">Live Matches</h1>
        <div class="liveMatches"></div>
        <h1 style="margin:30px;color:rgb(140,140,140);">Anstehende Matches</h1>
        <div class="matches" style="list-style-type: none;"></div>
        <h1 style="margin:30px;color:rgb(140,140,140);">Gespielte Matches</h1>
        <div class="playedMatches"></div>
        <script type="text/javascript" charset="UTF-8">
<?php $jo = getWebContent();
?>
            var arr = <?php echo json_encode($jo); ?>;
            var regex = "[0-2][0-9]:[0-9][0-9]h";
            var regexScore = "[0-9][0-9]:[0-9][0-9]";
            var str;
            var split;
            var time;
            var date, team1, team2;
            var pos;
            var color;
            var status;
            for (var k = 0; k < arr.length; k++) {
                str = arr[k].toString();
                split = str.split(" ");
                date = split[0];
                str = split[1];
                team1 = str.slice(0, -9);
                team2 = split[2];
                if (str.match(regex) != null) {
                    time = str.match(regex);
                    time = time[0];
                    color = "rgb(63,149,218)";
                    status = ".matches";
//                    date = date.split(".");
//                    var day = date[0];
//                    var month = date[1];
//                    month = month + 1;
//                    var year = date[2];
//                    
//                    time = time.split(":");
//                    var hour = time[0];
//                    var min = time[1];
//                    min = min.substr(0, min.length-1);
//                    
//                    var matchDatum  = new Date(year, month, day, hour, min, 0 ,0);
//                    var datumHeute = new Date();

                } else if (str.indexOf("defwin") !== -1) {
                    pos = str.indexOf("defwin");
                    time = str.substr(pos, 6);
                    color = "rgb(140,140,140)";
                    status = ".playedMatches";

                } else if (str.indexOf("LIVE") !== -1) {
                    pos = str.indexOf("LIVE");
                    time = str.slice(pos);
                    color = "rgb(18,232,39)";
                    status = ".liveMatches";

                } else if (str.indexOf("paused") !== -1) {
                    pos = str.indexOf("paused");
                    time = str.substr(pos, 6);
                    color = "rgb(140,140,140)";
                    status = ".playedMatches";
                } else {
                    time = str.match(regexScore);
                    time = time[0];
                    color = "rgb(140,140,140)";
                    status = ".playedMatches";

                }
                
                

                $(status).append('<div class="matchHead" id="' + k + '" style="border-color:' + color + ';">' + ' <span style="margin-right:50px;"> ' + date + '</span>' + " " + ' <span> ' + time + '</span>' + " " + '<div style="text-align:center;font-size: 24px;">' + team1 + ' vs ' + team2 + '</div></div>');
                $(status).append('<div class="panel"></div>');
            }
            if ($(".liveMatches").children().length > 0) {
                $("#live").show();
                $(".liveMatches").show();
            }
        </script>
    </div>
</div>
<?php include ("footer.php"); ?>