function GetURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}


$(document).ready(function() {


    $(".matchHead").click(function() {

        var panel = $(this).next();

        $(panel).slideToggle("slow");

        var isVisible = $(panel).hasClass(".opened");

        if (isVisible == true) {
            $(panel).html("<p></p>");
        } else {

            var id = $(this).attr("id");
            id = parseInt(id);

            $(panel).html('<div style="width:100%;"><img id="laden" style="margin:auto;" src="js/loading/match-loader.gif" width="50px" height="50px"/></div>');

            $.ajax({
                type: "POST",
                url: "includes/getEventInfo.php",
                data: {},
                dataType: "json",
                error: function(msg) {
                    alert("error");
                },
                success: function(data) {
                    var coverage = data[id][0];
                    var names = data[id][1];
                    var str1 = data[id][2];
                    var str2 = data[id][3];
                    var name1 = str1.match(/\'[^\']+\'/g);
                    var name2 = str2.match(/\'[^\']+\'/g);
                    $(panel).html('<div class="team_names"><div class="team1"></div><div class="team2"></div></div>');
                    $(panel).append('<div clasS="match_main"></div>');
                    $(panel).append('<p>' + coverage + '</p>');
                    $(panel).append('<p>' + names + '</p>');
                    $(panel).append('<p>' + name1[0] + " " + name1[1] + " " + name1[2] + " " + name1[3] + " " + name1[4] + '</p>');
                    $(panel).append('<p>' + name2[0] + " " + name2[1] + " " + name2[2] + " " + name2[3] + " " + name2[4] + '</p>');
                }
            });
        }
        $(panel).toggleClass(".opened");
    });

    $("#update").fadeIn("slow");
    $(".submit").click(function()
    {
        var comment = $("#comment").val();
        var dataString = comment;
        var pageid = GetURLParameter('id');
        if (comment == '')
        {
            alert('Das Textfeld ist leer!');
        }
        else
        {
            $("#flash").show();
            $("#flash").fadeIn(400).html('<img src="./styles/img/ajax-loader_comment.gif" />Loading Comment...');
            $.ajax({
                type: "POST",
                url: "includes/commentajax.php",
                data: {ids: pageid, data: dataString},
                error: function(msg) {
                    alert("error");
                },
                success: function(html) {
                    $("#update").append(html);
                    $("#update").fadeIn("slow");
                    $("#flash").hide();
                }
            });
        }
        return false;
    });

    $("#logout").click(function() {

    });

    $(".marketitem").mouseover(function(ev) {
        var mouseX = ev.pageX;
        var mouseY = ev.pageY;

        var market = "http://steamcommunity.com/market/listings/730/";
        var alt = $(this).attr("alt");
        var pos = alt.split("/");

        $(".markethover").html('<p style="color:white;">' + alt + '</p><br /><a style="color:white;text-align:center;text-decoration:none;" href=' + market + pos[0] + '>Steam Marktplatz</a>');



        $(".markethover").css({
            top: mouseY + 5,
            left: mouseX + 5
        }).show();

    });

    $("#stay").mouseleave(function(ev) {
        $(".hoverbox").hide();
    });

    $(".weaponinv").mouseover(function(ev) {
        var mouseX = ev.pageX;
        var mouseY = ev.pageY;

        var market = "http://steamcommunity.com/market/listings/730/";
        var alt = $(this).attr("alt");
        var pos = alt.split("/");
        //Array pos => pos[0] = name , pos[1] = color , pos[2] = lowestprice

        $(".hoverbox").html('<p style="color:#' + pos[1] + '">' + pos[0] + '</p></br><img style="padding-left:43%;" src="js/loading/ajax-loader.gif" width="18px" height="15px"/></br><a id="market" style="color:white;text-align:center;text-decoration:none;" href="' + market + pos[0] + '" )">Steam Marktplatz</a>');

        $(".hoverbox").css({
            top: mouseY + 5,
            left: mouseX + 5
        }).show();

        var name = pos[0];

        $.ajax({
            type: "POST",
            url: "includes/getPrice.php",
            data: {itemname: name},
            dataType: "json",
            error: function(msg) {
                alert("error");
            },
            success: function(data) {
                var low = data['low'];
                var median = data['median'];
                var volume = data ['volume'];

                if (low == null) {

                    low, median, volume = 0;

                }

                $(".hoverbox").find("img").remove();
                $(".hoverbox").html('<p style="color:#' + pos[1] + '">' + pos[0] + '</p><p>Ab: ' + low + ' | Median: ' + median + '</p><p>Anzahl: ' + volume + ' St&uuml;ck</p><a style="color:white;text-align:center;text-decoration:none;margin:auto;" href="' + market + pos[0] + '">Steam Marktplatz</a>');
            }
        });
    });



    $("#stay").mouseleave(function(ev) {
        $(".hoverbox").hide();
    });

});//doc ready.