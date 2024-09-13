<?php

use MediaWiki\MediaWikiServices;
use MWStake\MediaWiki\Component\DeeplTranslator\DeepLTranslator;

return [

	'MWStake.DeepLTranslator' => static function ( MediaWikiServices $services ) {
		return new DeepLTranslator(
			new GlobalVarConfig( 'mwsg' ),
			$services->getHttpRequestFactory()
		);
	}
];
