# swagger-json-collector
Combines Swagger docs from various sources

### Example of usage

Valid input formats are files that contains JSON, or JSON strings or PHP arrays. Formats can be mixed within the input array.

```php
$collector = new DocsCollector();


$doc = $collector->generate(['swagger.json', '{ "swagger": "2.0",...}'], [ 'swagger' => '2.0',...]]);

file_put_contents('swaggerDoc.json', json_encode($doc))
```
