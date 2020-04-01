$('#add-image').click(function() {

    // je recupere le numero de futur champs que je vais creer 
     const index = +$('#widgets-counter').val() ;

     // je recupere le prototype des entrees
     const templ = $('#annonce_images').data('prototype').replace(/__name__/g, index) ;

     // j'injecte ce code au sein de la DIV
     $('#annonce_images').append(templ)  ;

     $('#widgets-counter').val(index + 1) ;

     // Je gere le bouton Supprimer 
     handleDeleteButtons() ;
}) ;


function handleDeleteButtons()  {

    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target
        $(target).remove()  ;
    }) ;
}

function updateCounter()  {
         const count = +$('#annonce_images div.form-group').length  ;
     $('#widgets-counter').val(count) ;
}

updateCounter() ;

handleDeleteButtons()  ;