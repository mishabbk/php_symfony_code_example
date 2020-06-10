import $ from 'jquery';

google.maps.event.addDomListener(window, 'load', initAutocomplete);

let autocomplete;

const componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    postal_code: 'short_name',
    locality: 'long_name',
};

function initAutocomplete() {
    const addressForm = document.getElementsByClassName('address-autocomplete');
    if (addressForm.length > 0) {
        autocomplete = new google.maps.places.Autocomplete($(addressForm).find('.route')[0], {types: ['geocode']});
        autocomplete.setComponentRestrictions({'country': ['fr']});
        autocomplete.setFields(['address_component']);
        autocomplete.addListener('place_changed', fillInAddress);
    }
}

function fillInAddress() {
    const place = autocomplete.getPlace();
    for (let component in componentForm) {
        document.getElementsByClassName(component)[0].value = '';
    }
    let val;
    for (let i = 0; i < place.address_components.length; i++) {
        let addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            val = place.address_components[i][componentForm[addressType]];
            document.getElementsByClassName(addressType)[0].value = (document.getElementsByClassName(addressType)[0].value + ' ' + val).trim();
        }
    }
}
