import React from 'react';

// Internal dependencies
import ContentMixin from '../../utils/content-mixin';

require( './style.scss' );

export default React.createClass({
	displayName: 'Tweet',
	mixins: [ ContentMixin ],

	render: function() {
		let url = 'https://twitter.com/';
		let item = this.props.item;
		if ( ! item ) {
			return null;
		}
		let author = item.itemMeta.author;
		let content = item.itemMeta.showExcerpt ? item.post_excerpt : item.post_content;

		let media = item.itemMeta.media.map( ( image, i ) => {
			let img;
			if ( 'image' === image.type ) {
				img = ( <img key={ i } src={ `${ image.url }:small` } alt="" /> );
			}
			return img;
		} );

		// todo maybe change star icon to heart -- https://wordpress.org/support/topic/twitter-stars-should-be-hearts/

		return (
			<div className={ item.itemMeta.cssClasses }>
				<a className="tggr-author-profile clearfix" href={ url + author.username } rel="nofollow">
					{ author.image && <img src={ author.image } alt="" className="tggr-author-avatar" /> }
					<span className="tggr-author-name">{ author.name }</span>
					<span className="tggr-author-username">@{ author.username }</span>
				</a>

				<div className="tggr-item-content">
					<div dangerouslySetInnerHTML={ this.getContent( content ) } />
					{ item.itemMeta.showExcerpt && <p><a href={ item.itemMeta.mediaPermalink } rel="nofollow">Read the rest of this tweet on Twitter</a></p> }

					{ media }
				</div>

				<ul className="tggr-actions">
					<li><a href={ `${ url }intent/tweet?in_reply_to=${ item.itemMeta.tweetId }` } rel="nofollow"><i className="icon-reply"></i> <span>Reply</span></a></li>
					<li><a href={ `${ url }intent/retweet?tweet_id=${ item.itemMeta.tweetId }` } rel="nofollow"><i className="icon-retweet"></i> <span>Retweet</span></a></li>
					<li><a href={ `${ url }intent/favorite?tweet_id=${ item.itemMeta.tweetId }` } rel="nofollow"><i className="icon-star"></i> <span>Favorite</span></a></li>
				</ul>

				<a href={ item.itemMeta.mediaPermalink } rel="nofollow" className="tggr-timestamp">
					{ this.getTimeDiff( item.post_date_gmt ) }
				</a>

				<img className="tggr-source-logo" src={ tggrData.logos.twitter } alt="Twitter" />
			</div>
		);
	}
});
