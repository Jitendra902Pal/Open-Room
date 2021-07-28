<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyCvRwR3-fGr8AsnMdzmQVkgCdlWhqUiCG0"></script>
<!--  class based -->
<script>
     var inputs = document.getElementsByClassName('map_location');
var options = {
  types: ['geocode'],
  componentRestrictions: {country: 'In'}
};
var autocompletes = [];
for (var i = 0; i < inputs.length; i++) {
  var autocomplete = new google.maps.places.Autocomplete(inputs[i], options);
  autocomplete.inputId = inputs[i].id;
  autocomplete.addListener('place_changed', fillIn);
  autocompletes.push(autocomplete);
}
function fillIn() {
  console.log(this.inputId);
  var place = this.getPlace();
  console.log(place. address_components[0].long_name);
}
</script>
<!--  id based -->
<script>
       var autocompletefrom;
	      var addressElefrom = document.getElementById('station1');
       var addressEleto = document.getElementById('station2');
	  var dropdownfrom;
	  var timesfrom = 0;
	  
      function initAutocomplete() {
        autocompletefrom = new google.maps.places.Autocomplete(
            (addressElefrom), {
              types: ['geocode'],
              componentRestrictions: {'country': 'in'}
            });
            autocompleteto = new google.maps.places.Autocomplete(
            (addressEleto), {
              types: ['geocode'],
              componentRestrictions: {'country': 'in'}
            });
        autocompleteto.addListener('place_changed', onPlaceChanged);
        autocompletefrom.addListener('place_changed', onPlaceChanged);
      }
	  
     function initAutoObserverfrom(){
     dropdownfrom = document.querySelector('div.pac-container.pac-logo');
     if( dropdownfrom){
          if(timesfrom){ console.log( 'IE sucks... recursive called '+timesfrom+' timesfrom.' ); }
          

          var observerfrom = new MutationObserver(function(){
               if(dropdownfrom.style.display !='none' ){
                    
                    for(var i=0,l=dropdownfrom.children.length; i<l; i++){ 
                         var thespan = dropdownfrom.children[i].lastElementChild
                         thespan.innerHTML = thespan.innerHTML.replace(', IN','');
                    }
               } else {
               
                    addressElefrom.value = addressElefrom.value.replace(', IN','');
                    addressEleto.value = addressEleto.value.replace(', IN','');
                    
               }
          });
          observerfrom.observe(dropdownfrom,  { attributes: true, childList: false });
     }else {
          
          timesfrom++;
          setTimeout( initAutoObserverfrom, 20);
     }
     }
	  window.addEventListener("load", initAutoObserverfrom );
     function onPlaceChanged() {
     var place = autocompletefrom.getPlace();
     if (place.geometry) {
     } else {
          addressElefrom.placeholder = 'Enter From Station';
     }
     }
 
    </script>
<!-- auto fill other info  -->
<script>
      let autocomplete_c;
      const componentForm = {
        locality: "long_name",
        administrative_area_level_1: "long_name",
        postal_code: "short_name",
      };
      function initAutocomplete() {
        autocomplete_c = new google.maps.places.Autocomplete(
          document.getElementById("locality"),
          { types: ["geocode"],  componentRestrictions: {'country': 'in'} }
        );
        autocomplete_c.setFields(["address_component"]);
        autocomplete_c.addListener("place_changed", fillInAddress);
      }
      function fillInAddress() {
        const place = autocomplete_c.getPlace();
        for (const component in componentForm) {
          document.getElementById(component).value = "";
          document.getElementById(component).disabled = false;
        }
        for (const component of place.address_components) {
          const addressType = component.types[0];

          if (componentForm[addressType]) {
            const val = component[componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }
    </script> 
