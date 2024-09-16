mws.deepltranslator = {
	translateText: function( text, fromLang, toLang ) {
		var dfd = $.Deferred();
		$.ajax( {
			method: 'POST',
			url: mw.util.wikiScript( 'rest' ) + '/mws/v1/deepl/translate',
			contentType: 'application/json',
			data: JSON.stringify( {
				text: text,
				source_lang: fromLang,
				target_lang: toLang
			} ),
			dataType: 'json'
		} ).done( function( data ) {
			dfd.resolve( data );
		} );

		return dfd.promise();
	}
};