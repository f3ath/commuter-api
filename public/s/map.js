/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

exports.default = function ($, config) {
  "use strict";

  const uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
    let r = Math.random() * 16 | 0,
        v = c === 'x' ? r : r & 0x3 | 0x8;
    return v.toString(16);
  });

  this.sendPosition = function (location) {
    $.post({
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
    $.ajax({
      url: '/api/v0/map/' + encodeURI(config.map_name) + '/locations',
      contentType: 'application/vnd.api+json',
      success: response => callback(response.data ? response.data.attributes.locations : [])
    });
  };

  this.getLocationsAsync = function () {
    const getLocations = this.getLocations;
    return new Promise(r => getLocations(r));
  };
};

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
  value: true
});

exports.default = function (navigator) {
  "use strict";

  async function getCurrentPosition() {
    return new Promise((resolve, reject) => {
      if (navigator.geolocation) navigator.geolocation.getCurrentPosition(position => resolve({ lat: position.coords.latitude, lng: position.coords.longitude }));else reject('Oh shi... Geolocation is not supported');
    });
  }

  this.getPosition = getCurrentPosition;
};

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _apiClient = __webpack_require__(0);

var _apiClient2 = _interopRequireDefault(_apiClient);

var _location = __webpack_require__(1);

var _location2 = _interopRequireDefault(_location);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

(function (window) {
  "use strict";

  window.initMap = function () {
    const SanFrancisco = { lat: 37.7749, lng: -122.4194 };
    const map = new google.maps.Map(document.getElementById('map'), {
      zoom: 4,
      center: SanFrancisco
    });

    const api = new _apiClient2.default(jQuery, window.config);

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
        locations.map(location => markers.push(new google.maps.Marker(location.type === 'special' ? { position: location, icon: '/s/star.png' } : { position: location })));
        markers.map(m => m.setMap(map));
      };
    }();

    const location = new _location2.default(window.navigator);

    async function sendMyLocation() {
      api.sendPosition((await location.getPosition()));
    }

    async function refreshMarkers() {
      markers.refresh((await api.getLocationsAsync()));
    }

    (async function (map) {
      let position = await location.getPosition();
      map.setCenter(position);
      map.setZoom(11);
      api.sendPosition(position);
    })(map);

    api.getLocations(markers.refresh);
    setInterval(sendMyLocation, 10000);
    setInterval(refreshMarkers, 3000);
  };
})(window);

/***/ })
/******/ ]);