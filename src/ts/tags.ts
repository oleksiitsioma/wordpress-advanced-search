import $ from 'jquery';

// const tags = $('#tags');
// const tagsInput = $('#tags-input');

// let val;

// $(tagsInput).on( 'input' , function(){

//     val = $(this).val();

// } )
    
// const submitButtons = [13, 44, 59];



// $(tagsInput).on( 'keypress' , (e) => {

//     const curKey = e.which;

//     if ( $.inArray( curKey, submitButtons) !== -1 ) {
        
//         const newQuery = $(tagsInput).val().trim();

//         const span = $(`<span></span>`);
//         $(span).addClass('___pasInputUint__tag');
//         $(span).text( newQuery );

//         $(tagsInput).before(span);

//         $(tagsInput).val('')

//     }

// } )

const tagsContainer = $('.___pasInputUnit__tags');

const tags = $(tagsContainer).find('.___pasInputUint__tag');

$(tags).on( 'change' , function(){

    const sibQueries = $(this).siblings('.___pasInputUint__tag');

    const allQueriesInput = $(this).siblings('all-queries');

    let sibQueriesArray = [];

    let sibQueriesString = '';

    for (let i = 0; i < sibQueries.length; i++) {

        sibQueriesArray.push( $(sibQueries[i]).val() );

    }

    sibQueriesString = sibQueriesArray.join( ', ' );

    $(allQueriesInput).val(sibQueriesString);
    
})