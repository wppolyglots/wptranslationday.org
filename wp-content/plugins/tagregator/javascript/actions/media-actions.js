import AppDispatcher from '../dispatcher/dispatcher';
import AppConstants from '../constants/constants';

export default {
	/**
	 * @param  {array}  posts
	 */
	fetch: function( posts ) {
		AppDispatcher.handleViewAction( {
			actionType: AppConstants.REQUEST_ITEMS_SUCCESS,
			data: posts
		} );
	},
}
