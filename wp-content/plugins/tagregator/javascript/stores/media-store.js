import { EventEmitter } from 'events';
import assign from 'object-assign';
import AppDispatcher from '../dispatcher/dispatcher';
import AppConstants from '../constants/constants';
import find from 'lodash/find';
import unionBy from 'lodash/unionBy';

var CHANGE_EVENT = 'change';

/**
 * Our working item list, read-only
 * @type {array}
 * @protected
 */
var _items = [];

/**
 * Load this array into our items list
 *
 * @param {array} data - array of items, pulled from API
 */
function _loadItems( data ) {
	var maxItems = 100;

	data.sort( function( a, b ) {
		let aDate = new Date( a.date );
		let bDate = new Date( b.date );
		return bDate - aDate;
	} );

	_items = unionBy( data, _items, 'ID' );

	// Only the most recent items are relevant to the user, and a large page could drain browser resources
	if ( _items.length > maxItems ) {
		_items = _items.splice( 0, maxItems );
	}
}

let MediaStore = assign( {}, EventEmitter.prototype, {
	emitChange: function() {
		this.emit( CHANGE_EVENT );
	},

	addChangeListener: function( callback ) {
		this.on( CHANGE_EVENT, callback );
	},

	removeChangeListener: function( callback ) {
		this.removeListener( CHANGE_EVENT, callback );
	},

	/**
	 * Get the items list
	 *
	 * @returns {array}
	 */
	getItems: function() {
		return _items;
	},

	/**
	 * Get the current item
	 *
	 * @returns {array}
	 */
	getItem: function( id ) {
		var item = find( _item, function( _item ) {
			return id === _item.id;
		} );
		item = item || {};
		return item;
	},

	// Watch for store actions, and dispatch the above functions as necessary.
	dispatcherIndex: AppDispatcher.register( function( payload ) {
		var action = payload.action; // this is our action from handleViewAction

		switch ( action.actionType ) {
			case AppConstants.REQUEST_ITEMS_SUCCESS:
				_loadItems( action.data );
				break;
		}

		MediaStore.emitChange();

		return true;
	} )

} );

export default MediaStore;
