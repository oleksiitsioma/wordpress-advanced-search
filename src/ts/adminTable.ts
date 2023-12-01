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

} )

const postTypeDropdowns = $('select[data-dropdown-content="post-types"]');

const dynamicPostTypeDropdown = ( select ) => {

    const val = $(select).val();

    const currentTableEntry = $(select).closest('.___pasTable__entry');

    const targetSelectorContainer = $(currentTableEntry).find('.___pasTable__column[data-column-content="post-dropdowns"]')

    const selectors = $(targetSelectorContainer).find(`select`);

    const targetSelector = $(targetSelectorContainer).find(`select[data-dropdown-content="${val}-posts"]`);
    
    $(selectors).hide();

    $(targetSelector).show();

}

dynamicPostTypeDropdown( postTypeDropdowns );

$(postTypeDropdowns).on( 'input' , function () {
    dynamicPostTypeDropdown( this );
})