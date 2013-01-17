var details = {
	init: function()
        {
          this.mapInit();
	},
        
        mapInit: function() {
          if(navigator.geolocation) {
                browserSupportFlag = true;
                navigator.geolocation.getCurrentPosition(function(position) {
                  details.MY_latitude  = position.coords.latitude;
                  details.MY_longitude = position.coords.longitude;
                  details.initGoogleMapContainer(new google.maps.LatLng(position.coords.latitude, position.coords.longitude), details.googleMapReady);
                },function() {
                    this.handleNoGeolocation(browserSupportFlag);
                });
            }else if (google.gears){
                  browserSupportFlag = true;
                  geo = google.gears.factory.create('beta.geolocation');
                  geo.getCurrentPosition(function(position) {
                    details.MY_latitude  = position.latitude;
                    details.MY_longitude = position.longitude;
                    details.initGoogleMapContainer(new google.maps.LatLng(position.latitude, position.longitude), details.googleMapReady);
                  }, function() {
                    this.handleNoGeoLocation(browserSupportFlag);
                  });
            }else{
                  browserSupportFlag = false;
                  this.handleNoGeolocation(browserSupportFlag);
            }
        },
        
         handleNoGeolocation: function (errorFlag) {
          if (!errorFlag == true){
            details.MY_latitude  = 0;
            details.MY_longitude = 0;
            alert("Your browser doesn't support geolocation. We've placed you in Siberia.");
            center = siberia;
          }
          details.initGoogleMapContainer(center, details.googleMapReady());
        },
        
        initGoogleMapContainer: function(center, callback){
          details.myOptions = {
                                      streetViewControl: true,
                                      zoom: 10,
                                      mapTypeId: google.maps.MapTypeId.ROADMAP,
                                      center: new google.maps.LatLng(details_info.latitude, details_info.longitude),
                                      mapTypeControl: false,
                                      zoomControl: true,
                                      zoomControlOptions: {
                                          style: google.maps.ZoomControlStyle.Ml
                                      }
                                }
          details.map       = new google.maps.Map(document.getElementById("location-map"), details.myOptions);
          details.my_marker = new google.maps.Marker({
                                            animation: google.maps.Animation.DROP,
                                            position: center,
                                            icon: '/img/my_marker.png',
                                            map: details.map
                                          });
          marker = new google.maps.Marker({
                                            animation: google.maps.Animation.DROP,
                                            position: new google.maps.LatLng(details_info.latitude, details_info.longitude),
                                            map: details.map
                                          });
                                          
          if(details_info.status == 'lost'){           
              marker.setIcon('/img/markers/lost_marker.png');
          }
          if(details_info.status == 'find'){  
              marker.setIcon('/img/markers/find_marker.png');
          }
          if(details_info.status == 'unknown'){ 
              marker.setIcon('/img/markers/unknown_marker.png');
          }
                                          
                                          
          callback();
        },
        
        
        googleMapReady: function(){
          
        }
        
        
        
}

$(function() {
      details.init();
});