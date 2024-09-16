## Configuration

```php
$GLOBALS['mwsgDeeplTranslateServiceAuth'] = 'your-auth-key-here';
$GLOBALS['mwsgDeeplTranslateServiceUrl'] = 'https://api.deepl.com/v2/translate';
```

## Usage

### Direct service
```php
$translator = \MediaWiki\MediaWikiServices::getInstance()->getService( 'MWStake.DeepLTranslator' );
$translation = $translator->translateText( 'Hello, world!', 'EN', 'DE' );
```

### API

```
curl -X POST "https://your-wiki/rest.php/mws/v1/deepl/translate" -H "Content-Type: application/json" --data '{"text":"Hello, world!","source_lang":"EN","target_lang":"DE"}'
```

### JS

```javascript
mws.deepltranslator.translateText( 'Hello, world!', 'EN', 'DE' ).then( function( translation ) {
    console.log( translation );
} );
```