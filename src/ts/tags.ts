import $ from 'jquery';

const tags = $('#tags');
const tagsInput = $('#tags-input');

let val;

$(tagsInput).on( 'input' , function(){

    val = $(this).val();

} )
    
const submitButtons = [13, 44, 59];

$(tagsInput).on( 'keypress' , (e) => {

    const curKey = e.which;

    if ( $.inArray( curKey, submitButtons) !== -1 ) {
        
        const newQuery = $(tagsInput).val().trim();

        const span = $(`<span></span>`);
        $(span).addClass('___pasInputUint__tag');
        $(span).text( newQuery );

        $(tagsInput).before(span);

        $(tagsInput).val('')

    }

} )