<?php

use BlueSpice\TranslationTransfer\DeepLTranslator;
use MediaWiki\MediaWikiServices;

return [

	'MWStake.DeepLTranslator' => static function ( MediaWikiServices $services ) {
		return new DeepLTranslator(
			$services->getConfigFactory()->makeConfig( 'mwsg' ),
			$services->getHttpRequestFactory()
		);
	}
];
