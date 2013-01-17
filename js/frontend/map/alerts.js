var alerts = {
	init: function()
        {
          alerts.mapInit();
          this.Markers = [];
          this.InfoWindows = [];
	},
        
        mapInit: function() {
          if(navigator.geolocation) {
                browserSupportFlag = true;
                navigator.geolocation.getCurrentPosition(function(position) {
                  alerts.MY_latitude  = position.coords.latitude;
                  alerts.MY_longitude = position.coords.longitude;
                  alerts.initGoogleMapContainer(new google.maps.LatLng(position.coords.latitude, position.coords.longitude), alerts.googleMapReady);
                },function() {
                    this.handleNoGeolocation(browserSupportFlag);
                });
            }else if (google.gears){
                  browserSupportFlag = true;
                  geo = google.gears.factory.create('beta.geolocation');
                  geo.getCurrentPosition(function(position) {
                    alerts.MY_latitude  = position.latitude;
                    alerts.MY_longitude = position.longitude;
                    alerts.initGoogleMapContainer(new google.maps.LatLng(position.latitude, position.longitude), index.googleMapReady);
                  }, function() {
                    this.handleNoGeoLocation(browserSupportFlag);
                  });
            }else{
                  browserSupportFlag = false;
                  this.handleNoGeolocation(browserSupportFlag);
            }
        },
        
        
        getMarkers: function(){
          $.ajax({
            url: SYS.baseUrl + 'api/maps/getMapWithAlerts',
            type: 'POST',
            dataType: 'json',
            data: $.param({latitude:alerts.MY_latitude, longitude:alerts.MY_longitude}),
            success: function(res){
              $.each(res.data.pets, function(key, value){
                alerts.addMarkerAndInfoBlock(value);
              });
              $.each(alerts.Markers, function(key, marker){
                 google.maps.event.addListener(marker, 'mouseover', function(){
                    alerts.InfoWindows[key].open(alerts.map, marker);
                 });
                 google.maps.event.addListener(marker, 'mouseout', function(){
                   setTimeout(function(){alerts.InfoWindows[key].close();},2000);
                 });
              });
              
            }
          })
        },
        
        
        
        handleNoGeolocation: function (errorFlag) {
          if (!errorFlag == true){
            alerts.MY_latitude  = 0;
            alerts.MY_longitude = 0;
            alert("Your browser doesn't support geolocation. We've placed you in Siberia.");
            center = siberia;
          }
          alerts.initGoogleMapContainer(center, alerts.googleMapReady());
        },
        
        googleMapReady: function(){
          alerts.clearOverlaysfunction();
          alerts.getMarkers(0);
        },
        
        clearOverlaysfunction: function() {
          if (alerts.Markers) {
            for (i in alerts.Markers) {
              alerts.Markers[i].setMap(null);
            }
            alerts.Markers.length = 0;
          }
        },
        
        initGoogleMapContainer: function(center, callback){
          myOptions = {
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
          alerts.map       = new google.maps.Map(document.getElementById("location-map"), myOptions);
          alerts.my_marker = new google.maps.Marker({
                                            animation: google.maps.Animation.DROP,
                                            position: center,
                                            icon: '/img/my_marker.png',
                                            map: alerts.map
                                          });
          callback();
        },
        
        addMarkerAndInfoBlock: function(data){
            marker = new google.maps.Marker({
                                            animation: google.maps.Animation.DROP,
                                            position: new google.maps.LatLng(data.point.latitude, data.point.longitude),
                                            map: alerts.map
                                          });
                                          
            infowindow          = new google.maps.InfoWindow({
                                    position: new google.maps.LatLng(data.point.latitude, data.point.longitude),
                                  });
                                  
            if(typeof data.picture === 'undefined')
                  data.picture = SYS.baseUrl + '/img/260x180.gif'                      
                                  
            if(data.status == 'lost'){           
                data.status = "Lost";
                marker.setIcon('/img/markers/lost_marker.png');
                infowindow.setContent('<h3>STATUS : '+ data.status +'</h3>\n\
                                       <p>Name : <a href="'+SYS.baseUrl+data.id+'"> '+data.name+'</a></p>\n\
                                       <p><img width="50px" heidth="50px" src="'+data.picture+'"></p>');
            }
            if(data.status == 'find'){  
                data.status = "Find";
                marker.setIcon('/img/markers/find_marker.png');
                infowindow.setContent('<h3>STATUS : '+ data.status +'</h3>\n\
                                       <p>Name : <a href="'+SYS.baseUrl+data.id+'"> '+data.name+'</a></p>\n\ ');
            }
            if(data.status == 'unknown'){ 
                data.status = "Unknown";
                marker.setIcon('/img/markers/unknown_marker.png');
                infowindow.setContent('<h3>STATUS : ' + data.status  + '</h3>\n\
                                       <p> Description: ' + data.description + ' </p>\n\
                                       <p> Address: ' + data.address + ' </p>\n\
                                       <p><img width="50px" heidth="50px" src="' + data.picture + '"></p>');
            }
              
            alerts.InfoWindows.push(infowindow);
            alerts.Markers.push(marker);
            return marker;
        }
        
        
}

$(function() {
      alerts.init();
});