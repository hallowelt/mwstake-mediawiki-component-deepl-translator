<?php

namespace MWStake\MediaWiki\Component\DeeplTranslator;

use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use Wikimedia\ParamValidator\ParamValidator;

class SupportedLanguagesHandler extends SimpleHandler {

	/** @var DeepLTranslator */
	private DeepLTranslator $translator;

	/**
	 * @param DeepLTranslator $translator
	 */
	public function __construct( DeepLTranslator $translator ) {
		$this->translator = $translator;
	}

	/**
	 * @return Response
	 * @throws HttpException
	 */
	public function execute() {
		$validated = $this->getValidatedParams();

		$status = $this->translator->getSupportedLanguages( $validated['type'] );

		if ( $status->isOK() ) {
			return $this->getResponseFactory()->createJson( $status->getValue() );
		} else {
			throw new HttpException( $status->getMessage(), 400 );
		}
	}

	public function getParamSettings() {
		return [
			'type' => [
				static::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => 'source'
			]
		];
	}
}
