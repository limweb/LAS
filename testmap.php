<?php 
$sql = 'select .....';


$lat =51.508742;
$lan = -0.120850;
?>
<!DOCTYPE html>
    <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title></title>
            <link rel="stylesheet" href="">
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    </head>
    <body>
      <div id="googleMap" style="width:500px;height:380px;"></div>
    </body>
    <script>
     var myCenter=new google.maps.LatLng(<?=$lat?>,<?=$lan?>);

     function initialize()
      {
       var mapProp = {
       center:myCenter,
       zoom:5,
       mapTypeId:google.maps.MapTypeId.ROADMAP
      };

      var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);

       var marker=new google.maps.Marker({
       position:myCenter,
        });

       marker.setMap(map);

         var infowindow = new google.maps.InfoWindow({
        content:"Hello World! abd"
         });

        infowindow.open(map,marker);
       }

      google.maps.event.addDomListener(window, 'load', initialize);
      </script>
</html>