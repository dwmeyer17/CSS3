( function () {
	document.querySelectorAll( '.hero-slideshow' ).forEach( function ( slideshow ) {
		var slides = slideshow.querySelectorAll( '.hero-slide' );
		var interval = parseInt( slideshow.dataset.interval, 10 ) || 5000;
		var current = 0;

		if ( slides.length < 2 || window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			return;
		}

		setInterval( function () {
			slides[ current ].classList.remove( 'is-active' );
			current = ( current + 1 ) % slides.length;
			slides[ current ].classList.add( 'is-active' );
		}, interval );
	} );
} )();
