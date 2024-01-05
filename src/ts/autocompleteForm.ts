import $ from 'jquery'

const autocompleteOptions = $('body').find('.___pasAutocomplete__options');

console.log( autocompleteOptions );

$(window).click(function() {

    $( autocompleteOptions ).hide();
    
});

