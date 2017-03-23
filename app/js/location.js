export default function (navigator) {
  "use strict";
  async function getCurrentPosition() {
    return new Promise((resolve, reject) => {
      if (navigator.geolocation)
        navigator.geolocation.getCurrentPosition(
          position => resolve({lat: position.coords.latitude, lng: position.coords.longitude})
        );
      else
        reject('Oh shi... Geolocation is not supported');
    });
  }

  this.getPosition = getCurrentPosition;
}
