(function () {
    "use strict";

    const ApiClient = function (jq) {
        let _id = null;

        const _makeLocationRequest = (location, id = null) => (
            {
                data: {
                    type: 'locations',
                    id: id,
                    attributes: {
                        lat: location.lat,
                        lng: location.lng,
                    }
                }
            }
        );

        this.init = function (location) {
            const setId = id => _id = id;

            jq.post({
                url: '/locations',
                type: 'POST',
                contentType: 'application/vnd.api+json',
                processData: false,
                dataType: 'json',
                data: JSON.stringify(_makeLocationRequest(location)),
                success: data => setId(data.id),
            });
        };

        this.getLocations = function (callback) {
            jq.ajax({
                url: '/locations',
                contentType: 'application/vnd.api+json',
                success: response => callback(
                    response.data ? response.data.map(d => ({lat: d.attributes.lat, lng: d.attributes.lng})): []
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
                console.log('Geolocation not supported');
            }
        };

        const api = new ApiClient(jQuery);

        let markers = [];
        const refreshMarkers = function (locations) {
            markers.map(m => m.setMap(null));
            markers = [];
            locations.map(location => markers.push(new google.maps.Marker({position: location})));
            markers.map(m => m.setMap(map));
        };

        withLocation(location => api.init(location));

        withLocation(function (location) {
            map.setCenter(location);
            map.setZoom(11);
        });

        api.getLocations(refreshMarkers);
        setInterval(
            function () {
                api.getLocations(refreshMarkers);
            },
            10000
        );
    };
})();

