import React from 'react';
import isEqual from 'lodash/isEqual';

// Internal
import API from '../../utils/api';
import MediaStore from '../../stores/media-store';

// Components
import Tweet from '../tweet';
import Instagram from '../instagram';
import Flickr from '../flickr';
import Google from '../google';

require( './style.scss' );

var _interval;

/**
 * Determines if the top of an element is visible in the viewport
 *
 * @param {string} element
 *
 * @returns {boolean}
 */
function isScrolledIntoView( element ) {
	return element.getBoundingClientRect().top >= 0;
}

export default React.createClass({
	displayName: 'Stream',

	getInitialState: function() {
		return {
			fetching: false,
			data: MediaStore.getItems(),
		}
	},

	getItems: function() {
		const intervalSeconds = tggrData.refreshInterval || 30;
		if ( ! this.state.fetching && ( isScrolledIntoView( this.refs.container ) || this.state.data.length < 1 ) ) {
			this.setState( { fetching: true } );
			API.getItems();
			if ( 'undefined' === typeof _interval ) {
				_interval = setInterval( this.getItems, intervalSeconds * 1000 );
			}
		}
	},

	componentDidMount: function() {
		MediaStore.addChangeListener( this._onChange );
		this.getItems();
	},

	componentDidUpdate: function( prevProps ) {
		if ( ! isEqual( prevProps, this.props ) ) {
			clearInterval( _interval );
			this.getItems();
		}
	},

	componentWillUnmount: function() {
		MediaStore.removeChangeListener( this._onChange );
		clearInterval( _interval );
	},

	_onChange: function() {
		this.setState( {
			fetching: false,
			data: MediaStore.getItems(),
		} );
	},

	render: function() {
		let layout = tggrData.layout || 'three-column';
		let items = this.state.data.map( function( item, i ) {
			let rendered;

			switch ( item.post_type ) {
				case 'tggr-tweets':
					rendered = ( <Tweet key={ i } item={ item } layout={ layout } /> );
					break;
				case 'tggr-instagram':
					rendered = ( <Instagram key={ i } item={ item } layout={ layout } /> );
					break;
				case 'tggr-flickr':
					rendered = ( <Flickr key={ i } item={ item } layout={ layout } /> );
					break;
				case 'tggr-google':
					rendered = ( <Google key={ i } item={ item } layout={ layout } /> );
					break;
				default:
					rendered = ( <div key={ i }>No handler for this media type: { item.post_type }</div> );
					break;
			}

			return rendered;
		} );

		if ( items.length < 1 && ! this.state.fetching ) {
			items = ( <div><p>No results found for {tggrData.hashtags} (yet).</p></div> );
		}

		return (
			<div className="tggr-stream" ref='container'>
				{ this.state.fetching ?
					<div className='tggr-loading' style={ { height: '20px' } }>
						<i className="icon icon-spinner icon-spin"></i>
						<span className='assistive-text screen-reader-text'>Loading More</span>
					</div> :
					null
				}

				<div className="tggr-media-items" style={ { marginTop: this.state.fetching ? '15px' : '35px' } }>
					{ items }
				</div>
			</div>
		);
	}
});
