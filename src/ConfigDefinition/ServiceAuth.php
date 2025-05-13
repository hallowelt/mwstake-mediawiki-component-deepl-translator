<?php

namespace MWStake\MediaWiki\Component\DeeplTranslator\ConfigDefinition;

use BlueSpice\ConfigDefinition\IOverwriteGlobal;
use BlueSpice\ConfigDefinition\SecretSetting;

class ServiceAuth extends SecretSetting implements IOverwriteGlobal {
	/**
	 *
	 * @return array
	 */
	public function getPaths() {
		$feature = static::FEATURE_SYSTEM;
		$ext = 'DeepL';
		$package = static::PACKAGE_PRO;
		return [
			static::MAIN_PATH_FEATURE . "/$feature/$ext",
			static::MAIN_PATH_PACKAGE . "/$package/$ext",
		];
	}

	/**
	 *
	 * @return string
	 */
	public function getLabelMessageKey() {
		return 'mwstake-component-deepl-translator-config-service-auth';
	}

	/**
	 * @return string
	 */
	public function getGlobalName() {
		return 'mwsgDeeplTranslateServiceAuth';
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->config->get( 'DeeplTranslateServiceAuth' );
	}
}
