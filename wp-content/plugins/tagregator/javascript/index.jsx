import React from 'react';
import ReactDOM from 'react-dom';
import Stream from './components/stream';

// Query DOM for all widget wrapper divs
let streams = document.querySelectorAll( 'div[data-hashtag]' );
streams = Array.prototype.slice.call( streams );

// Iterate over the DOM nodes and render a React component into each node
streams.forEach( function( wrapper ) {
	ReactDOM.render(
		<Stream />,
		wrapper
	);
} );
