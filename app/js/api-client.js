export default function ($, config) {
  "use strict";
  const uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
    let r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
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
          attributes: location,
        }
      }),
    });
  };

  this.getLocations = function (callback) {
    $.ajax({
      url: '/api/v0/map/' + encodeURI(config.map_name) + '/current_locations',
      contentType: 'application/vnd.api+json',
      success: response => callback(
        response.data ? response.data.attributes.locations : []
      )
    });
  };

  this.getLocationsAsync = function () {
    const getLocations = this.getLocations;
    return new Promise(r => getLocations(r));
  };
}
