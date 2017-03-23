"use strict";
export default function (navigator) {
  this.getPosition = () => new Promise((resolve, reject) => {
    if (navigator.geolocation)
      navigator.geolocation.getCurrentPosition(
        position => resolve({lat: position.coords.latitude, lng: position.coords.longitude})
      );
    else
      reject('Oh shi... Geolocation is not supported');
  });
}
