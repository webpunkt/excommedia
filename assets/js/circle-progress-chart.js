jQuery( function ( $ ) {
    'use strict';

    $( '.tm-circle-progress-chart' ).waypoint( function () {
        // Fix for different ver of waypoints plugin.
        var self      = this.element ? this.element : $( this );
        var countHtml = $( this ).find( '.chart-number' );

        $( self ).find( '.chart' ).circleProgress( {
            startAngle: - Math.PI / 4 * 2,
            animation: {duration: 1700}
        } ).on( 'circle-animation-progress', function ( event, progress ) {
            countHtml.html( parseInt( (
                                          countHtml.data( 'max' )
                                      ) * progress ) + '<span>' + countHtml.data( 'units' ) + '</span>' );

        } );
    }, {
        offset: '90%',
        triggerOnce: true
    } );
} );
