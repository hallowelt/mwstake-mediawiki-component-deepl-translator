<?php

if ( defined( 'MWSTAKE_MEDIAWIKI_COMPONENT_DEEPL_TRANSLATOR_VERSION' ) ) {
	return;
}

define( 'MWSTAKE_MEDIAWIKI_COMPONENT_DEEPL_TRANSLATOR_VERSION', '1.1.6' );

MWStake\MediaWiki\ComponentLoader\Bootstrapper::getInstance()->register( 'deepl-translator', static function () {
	$GLOBALS['wgServiceWiringFiles'][] = __DIR__ . '/ServiceWiring.php';

	$GLOBALS['mwsgDeeplTranslateServiceAuth'] = '';
	$GLOBALS['mwsgDeeplTranslateServiceUrl'] = 'https://api-free.deepl.com/v2';

	$GLOBALS['wgResourceModules']['ext.mws.deepltranslator'] = [
		'scripts' => [
			'api.js'
		],
		'localBasePath' => __DIR__ . '/resources'
	];

	$restFilePath = wfRelativePath( __DIR__ . '/rest-routes.json', $GLOBALS['IP'] );

	$GLOBALS['wgRestAPIAdditionalRouteFiles'][] = $restFilePath;
	$GLOBALS['wgMessagesDirs']['mwstake-component-deepl-translator'] = __DIR__ . '/i18n';

	$GLOBALS['mwsgManifestRegistryOverrides']['BlueSpiceFoundationConfigDefinitionRegistry'] = [
		'merge' => [
			'DeeplTranslateServiceAuth' =>
				"MWStake\\MediaWiki\\Component\\DeeplTranslator\\ConfigDefinition\\ServiceAuth::getInstance",
			'DeeplTranslateServiceUrl' =>
				"MWStake\\MediaWiki\\Component\\DeeplTranslator\\ConfigDefinition\\ServiceUrl::getInstance"
		]
	];
} );
