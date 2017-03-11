(function () {
    "use strict";

    const ApiClient = function (jq) {
        const uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            let r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
            return v.toString(16);
        });

        this.sendLocation = function (location) {
            jq.post({
                url: '/locations',
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
                url: '/current_locations',
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
                locations.map(location => markers.push(new google.maps.Marker({position: location})));
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
            5000
        );
        setInterval(
            () => api.getLocations(markers.refresh),
            5000
        );
    };
})();

