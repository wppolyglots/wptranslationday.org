/* global jQuery */

/**
 * Internal dependencies
 */
import MediaActions from '../actions/media-actions';

var _get = function( url, data ) {
	return jQuery.ajax( {
		url: url,
		data: data,
		dataType: 'json'
	} );
};

export default {
	// Get a list of tweets according to args criteria
	getItems: function( args ) {
		let url = `${tggrData.ApiUrl}tagregator/v1/items`;

		args          = args || {};
		args.hashtags = tggrData.hashtags.split( ',' );

		jQuery.when(
			_get( url, args )
		).done( function( data, status, request ) {
			MediaActions.fetch( data );
		} );
	},
};
