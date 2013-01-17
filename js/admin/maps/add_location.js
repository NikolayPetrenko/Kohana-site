
var add_location = {
	init: function() {
          this.latitude        = $('input[name=latitude]');
          this.longitude       = $('input[name=longitude]');
          
          this.geocoder        = new google.maps.Geocoder();
          this.address         = document.getElementById('address');
          
          this.initGoogleMaps();
          this.changeGoogleMarkerByCoordiante();
          this.validationInit();
          this.pictureUploadInit();
          this.switchInput();
	},
        
        switchInput: function() {
          $('.phone_js').keyup(function(){
            if($(this).val().length == parseInt($(this).attr('maxlength'))) {
              $(this).closest('.controls').find('.phone_js[rel="'+parseInt(parseInt($(this).attr('rel'))+1)+'"]').focus();
            }
          })
        },
        
        
        detectCoordinats: function(){
            if(add_location.latitude.val() != '' && add_location.longitude.val() != ''){
                newPosition = new google.maps.LatLng(add_location.latitude.val(), add_location.longitude.val());
                add_location.addMarker(newPosition);
            }
        },
        validationInit: function(){
          $('form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 4
                    },
                    category_id:{
                        required: true
                    },
                    address:{
                        required: true
                    },
                    latitude:{
                        required: true,
                        number: true
                    },
                    longitude:{
                        required: true,
                        number: true
                    },
                    phone:{
                        number: true
                    }
                }
            });
        },
        
        initGoogleMaps: function(){
            console.log('hello');
            if(navigator.geolocation) {
                browserSupportFlag = true;
                navigator.geolocation.getCurrentPosition(function(position) {
                  center = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                  add_location.initGoogleMapContainer(center);
                  add_location.detectCoordinats();
                },function() {
                    this.handleNoGeolocation(browserSupportFlag);
                });
            }else if (google.gears){
                  browserSupportFlag = true;
                  geo = google.gears.factory.create('beta.geolocation');
                  geo.getCurrentPosition(function(position) {
                    center = new google.maps.LatLng(position.latitude,position.longitude);
                    add_location.initGoogleMapContainer(center);
                    add_location.detectCoordinats();
                    //map.setCenter(initialLocation);
                  }, function() {
                    this.handleNoGeoLocation(browserSupportFlag);
                  });
            }else{
                  //center = new google.maps.LatLng(0, 0);
                  //add_location.initGoogleMapContainer(center);
                  //add_location.detectCoordinats();
                  browserSupportFlag = false;
                  this.handleNoGeolocation(browserSupportFlag);
            }
            
        },
        
        
        handleNoGeolocation: function (errorFlag) {
          if (errorFlag == true) {
            alert("Geolocation service failed.");
            center = newyork;
          } else {
            alert("Your browser doesn't support geolocation. We've placed you in Siberia.");
            center = siberia;
          }
          add_location.initGoogleMapContainer(center);
          //map.setCenter(initialLocation);
        },
        
        initGoogleMapContainer: function(center){
          add_location.myOptions = {
                                      streetViewControl: false,
                                      zoom: 10,
                                      mapTypeId: google.maps.MapTypeId.ROADMAP,
                                      center: center,
                                      streetViewControl: true,
                                      mapTypeControl: false,
                                      zoomControl: true,
                                      zoomControlOptions: {
                                          style: google.maps.ZoomControlStyle.Ml
                                      }
                                    }

          add_location.map = new google.maps.Map(document.getElementById("location-map"), add_location.myOptions);
          add_location.autocomplete = new google.maps.places.Autocomplete(add_location.address); 
          add_location.infowindow   = new google.maps.InfoWindow();
          
          
          google.maps.event.addListener(add_location.map, 'click', function(event) {
            add_location.locationChangeListeners(event.latLng);
          });
          
          google.maps.event.addListener(add_location.autocomplete, 'place_changed', add_location.autocompliteListener);
          
          $(add_location.address).keyup(function(e){
              add_location.geocoderFromAddress($(this).val(), 10);
          });
          
        },
        
        autocompliteListener: function(){
            place = add_location.autocomplete.getPlace();
            add_location.addMarker(place.geometry.location);
            add_location.changeCoordsInForm(place.geometry.location);
            if (place.geometry.viewport) {
                add_location.map.fitBounds(place.geometry.viewport);
            } else {
                add_location.map.setCenter(place.geometry.location);
                add_location.map.setZoom(10);
            }
            add_location.windowInfoListner(place.name, place.formatted_address);
        },
        
        windowInfoListner: function(name, description){
            add_location.infowindow.close();
            add_location.infowindow.setContent('<b>' + name + '</b><p>' + description + '</p>');
            add_location.infowindow.open(add_location.map, add_location.marker);
        },
        
        
        
        locationChangeListeners: function(location){
            add_location.changeCoordsInForm(location);
            add_location.addMarker(location);
            add_location.geocoderFromLocation(location);
        },
        
        
        geocoderFromLocation: function(location) {
            add_location.geocoder.geocode({'latLng': location}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                            $(add_location.address).val(results[1].formatted_address);
                            add_location.windowInfoListner(results[0].address_components[1].short_name, results[0].formatted_address);
                    }
                }
            });
        },
        
        geocoderFromAddress: function (address, zoom) {
            add_location.geocoder.geocode({'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    add_location.map.setCenter(results[0].geometry.location);
                    add_location.map.setZoom(zoom);
                }
            });               
        },
        
        
        addMarker: function(location){
            if ( this.marker ) {
                this.marker.setPosition(location);
            }else{
                this.marker = new google.maps.Marker({
                                                      animation: google.maps.Animation.DROP,
                                                      position: location,
                                                      map: add_location.map
                                                    });
            }
        },
        
        changeCoordsInForm: function(location)
        {
            add_location.latitude.val(location.lat());
            add_location.longitude.val(location.lng());
        },
        
        
        changeGoogleMarkerByCoordiante: function()
        {
          $('input[name=latitude], input[name=longitude]').keyup(function(){
              newPosition = new google.maps.LatLng(add_location.latitude.val(), add_location.longitude.val());
              add_location.addMarker(newPosition);
          });
        },
        
        pictureUploadInit: function()
        {
            $('input[type=file]').fileupload({
                url: SYS.baseUrl + 'upload/images',
                maxFileSize: 5000000,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                placeholder: '',
                process: [
                    {
                        action: 'load',
                        fileTypes: /^image\/(gif|jpeg|png)$/,
                        maxFileSize: 20000000 // 20MB
                    },
                    {
                        action: 'resize',
                        maxWidth: 1440,
                        maxHeight: 900
                    },
                    {
                        action: 'save'
                    }
                ],

                dataType: 'json',
                done: function (e, data) {
                      if(data.result.text == 'success'){
                        loadingStop();
                        $('div.span3').find('img').attr({src: SYS.baseUrl + 'images/temp/' + data.result.data.file})
                        $('input[name=picture]').val(data.result.data.file);
                      }
                }
            }).bind('fileuploadstart', function (e, data) {
                loadingStart($('.thumbnail'));
            }).bind('fileuploadstop', function (e) {

            });
        }
        
}


//var category = {
//        init: function(){
//          this.categorySelect  = $('#main_categories');
//          this.subCategories   = $('#sub-categories');
//          this.categorySelectObserver();
//        },
//        
//        categorySelectObserver: function() {
//          this.categorySelect.change(function(){
//              id = $(this).find(':selected').val();
//              $.ajax({
//                url: SYS.baseUrl + 'admin/maps/ajaxGetCategory',
//                type: 'POST',
//                dataType: 'json',
//                data: $.param({
//                      id: id
//                      }),
//                success: function(res){
//                  if(res.text == 'success')
//                      $('span#sub-categories-container').html(res.data.container);
//                }
//                
//              })
//          });
//        }
//}

$(document).ready(function() {
    add_location.init();
    //category.init();
});