import $ from 'jquery'

const entryOpenableHeaders = $('.___pasTable__entry_openable .___pasTable__entryHeader');

$(entryOpenableHeaders).on( 'click' , function() {

    const parentSelector = $(this).closest('.___pasTable__entry');
    const parentSiblings = $(parentSelector).siblings('.___pasTable__entry');

    if( $(parentSelector).hasClass('open') ){

        $(parentSelector).removeClass('open')

    } else {
        
        $(parentSelector).addClass('open')

    }
    
    $(parentSiblings).removeClass('open');

    

    // $('.___pasTable__entry').removeClass('open');


    // $(this).closest('.___pasTable__entry').toggleClass('open');


} )