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

const tagsContainer = $('body').find('.___pasInputUnit__tags');

console.log( tagsContainer );

const tags = $(tagsContainer).find('.___pasInputUint__tag');

const prepareQueries = ( query ) =>{
    
    const allQueriesInput   = $(query).siblings('.all-queries');

    $(allQueriesInput).val('')

    const querySibs         = $(query).siblings('.___pasInputUint__tag');

    let preparedQueriesArray = [];

    for (let i = 0; i < querySibs.length; i++) {

        if( $(querySibs[i]).val().length > 0 ){

            preparedQueriesArray.push( $(querySibs[i]).val() );

        }
        
    }

    if( $(query).val().length > 0 ){

        preparedQueriesArray.push( $(query).val() );
    }

    const preparedQueriesString = preparedQueriesArray.join(', ');


    $(allQueriesInput).val( preparedQueriesString );

}

for (let i = 0; i < tagsContainer.length; i++) {
    console.log( tagsContainer[i] );    
}


$(tags).on( 'input' , function(){

    const queryContainer = this;

    prepareQueries( queryContainer );

} );
