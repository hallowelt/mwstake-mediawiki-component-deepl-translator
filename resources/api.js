mws.deepltranslator = {
	translateText: function ( text, fromLang, toLang ) {
		const dfd = $.Deferred();
		$.ajax( {
			method: 'POST',
			url: mw.util.wikiScript( 'rest' ) + '/mws/v1/deepl/translate',
			contentType: 'application/json',
			data: JSON.stringify( {
				text: text,
				source_lang: fromLang, // eslint-disable-line camelcase
				target_lang: toLang // eslint-disable-line camelcase
			} ),
			dataType: 'json'
		} ).done( ( data ) => {
			dfd.resolve( data );
		} );

		return dfd.promise();
	}
};
