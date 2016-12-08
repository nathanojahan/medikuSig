<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Mediku</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mediku2.css" rel="stylesheet">
    <style>
      /* Always set the map height explicitly to define the size of the div
      * element that contains the map. */
      #map {
        height: 100%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
</style>


</head>
<body>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

    <div class="container">
        <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">

                    <a class="navbar-brand" href="#">Mediku</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </div>
    <div id="map"></div>
    <div id="capture"></div>
    <input type="hidden" name="address" id="address" value="<?php echo $_POST["address"]; ?>">
    <script>
        var map;
        var geocoder;
        var infoWindow;
        var kml = '../medikuSig/Mediku.kml'; 

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -6.21462, lng: 106.84513},
                zoom: 16
            });
            geocoder = new google.maps.Geocoder();
            geocodeAddress(geocoder, map);
            infowindow = new google.maps.InfoWindow();
            loadKmlLayer(kml, map);
        }

        function geocodeAddress(geocoder, resultsMap) {
            var address = document.getElementById('address').value;
            geocoder.geocode({'address': address}, function(results, status) {
              if (status === 'OK') {
                resultsMap.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                  map: resultsMap,
                  position: results[0].geometry.location
              });
                google.maps.event.addListener(marker, 'click', function() {
                  infowindow.setContent("your location");
                  infowindow.open(map, this);
              });
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });
        }

        function loadKmlLayer(src, map) {
            var kmlLayer = new google.maps.KmlLayer(src, {
              suppressInfoWindows: true,
              preserveViewport: false,
              map: map
          });
            google.maps.event.addListener(kmlLayer, 'click', function(event) {
              var content = event.featureData.infoWindowHtml;
              var testimonial = document.getElementById('capture');
              testimonial.innerHTML = content;
          });
        }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgDkjgoAZ7GbKexSSnDtIkBQUrLB6HHXw&callback=initMap"
    async defer></script>
</body>

</html>