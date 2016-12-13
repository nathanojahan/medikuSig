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
       #map {
        height: 100%;
    }
    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    .controls {
        margin-top: 60px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
    }

    #pac-input:focus {
        border-color: #4d90fe;
    }

    .pac-container {
        font-family: Roboto;
    }

    #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
    }

    #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
    }
    #target {
        width: 345px;
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
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Mediku</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                </div>
            </div>
        </nav>
    </div>
    <div id="search-box">
        <input id="pac-input" class="controls" type="text" placeholder="Search Box">
    </div>
    <div id="map"></div>
    <script>
        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: -6.4025, lng: 106.7942},
              zoom: 13,
              mapTypeId: 'roadmap'
          });

            $.getJSON("data.json", function(json) {
                var marker;
                var i = 0;
                for (var key in json) {
                  if (json.hasOwnProperty(key)) {
                    console.log(json[key]);
                    var marker = new google.maps.Marker({
                      map: map,
                      position: new google.maps.LatLng(json[key].geometry.location.lat, json[key].geometry.location.lng)
                  });
                    var content = "Name: " + json[key].name     

                    var infowindow = new google.maps.InfoWindow()

                    google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
                        return function() {
                         infowindow.setContent(content);
                         infowindow.open(map,marker);
                     };
                 })(marker,content,infowindow)); 
                }
            }
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
      });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
        }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
        });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
          }
          var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
          };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
          }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
          } else {
              bounds.extend(place.geometry.location);
          }
      });
          map.fitBounds(bounds);
      });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgDkjgoAZ7GbKexSSnDtIkBQUrLB6HHXw&libraries=places&callback=initAutocomplete"
async defer></script>
</body>

</html>