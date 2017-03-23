import ApiClient from "./api-client.js";
import Location from "./location.js";

(function (window) {
  "use strict";

  window['initMap'] = function () {
    const SanFrancisco = {lat: 37.7749, lng: -122.4194};
    const map = new google.maps.Map(
      document.getElementById('map'),
      {
        zoom: 4,
        center: SanFrancisco
      }
    );

    const api = new ApiClient(jQuery, window.config);

    const markers = new function () {
      let markers = [];
      this.refresh = function (locations) {
        markers.map(m => m.setMap(null));
        markers = [];
        locations.map(location => markers.push(
          new google.maps.Marker({position: location})
        ));
        markers.map(m => m.setMap(map));
      };
    };

    async function sendMyLocation() {
      let location = await (new Location(window.navigator)).getPosition();
      api.sendLocation(location);
    }
    async function refreshMarkers() {
      let locations = await api.getLocationsAsync();
      markers.refresh(locations);
    }

    (async function() {
      let location = await (new Location(window.navigator)).getPosition();
      map.setCenter(location);
      map.setZoom(11);
      api.sendLocation(location);
    })();

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

