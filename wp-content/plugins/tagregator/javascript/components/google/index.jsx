import React from 'react';

// Internal dependencies
import ContentMixin from '../../utils/content-mixin';

require( './style.scss' );

export default React.createClass({
	displayName: 'Google',
	mixins: [ ContentMixin ],

	render: function() {
		let url = 'https://plus.google.com/';
		let item = this.props.item;
		if ( ! item ) {
			return null;
		}
		let author = item.itemMeta.author;
		let content = item.itemMeta.showExcerpt ? item.post_excerpt : item.post_content;

		let media = item.itemMeta.media.map( function( image, i ) {
			let img;
			if ( 'image' === image.type ) {
				img = ( <img key={ i } src={ `${ image['small_url'] }` } alt="" /> );
			}
			return img;
		} );

		return (
			<div className={ item.itemMeta.cssClasses }>
				<a className="tggr-author-profile clearfix" href={ url + author.userId } rel="nofollow">
					{ author.image && <img src={ author.image } alt="" className="tggr-author-avatar" /> }
					<span className="tggr-author-name">{ author.name }</span>
					<span className="tggr-author-username">@{ author.username }</span>
				</a>

				<div className="tggr-item-content">
					<div dangerouslySetInnerHTML={ this.getContent( content ) } />
					{ media }
					{ item.itemMeta.showExcerpt && <p><a href={ item.itemMeta.mediaPermalink } rel="nofollow">Read the rest of this post on Google+</a></p> }
				</div>

				<a href={ item.itemMeta.mediaPermalink } rel="nofollow" className="tggr-timestamp">
					{ this.getTimeDiff( item.post_date_gmt ) }
				</a>

				<img className="tggr-source-logo" src={ tggrData.logos.google } alt="Google" />
			</div>
		);
	}
});
