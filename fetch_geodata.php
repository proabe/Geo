<?php


if(count($_POST) > 0){

$url = htmlentities($_POST['URL']);
$html = @file_get_contents($url);
$body = new DOMDocument();
$api=htmlentities($_POST['API']);

libxml_use_internal_errors(TRUE);
if (!empty($html)) {
    $body->loadHTML($html);
    libxml_clear_errors();
    $body_xpath = new DOMXPath($body);
    $lat="";
    $lng="";
 $geo=substr($html, strpos($html, "\",["));
      
    $geo=strstr($geo, "]",true);
    $geo=str_replace("\",[", "", $geo);
    $geo=explode(",", $geo);
    if(count($geo)>2){
     $lat=$geo[2];
     $lng=$geo[3];
    }
    else{
    $lat=$geo[0];
    $lng=$geo[1]; 
    }   
     

    $html= @file_get_contents("https://apis.mapmyindia.com/advancedmaps/v1/".trim($api)."/rev_geocode?lng=".trim($lng)."&lat=".trim($lat));
    
    $json=json_decode($html, true);
    $addr=$json['results'][0]['formatted_address'];
    $latitude=$json['results'][0]['lat'];
    $longitude=$json['results'][0]['lng'];
    
    }
   
   
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <title>Fetch Geo</title>
    <link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="main.css">
    <script src="https://apis.mapmyindia.com/advancedmaps/v1/kec73vaab5y1umbc7qy7p3f3jhjogca1/map_load?v=01."></script>
    <script type="text/javascript"> document.addEventListener("DOMContentLoaded",at);
    function at(){
    document.getElementById("btn").addEventListener("click",callme);
    function callme(e)
    {
      e.preventDefault();
       var map=new MapmyIndia.Map("map",{ center:[<?php echo $latitude; ?>,<?php echo $longitude; ?>],zoomControl: true,hybrid:true });L.marker([<?php echo $latitude; ?>,<?php echo $longitude; ?>]).addTo(map);
     }
     
    }</script>

    
    
    
</head>

<body>
    <div id ="fm">
    <form  action="" method="post">
       <p>
           Enter the URL:<input type="text" name="URL" placeholder="URL without double quotes">
       </p>
       <p>
           Enter the API key:<input type="text" name="API" placeholder=" MapmyIndia API key">
       </p>
       <p>
           <input id="btn" type="submit" name="bt" value="Get Data">
       </p>
    </form>
    </div>
    <div id="geo">
            <p class="g1">
               <?php   if(strlen(trim($api))===0){
                        echo "<h3>Latitude:  {$lat} </h3>" ;
                       }
                       else{ 
                       echo "<h3>Latitude:  {$latitude} </h3>" ;
                       } ?>
            </p>
            <p class="g1">
                <?php   if(strlen(trim($api))===0){
                        echo "<h3>Latitude:  {$lng} </h3>" ;
                       }
                       else{ 
                       echo "<h3>Latitude:  {$longitude} </h3>" ;
                       } ?> 
            </p>
            <p class="g1">
                <?php echo "<h3>Address: {$addr} </h3>" ?> 
            </p>
        </div>
        <div id="wrap">
        <div id="map"></div>
        </div>
        <div id="gmap">
        <img src=<?php echo "https://maps.googleapis.com/maps/api/staticmap?center=".trim($lat).",".trim($lng)."&zoom=10&size=400x300&markers=".trim($lat).",".trim($lng)."&path=color:0x00FF80|weight:5|".trim($lat).",".trim($lng)."&size=175x175&sensor=FALSE"?>>
        </div>
</body>
</html>
