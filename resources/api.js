mws.deepltranslator = {
	translateText: function( text, fromLang, toLang ) {
		return $.ajax( {
			method: 'POST',
			url: mw.util.wikiScript( 'rest' ) + '/mws/v1/deepl/translate',
			contentType: 'application/json',
			data: JSON.stringify( {
				text: text,
				source_lang: fromLang,
				target_lang: toLang
			} ),
			dataType: 'json'
		} );
	}
};