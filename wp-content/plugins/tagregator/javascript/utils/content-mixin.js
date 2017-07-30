import moment from 'moment-timezone';

export default {
	getContent: function( data ) {
		return { __html: data };
	},

	getTimeDiff: function( date ) {
		return moment.tz( date, 'UTC' ).fromNow();
	},
};
