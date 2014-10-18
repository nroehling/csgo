<?php
    session_start();
    include 'openid.php';
try 
{
    $openid = new LightOpenID('http://www.my-csgo.de');
    if(!$openid->mode) 
    {
        if(isset($_GET['login'])) 
        {
            $openid->identity = 'http://steamcommunity.com/openid';    // This is forcing english because it has a weird habit of selecting a random language otherwise
            header('Location: ' . $openid->authUrl());
        }
?>
<?php
    } 
    else if($openid->mode == 'cancel') 
    {
        echo 'User has canceled authentication!';
    } 
    else 
    {
        if($openid->validate()) 
        {       
                $_SESSION['identity'] = $openid->identity;
                $_SESSION['steamuserid'] = str_replace("http://steamcommunity.com/openid/id/","",$_SESSION['identity']);
                
//                $steamid = $_SESSION['steamuserid'];
//
//                $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$_STEAMAPI&steamids=$steamid";
//                $json_object= file_get_contents($url);
//                $json_decoded = json_decode($json_object);
//                
//                foreach ($json_decoded->response->players as $player)
//                {
//                    echo "
//                    <br/>Player ID: $player->steamid
//                    <br/>Player Name: $player->personaname
//                    <br/>Profile URL: $player->profileurl
//                    <br/>SmallAvatar: <img src='$player->avatar'/> 
//                    <br/>MediumAvatar: <img src='$player->avatarmedium'/> 
//                    <br/>LargeAvatar: <img src='$player->avatarfull'/> 
//                    <br/>personastate : $player->personastate
//                    ";
//                    
//                    
//                }
                
                header("Location: http://www.my-csgo.de");
        } 
        else 
        {
                echo "User is not logged in.\n";
        }
    }
} 
catch(ErrorException $e) 
{
    echo $e->getMessage();
}
