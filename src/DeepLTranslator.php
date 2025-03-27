<?php

namespace MWStake\MediaWiki\Component\DeeplTranslator;

use Config;
use Exception;
use FormatJson;
use MediaWiki\Http\HttpRequestFactory;
use MWHttpRequest;
use Status;

class DeepLTranslator {
	protected const AUTH_HEADER_NAME = 'Authorization';
	protected const AUTH_HEADER_PREFIX = 'DeepL-Auth-Key';

	protected const PARAM_TEXT = 'text';
	protected const PARAM_SOURCE_LANG = 'source_lang';
	protected const PARAM_TARGET_LANG = 'target_lang';
	protected const PARAM_SPLIT_SENTENCES = 'split_sentences';
	protected const PARAM_PRESERVE_FORMATTING = 'preserve_formatting';
	protected const PARAM_FORMALITY = 'formality';
	protected const PARAM_TAG_HANDLING = 'tag_handling';
	protected const PARAM_IGNORE_TAGS = 'ignore_tags';

	/** @var Config */
	protected $config;

	/** @var HttpRequestFactory */
	protected $requestFactory;

	/**
	 *
	 * @param Config $config
	 * @param HttpRequestFactory $requestFactory
	 */
	public function __construct( Config $config, HttpRequestFactory $requestFactory ) {
		$this->config = $config;
		$this->requestFactory = $requestFactory;
	}

	/**
	 * @param string $text
	 * @param string $sourceLang
	 * @param string $targetLang
	 * @return Status
	 */
	public function translateText( string $text, string $sourceLang, string $targetLang, array $options = [] ) {
		$status = Status::newGood();
		if ( !$this->isConfigured() ) {
			$status->fatal( 'DeepL is not configured' );
			return $status;
		}
		try {
			$req = $this->getRequest( $text, $sourceLang, $targetLang, $options );
			$status->merge( $req->execute(), true );
		} catch ( Exception $e ) {
			$status->fatal( $e->getMessage() );
		}

		if ( !$status->isOK() ) {
			return $status;
		}
		$res = FormatJson::decode( $req->getContent() );
		if ( empty( $res->translations ) || empty( $res->translations[0]->text ) ) {
			$status->fatal( "invalid translation for title $text" );
			return $status;
		}

		return Status::newGood( $res->translations[0]->text );
	}

	/**
	 * @return Status
	 */
	public function getSupportedLanguages( string $type = 'source' ): Status {
		$status = Status::newGood();
		if ( !$this->isConfigured() ) {
			$status->fatal( 'DeepL is not configured' );
			return $status;
		}
		try {
			$data = array_merge(
				$this->makeOptions(),
				[ 'postData' => [
					'type' => $type
				] ]
			);
			$req = $this->requestFactory->create( $this->makeUrl( 'languages' ), $data );

			$this->setAuthHeader( $req );

			$status->merge( $req->execute(), true );
		} catch ( Exception $e ) {
			$status->fatal( $e->getMessage() );
		}
		if ( !$status->isOK() ) {
			return $status;
		}

		return Status::newGood( FormatJson::decode( $req->getContent() ) );
	}

	/**
	 *
	 * @param string $text
	 * @param string $sourceLang
	 * @param string $targetLang
	 * @return array
	 */
	protected function makePostData( $text, $sourceLang, $targetLang ) {
		return [
			static::PARAM_SOURCE_LANG => $sourceLang,
			static::PARAM_TARGET_LANG => $targetLang,
			static::PARAM_TEXT => $text,
			static::PARAM_TAG_HANDLING => 'xml',
			static::PARAM_IGNORE_TAGS => 'deepl:ignore,translation:ignore'
		];
	}

	/**
	 * @return array
	 */
	protected function makeOptions() {
		return [
			'timeout' => 120,
			'method' => 'post',
			'sslVerifyHost' => 0,
			'followRedirects' => true,
			'sslVerifyCert' => false,
		];
	}

	/**
	 * Set authentication header.
	 * See: https://developers.deepl.com/docs/getting-started/auth#authentication
	 *
	 * @param MWHttpRequest $req
	 * @return void
	 */
	protected function setAuthHeader( MWHttpRequest $req ): void {
		$req->setHeader(
			static::AUTH_HEADER_NAME,
			static::AUTH_HEADER_PREFIX . ' ' . $this->config->get( 'DeeplTranslateServiceAuth' )
		);
	}

	/**
	 * @param string $text
	 * @param string $sourceLanguage
	 * @param string $targetLanguage
	 * @return MWHttpRequest
	 */
	public function getRequest(
		string $text, string $sourceLanguage, string $targetLanguage, array $options = []
	): MWHttpRequest {
		$data = array_merge_recursive(
			$this->makeOptions(),
			$options,
			[
				'postData' => $this->makePostData(
					$text,
					strtoupper( $sourceLanguage ),
					strtoupper( $targetLanguage )
				)
			],
		);
		$req = $this->requestFactory->create( $this->makeUrl( 'translate' ), $data );

		$this->setAuthHeader( $req );

		return $req;
	}

	/**
	 * @return bool
	 */
	public function isConfigured(): bool {
		return $this->config->get( 'DeeplTranslateServiceAuth' ) && $this->config->get( 'DeeplTranslateServiceUrl' );
	}

	/**
	 *
	 * @return string
	 */
	protected function makeUrl( string $endpoint = 'translate' ) {
		$url = $this->config->get( 'DeeplTranslateServiceUrl' );
		if ( substr( $url, -strlen( $endpoint ) ) === $endpoint ) {
			// B/C
			return $url;
		}
		$url = rtrim( $url, '/' );
		return $url . '/' . $endpoint;
	}
}
