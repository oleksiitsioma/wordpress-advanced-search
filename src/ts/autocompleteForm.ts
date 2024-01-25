import $ from 'jquery'

const autocompleteOptions = $('body').find('.___wasAutocomplete__options');

console.log( autocompleteOptions );

$(window).click(function() {

    $( autocompleteOptions ).hide();
    
});

