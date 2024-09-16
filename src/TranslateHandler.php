<?php

namespace MWStake\MediaWiki\Component\DeeplTranslator;

use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use Wikimedia\ParamValidator\ParamValidator;

class TranslateHandler extends SimpleHandler {

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
		$validated = $this->getValidatedBody();

		$status = $this->translator->translateText(
			$validated['text'],
			$validated['source_lang'],
			$validated['target_lang']
		);

		if ( $status->isOK() ) {
			return $this->getResponseFactory()->createJson( $status->getValue() );
		} else {
			throw new HttpException( $status->getMessage(), 400 );
		}
	}

	/**
	 * @param string $contentType
	 * @return JsonBodyValidator|null
	 */
	public function getBodyValidator( $contentType ) {
		if ( $contentType !== 'application/json' ) {
			return null;
		}
		return new JsonBodyValidator( [
			'text' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true
			],
			'source_lang' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true
			],
			'target_lang' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true
			],
		] );
	}
}
