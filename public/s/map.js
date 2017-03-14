(function () {
    "use strict";

    const ApiClient = function (jq) {
        const uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            let r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
            return v.toString(16);
        });

        this.sendLocation = function (location) {
            jq.post({
                url: '/api/v0/map/default/locations',
                type: 'POST',
                contentType: 'application/vnd.api+json',
                processData: false,
                dataType: 'json',
                data: JSON.stringify({
                    data: {
                        type: 'locations',
                        id: uuid,
                        attributes: location,
                    }
                }),
            });
        };

        this.getLocations = function (callback) {
            jq.ajax({
                url: '/api/v0/map/default/current_locations',
                contentType: 'application/vnd.api+json',
                success: response => callback(
                    response.data ? response.data.attributes.locations : []
                )
            });
        };

    };

    window['initMap'] = function () {
        const SanFrancisco = {lat: 37.7749, lng: -122.4194};
        const map = new google.maps.Map(
            document.getElementById('map'),
            {
                zoom: 4,
                center: SanFrancisco
            }
        );


        const withLocation = function (callback) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    pos => callback({lat: pos.coords.latitude, lng: pos.coords.longitude})
                );
            } else {
                console.log('Oh shi... Geolocation is not supported');
            }
        };

        const api = new ApiClient(jQuery);

        const markers = new function() {
            let markers = [];
            this.refresh = function (locations) {
                markers.map(m => m.setMap(null));
                markers = [];
                locations.map(location => markers.push(
                    //new google.maps.Marker({position: {lat: location.lat, lng: location.lng}})
                    //alert(JSON.stringify(location)) &&
                    new google.maps.Marker({position: {lat: 0 + location.lat, lng: 0 + location.lng}})
                ));
                markers.map(m => m.setMap(map));
            };
        };

        withLocation(function (location) {
            map.setCenter(location);
            map.setZoom(11);
        });

        api.getLocations(markers.refresh);
        withLocation(api.sendLocation);
        setInterval(
            () => withLocation(api.sendLocation),
            10000
        );
        setInterval(
            () => api.getLocations(markers.refresh),
            3000
        );
    };
})();

