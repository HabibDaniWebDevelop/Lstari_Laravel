jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
    return this.flatten().reduce( function ( a, b ) {
        if ( typeof a === 'string' ) {
            a = a.replace(/[^\d.-]/g, '') * 1;
        }
        if ( typeof b === 'string' ) {
            b = b.replace(/[^\d.-]/g, '') * 1;
        }
 
        return a + b;
    }, 0 );
} );



// jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
//     return this.reduce(function (a, b) {
//         var x = parseFloat(a) || 0;
//         var y = parseFloat(b) || 0;
//         return x + y;
//     });
// });