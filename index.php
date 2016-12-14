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
                margin-top: 10px;
                border: 2px solid transparent;
                border-radius: 2px 0 0 2px;
                box-sizing: border-box;
                -moz-box-sizing: border-box;
                height: 40px;
                outline: none;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            }

            #pac-input {
                background-color: #fff;
                font-family: Roboto;
                font-size: 18px;
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
                    <div class="navbar-header col-md-12">
                        <div class="col-md-4">
                            <label><a href="index.php"><img src="assets/medikuLogo.png" style="height: 50px"/>  Mediku</a> </label>                            
                        </div>
                        <div class="col-md-3">                          
                        </div>                       
                        <div class="col-md-5" id="kanan">
                            <label>Tugas Akhir Sistem Informasi Geografis People Power</label>                       
                        </div>

                    </div>

                </div>
            </nav>

        </div>
        <div class="home">
            <div class="sidebar">
                <div id="search-box">
                    <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                </div>
            </div>

            <div id="map">                
            </div>

            <div id="side">                  
                <div id="dvDistance">                    
                </div>
                <div id="dvPanel">                
                </div>                
            </div>
        </div>
        <script>
            var global;
            var startLat;
            var startLong;
            var map;
            var tmp;
            function initAutocomplete() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: -6.4025, lng: 106.7942},
                    zoom: 16,
                    mapTypeId: 'roadmap'
                });
                var opt = {minZoom: 14, maxZoom: 17};
                map.setOptions(opt);
                var infoWindow = new google.maps.InfoWindow({map: map});
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        var pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        global = position.coords.latitude + "," + position.coords.longitude;
                        startLat = position.coords.latitude;
                        startLong = position.coords.longitude;
                        var icons = 'assets/loc64.png';
                        infoWindow.setPosition(pos);
                        infoWindow.setContent('Your Location');
                        map.setCenter(pos);
                        var marker = new google.maps.Marker({
                            map: map,
                            position: pos,
                            icon: icons,
                        });
                    }, function () {
                        /*handleLocationError(true, infoWindow, map.getCenter());*/
                    });
                } else {
                    // Browser doesn't support Geolocation
                    /*handleLocationError(false, infoWindow, map.getCenter());*/
                }

                $.getJSON("data.json", function (json) {
                    var marker;
                    var i = 0;
                    for (var key in json) {
                        if (json.hasOwnProperty(key)) {
                            var icons = 'assets/hsp64.png';
                            var marker = new google.maps.Marker({
                                map: map,
                                position: new google.maps.LatLng(json[key].geometry.location.lat, json[key].geometry.location.lng),
                                icon: icons,
                            });
                            var geoLat = json[key].geometry.location.lat;
                            var geoLng = json[key].geometry.location.lng;

                            var content =
                                    "<div style='width:180px;length:150px'><h4>" + json[key].name + "</h4>\n\
                                <p>" + json[key].formatted_address + "</p><p style='text-align:center;color:grey'>" + geoLat + " , " + geoLng +
                                    "<p style='text-align:center'><button class='btn btn-primary' onclick='directionFunction(" + geoLat + "," + geoLng + ")'>Direction</button></p>";

                            var infowindow = new google.maps.InfoWindow()
                            infowindow.open();

                            google.maps.event.addListener(marker, 'click', (function (marker, content, infowindow) {
                                return function () {
                                    infowindow.setContent(content);
                                    infowindow.open(map, marker);
                                };
                            })(marker, content, infowindow));
                        }
                    }
                });

                // Create the search box and link it to the UI element.
                var input = document.getElementById('pac-input');
                var searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                // Bias the SearchBox results towards current map's viewport.
                map.addListener('bounds_changed', function () {
                    searchBox.setBounds(map.getBounds());
                });

                var markers = [];
                // Listen for the event fired when the user selects a prediction and retrieve
                // more details for that place.
                searchBox.addListener('places_changed', function () {
                    var places = searchBox.getPlaces();
                    if (places.length == 0) {
                        return;
                    }

                    // Clear out the old markers.
                    markers.forEach(function (marker) {
                        marker.setMap(null);
                    });
                    markers = [];

                    // For each place, get the icon, name and location.
                    var bounds = new google.maps.LatLngBounds();
                    places.forEach(function (place) {
                        if (!place.geometry) {
                            console.log("Returned place contains no geometry");
                            return;
                        }
                        startLat = place.geometry.location.lat();
                        startLong = place.geometry.location.lng();
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

            function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                infoWindow.setPosition(pos);
                infoWindow.setContent(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
            }

            function directionFunction(lat, long) {
                var iconstart = 'assets/loc64.png';
                var iconfinish = 'assets/hsp64.png';
                var infoWindow = new google.maps.InfoWindow({map: map});
                var pointA = new google.maps.LatLng(startLat, startLong),
                        pointB = new google.maps.LatLng(lat, long),
                        myOptions = {
                            zoom: 15,
                            center: pointA
                        },
                map = new google.maps.Map(document.getElementById('map'), myOptions),
                        // Instantiate a directions service.
                        directionsService = new google.maps.DirectionsService,
                        directionsDisplay = new google.maps.DirectionsRenderer({
                            map: map
                        }),
                        markerA = new google.maps.Marker({
                            position: pointA,
                            title: "Your Location",
                            map: map,
                            icon: iconstart
                        }),
                        markerB = new google.maps.Marker({
                            position: pointB,
                            title: "Destination",
                            map: map,
                            icon: iconfinish
                        });

                var opt = {minZoom: 14, maxZoom: 17};
                map.setOptions(opt);
                //directionsDisplay.setMap(map);                
                var string = "https://maps.googleapis.com/maps/api/directions/json?origin=" + startLat + "," + startLong + "&destination=" + lat + "," + long + "&key=AIzaSyDgDkjgoAZ7GbKexSSnDtIkBQUrLB6HHXw";

                $.getJSON(string, function (data) {
                    tmp = data;
                });
                
                // get route from A to B
                calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB);
                showDiv()
            }

            function calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB) {
                directionsDisplay.setPanel(document.getElementById('dvPanel'));
                directionsService.route({
                    origin: pointA,
                    destination: pointB,
                    travelMode: google.maps.TravelMode.DRIVING
                }, function (response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                        var distance = tmp.routes[0].legs[0].distance.text;
                        var duration = tmp.routes[0].legs[0].duration.text;
                        console.log(distance, duration)
                        var dvDistance = document.getElementById("dvDistance");                         
                        dvDistance.innerHTML += "<div style='width:100%;background-color:white;font-size: 20px;padding:10px;text-align:center;'><a href='index.php' class='btn btn-primary'>Exit Direction Mode</a><h3>Distance : " + distance + "</h3><p>Duration : " + duration + "</p></div>";
                    } else {
                        window.alert('Directions request failed due to ' + status);
                    }
                });

            }
            
            function showDiv(){
                var x = document.getElementById("side");
                var y = document.getElementById("dvDistance");
                x.style.visibility = "visible";
                y.style.visibility = "visible";
        }

        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgDkjgoAZ7GbKexSSnDtIkBQUrLB6HHXw&libraries=places&callback=initAutocomplete"
        async defer></script> 

    </body>

</html>