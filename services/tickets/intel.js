
  
	  
$(document).ready(function(){
const inputHome = document.querySelector("#phone");
const inputMobile = document.querySelector("#phone1");
const inputVacation = document.querySelector("#vacation");

window.intlTelInput(inputHome, {
	initialCountry: "auto",
         geoIpLookup: function(callback) {
         fetch("https://ipapi.co/json")
        .then(function(res) { return res.json(); })
           .then(function(data) { callback(data.country_code); })
          .catch(function() { callback("us"); });
        },
});


window.intlTelInput(inputMobile, {
	initialCountry: "auto",
         geoIpLookup: function(callback) {
         fetch("https://ipapi.co/json")
        .then(function(res) { return res.json(); })
           .then(function(data) { callback(data.country_code); })
          .catch(function() { callback("us"); });
        },
});
window.intlTelInput(inputVacation, {
  onlyCountries: ['es', 'fr', 'it'],
  utilsScript: "/intl-tel-input/js/utils.js?1690975972744"
});
	
	
	
	
	  });