'use strict';

(function (window) {
    "use strict";

    var config = window.config;
    var ApiClient = function ApiClient(jq) {
        var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0,
                v = c == 'x' ? r : r & 0x3 | 0x8;
            return v.toString(16);
        });

        this.sendLocation = function (location) {
            jq.post({
                url: '/api/v0/map/' + encodeURI(config.map_name) + '/locations',
                type: 'POST',
                contentType: 'application/vnd.api+json',
                processData: false,
                dataType: 'json',
                data: JSON.stringify({
                    data: {
                        type: 'locations',
                        id: uuid,
                        attributes: location
                    }
                })
            });
        };

        this.getLocations = function (callback) {
            jq.ajax({
                url: '/api/v0/map/' + encodeURI(config.map_name) + '/current_locations',
                contentType: 'application/vnd.api+json',
                success: function success(response) {
                    return callback(response.data ? response.data.attributes.locations : []);
                }
            });
        };
    };

    window['initMap'] = function () {
        var SanFrancisco = { lat: 37.7749, lng: -122.4194 };
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: SanFrancisco
        });

        var withLocation = function withLocation(callback) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (pos) {
                    return callback({ lat: pos.coords.latitude, lng: pos.coords.longitude });
                });
            } else {
                console.log('Oh shi... Geolocation is not supported');
            }
        };

        var api = new ApiClient(jQuery);

        var markers = new function () {
            var markers = [];
            this.refresh = function (locations) {
                markers.map(function (m) {
                    return m.setMap(null);
                });
                markers = [];
                locations.map(function (location) {
                    return markers.push(new google.maps.Marker({ position: location }));
                });
                markers.map(function (m) {
                    return m.setMap(map);
                });
            };
        }();

        withLocation(function (location) {
            map.setCenter(location);
            map.setZoom(11);
        });

        api.getLocations(markers.refresh);
        withLocation(api.sendLocation);
        setInterval(function () {
            return withLocation(api.sendLocation);
        }, 10000);
        setInterval(function () {
            return api.getLocations(markers.refresh);
        }, 3000);
    };
})(window);