(function () {
    "use strict";

    window['initMap'] = function() {
        let myLatLng = {lat: -25.363, lng: 131.044};

        let map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: myLatLng
        });

        const mutate = function(p) {
            const d_lat = 0.03;
            const d_lng = -0.05;
            return {
                lat: p.lat + Math.random() * d_lat,
                lng: p.lng + Math.random() * d_lng,
            };
        };
        let markers = [];
        setInterval(
            function() {
                markers.map(m => m.setMap(null));
                markers = [];
                for (let i = 0; i < 1000; i++) {
                    markers.push(new google.maps.Marker({
                        position: mutate(myLatLng)
                    }))
                }
                markers.map(m => m.setMap(map));
            },
            1000
        );
    };
})();

