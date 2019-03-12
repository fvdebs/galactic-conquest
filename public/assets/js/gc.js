( function ( $ ) {
    // Initialize Slidebars
    var controller = new slidebars();
    controller.init();

    $( '.toggle-id-1' ).on( 'click', function ( event ) {
        // Stop default action and bubbling
        event.stopPropagation();
        event.preventDefault();

        // Toggle the Slidebar with id 'id-1'
        controller.toggle( 'id-1' );
    } );

    $( 'a[href^="#"]' ).on( 'click', function( event ) {
        event.preventDefault();

        var target = $( this ).attr( 'href' );

        $( 'html, body' ).animate( {
            scrollTop: target.offset().top
    }, 1000 );
    } );

    $('a[href^="#"]').on('click', function(event) {
        event.preventDefault();

        var target = $( this ).attr( 'href' );

        $('[canvas="container"]').animate({
            scrollTop: target.offset().top
        }, 1000);
    });

} ) ( jQuery );