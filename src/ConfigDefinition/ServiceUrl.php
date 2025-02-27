<?php

namespace MWStake\MediaWiki\Component\DeeplTranslator\ConfigDefinition;

use BlueSpice\ConfigDefinition\IOverwriteGlobal;
use BlueSpice\ConfigDefinition\StringSetting;

class ServiceUrl extends StringSetting implements IOverwriteGlobal {
	/**
	 *
	 * @return array
	 */
	public function getPaths() {
		$feature = static::FEATURE_CONTENT_STRUCTURING;
		$ext = 'DeepL';
		$package = static::PACKAGE_PRO;
		return [
			static::MAIN_PATH_FEATURE . "/$feature/$ext",
			static::MAIN_PATH_EXTENSION . "/$ext/$feature",
			static::MAIN_PATH_PACKAGE . "/$package/$ext",
		];
	}

	/**
	 *
	 * @return string
	 */
	public function getLabelMessageKey() {
		return 'mwstake-component-deepl-translator-config-service-url';
	}

	/**
	 *
	 * @return string
	 */
	public function getHelpMessageKey() {
		return 'mwstake-component-deepl-translator-config-service-url-help';
	}

	/**
	 * @return string
	 */
	public function getGlobalName() {
		return 'mwsgDeeplTranslateServiceUrl';
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->config->get( 'DeeplTranslateServiceUrl' );
	}
}
