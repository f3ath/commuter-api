import ApiClient from "./api-client.js";
import Location from "./location.js";

(function (window) {
  "use strict";

  window.initMap = function () {
    const SanFrancisco = {lat: 37.7749, lng: -122.4194};
    const map = new google.maps.Map(
      document.getElementById('map'),
      {
        zoom: 4,
        center: SanFrancisco
      }
    );

    const api = new ApiClient(jQuery, window.config);

    google.maps.event.addListener(map, "rightclick", function (event) {
      const location = {
        lat: event.latLng.lat(),
        lng: event.latLng.lng(),
        type: 'special',
        expires: 60 * 60
      };
      api.sendPosition(location);
    });


    const markers = new function () {
      let markers = [];
      this.refresh = function (locations) {
        markers.map(m => m.setMap(null));
        markers = [];
        locations.map(location => markers.push(
          new google.maps.Marker(
            location.type === 'special' ? {position: location, icon : '/s/star.png'}: {position: location}
            )
        ));
        markers.map(m => m.setMap(map));
      };
    };

    const location = new Location(window.navigator);

    async function sendMyLocation() {
      api.sendPosition(await location.getPosition());
    }

    async function refreshMarkers() {
      markers.refresh(await api.getLocationsAsync());
    }

    (async function (map) {
      let position = await location.getPosition();
      map.setCenter(position);
      map.setZoom(11);
      api.sendPosition(position);
    })(map);

    api.getLocations(markers.refresh);
    setInterval(
      sendMyLocation,
      10000
    );
    setInterval(
      refreshMarkers,
      3000
    );
  };
})(window);

