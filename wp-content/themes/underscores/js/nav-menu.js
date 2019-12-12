( function(){

    var container, dropdown;
    var windowWidth = window.innerWidth;

    container = document.getElementsByClassName( 'main-navigation' );
   
    // console.log( windowWidth );
    if ( !container ) {
        return;
    }

    dropdown = document.getElementsByClassName( 'dropdown-menu' );

    if ( !dropdown ) {
        return
    }

    for( let i = 0; i < dropdown.length; i++ ) {
        var right = ( dropdown[i].getBoundingClientRect().width - ( windowWidth - dropdown[i].getBoundingClientRect().left ) ) > 0;
        
        // console.log( dropdown[i].offsetWidth );
        // console.log( windowWidth - dropdown[i].offsetLeft );
        // console.log( right );

        if ( right ) {
            dropdown[i].style.right = 0;
        }

    }

})()
