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

const postTypeDropdowns = $('.___pasInputUnit_postType select');

const dynamicPostTypeDropdown = ( select ) => {

    const val = $(select).val();

    const postTypeInputUnit = $(select).closest('.___pasInputUnit_postType');

    const postIDInputUnit = $( postTypeInputUnit ).siblings('.___pasInputUnit_postID');

    const selectors = $(postIDInputUnit).find(`select`);

    const targetSelector = $(postIDInputUnit).find(`select[data-dropdown-content="posts-${val}"]`);
    
    $(selectors).hide();

    $(targetSelector).show();

}

for (let i = 0; i < postTypeDropdowns.length; i++) {

    dynamicPostTypeDropdown( postTypeDropdowns[i] );
    
}


$(postTypeDropdowns).on( 'input' , function () {
    dynamicPostTypeDropdown( this );
})