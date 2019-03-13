( function ( $ ) {
    // Initialize Slidebars
    var controller = new slidebars();
    controller.init();

    $('.toggle-id-1').on( 'click', function ( event ) {
        // Stop default action and bubbling
        event.stopPropagation();
        event.preventDefault();

        // Toggle the Slidebar with id 'id-1'
        controller.toggle( 'id-1' );
    } );

    // close all active on content click.
    $('#sidebar-content-wrapper').on( 'click', function ( event ) {
        if (controller.getActiveSlidebar() ) {
            event.preventDefault();
            event.stopPropagation();
            controller.close();
        }
    });

    // close all active sidebars on window resize
    window.onresize = function(event) {
        if (controller.getActiveSlidebar() ) {
            event.preventDefault();
            event.stopPropagation();
            controller.close();
        }
    };

} ) ( jQuery );