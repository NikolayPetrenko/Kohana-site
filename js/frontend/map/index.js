var index = {
	init: function()
        {
          index.mapInit();
          this.Markers = [];
          this.InfoWindows = [];
          this.categoryObserver();
	},
        
        categoryObserver: function(){
          $('button').click(function(){
            index.clearOverlaysfunction();
            index.getMarkers($(this).attr('data-rel'));
          });
        },
        
        
        mapInit: function() {
          if(navigator.geolocation) {
                browserSupportFlag = true;
                navigator.geolocation.getCurrentPosition(function(position) {
                  index.MY_latitude  = position.coords.latitude;
                  index.MY_longitude = position.coords.longitude;
                  index.initGoogleMapContainer(new google.maps.LatLng(position.coords.latitude, position.coords.longitude), index.googleMapReady);
                },function() {
                    this.handleNoGeolocation(browserSupportFlag);
                });
            }else if (google.gears){
                  browserSupportFlag = true;
                  geo = google.gears.factory.create('beta.geolocation');
                  geo.getCurrentPosition(function(position) {
                    index.MY_latitude  = position.latitude;
                    index.MY_longitude = position.longitude;
                    index.initGoogleMapContainer(new google.maps.LatLng(position.latitude, position.longitude), index.googleMapReady);
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
            index.MY_latitude  = 0;
            index.MY_longitude = 0;
            alert("Your browser doesn't support geolocation. We've placed you in Siberia.");
            center = siberia;
          }
          index.initGoogleMapContainer(center, index.googleMapReady());
        },
        
        
        getMarkers: function(category){
          $.ajax({
            url: SYS.baseUrl + 'api/maps/getLocations',
            type: 'POST',
            dataType: 'json',
            data: $.param({latitude:index.MY_latitude, longitude:index.MY_longitude, category: category}),
            success: function(res){
              $.each(res.data.locations, function(key, value){
                index.addMarkerAndInfoBlock(value);
              });
              $.each(index.Markers, function(key, marker){
                 google.maps.event.addListener(marker, 'mouseover', function(){
                    index.InfoWindows[key].open(index.map, marker);
                 });
                 google.maps.event.addListener(marker, 'mouseout', function(){
                   setTimeout(function(){index.InfoWindows[key].close();},20000);
                 });
              });
              
            }
          })
        },
        
        
        googleMapReady: function(){
          index.clearOverlaysfunction();
          index.getMarkers(0);
        },
        
        clearOverlaysfunction: function() {
          if (index.Markers) {
            for (i in index.Markers) {
              index.Markers[i].setMap(null);
            }
            index.Markers.length = 0;
            index.InfoWindows.length = 0;
          }
        },
        
        initGoogleMapContainer: function(center, callback){
          index.myOptions = {
                                      streetViewControl: true,
                                      zoom: 8,
                                      mapTypeId: google.maps.MapTypeId.ROADMAP,
                                      center: center,
                                      mapTypeControl: false,
                                      zoomControl: true,
                                      zoomControlOptions: {
                                          style: google.maps.ZoomControlStyle.Ml
                                      }
                                }
          index.map       = new google.maps.Map(document.getElementById("location-map"), index.myOptions);
          index.my_marker = new google.maps.Marker({
                                            animation: google.maps.Animation.DROP,
                                            position: center,
                                            icon: '/img/my_marker.png',
                                            map: index.map
                                          });
          callback();
        },
        
        addMarkerAndInfoBlock: function(data){
            marker = new google.maps.Marker({
                                            animation: google.maps.Animation.DROP,
                                            position: new google.maps.LatLng(data.point.latitude, data.point.longitude),
                                            map: index.map
                                          });
                                          
            infowindow          = new google.maps.InfoWindow({
                                    position: new google.maps.LatLng(data.point.latitude, data.point.longitude),
                                    content:  "<div  class='mapInfo'>\n\
                                                <div class='littleAngle'></div>\n\
                                                <div class='titleMapInfo'><h2>"+data.name+"</h2><h3>\n\
                                                    <span>Category:</span> "+ data.category +"</h3><h4>\n\
                                                    <span>Distance:</span>" + data.distance + " km</h4>\n\
                                                </div>\n\
                                                <a href="+SYS.baseUrl + "map/location/"+data.id +">\n\
                                                 <div class='wantMore'><h3>Want to know more?</h3>\n\
                                                 </div>\n\
                                                  </a>\n\
                                                </div>"
                                  });
                                  
            //marker.setIcon('/img/facebook_icon.png'); this work (test)
            index.InfoWindows.push(infowindow);
            index.Markers.push(marker);
            return marker;
        }
        
        
}

$(function() {
      index.init();
});