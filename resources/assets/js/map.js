var mapDiv = document.getElementById('map');
var map2Div = document.getElementById('agencies-map');
var mapLoad = function(e) {
    mapDiv.removeEventListener('click', mapLoad);
    $.getScript( "https://maps.googleapis.com/maps/api/js?key=AIzaSyBIc5obn1ArfkEzXhkgZiMyyHPRQmjNx5M", function() {
        init();
    });
};
mapDiv.addEventListener('click', mapLoad);
if(map2Div)
    mapLoad();

// When the window has finished loading create our google map below
//google.maps.event.addDomListener(window, 'load', init);

function init() {
    // Basic options for a simple Google Map
    // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
    var mapOptions = {
        // How zoomed in you want the map to start at (always required)
        zoom: 15,

        // The latitude and longitude to center the map (always required)
        center: new google.maps.LatLng(55.709328, 37.653426), /* Moscow*/

        // Do not change zoom on mouse scroll
        scrollwheel: false,

        // How you would like to style the map.
        // This is where you would paste any style found on Snazzy Maps.
        styles: [
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#e9e9e9"
                    },
                    {
                        "lightness": 17
                    }
                ]
            },
            {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    },
                    {
                        "lightness": 20
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 17
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 29
                    },
                    {
                        "weight": 0.2
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 18
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 16
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    },
                    {
                        "lightness": 21
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#dedede"
                    },
                    {
                        "lightness": 21
                    }
                ]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "visibility": "on"
                    },
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 16
                    }
                ]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "saturation": 36
                    },
                    {
                        "color": "#333333"
                    },
                    {
                        "lightness": 40
                    }
                ]
            },
            {
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f2f2f2"
                    },
                    {
                        "lightness": 19
                    }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#fefefe"
                    },
                    {
                        "lightness": 20
                    }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#fefefe"
                    },
                    {
                        "lightness": 17
                    },
                    {
                        "weight": 1.2
                    }
                ]
            }
        ]
    };

    // Get the HTML DOM element that will contain your map
    // We are using a div with id="map" seen below in the <body>
    var mapElement = document.getElementById('map');
    // Create the Google Map using our element and options defined above
    var map = new google.maps.Map(mapElement, mapOptions);

    // Let's also add a marker while we're at it
    var bigMarker = new google.maps.MarkerImage(
        '/img/map_point-min.png',
        new google.maps.Size(126,132),
        new google.maps.Point(0,0),
        new google.maps.Point(118,91)
    );
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(55.709328, 37.653426),
        map: map,
        title: 'Магазин',
        icon: bigMarker
    });

    mapElement2 = document.getElementById('agencies-map');
    // Create the Google Map using our element and options defined above
    var $agencies = $('.js-agencies');
    var shops = {
        lat:$agencies.find('.js-lat'),
        long:$agencies.find('.js-long'),
        address:$agencies.find('.js-address'),
    }
    //if shop markers is apsend then create map and place markers
    if(shops.lat.length) {
        var zoom = $(map2Div).data('zoom');
        var lat = $(map2Div).data('lat');
        var long = $(map2Div).data('long');
        if(zoom) {
            mapOptions.zoom = zoom;
        }
        // Let's also add a marker while we're at it
        var marker2;
        var address;
        if(!lat)
            lat = shops.lat.eq(0).val();
        if(!long)
            long = shops.long.eq(0).val();
        mapOptions.center = new google.maps.LatLng(lat, long);
        var map2 = new google.maps.Map(mapElement2, mapOptions);
        var markerImage = new google.maps.MarkerImage(
            '/img/map-point-small-min.png',
            new google.maps.Size(52,51),
            new google.maps.Point(0,0),
            new google.maps.Point(47,42)
        );

        for(var i = 0; i < shops.lat.length; i++) {
            address = shops['address'].eq(i).val();
            if(!address) {
                address = shops['address'].eq(i).text()
            }
            marker2 = new google.maps.Marker({
                position: new google.maps.LatLng(shops.lat.eq(i).val(), shops.long.eq(i).val()),
                map: map2,
                title: address,
                icon: markerImage
            });
        }
    }
    // center map on window resize
    google.maps.event.addDomListener(window, "resize", function() {
        var center = map.getCenter();
        google.maps.event.trigger(map, "resize");
        map.setCenter(center);
    });
}