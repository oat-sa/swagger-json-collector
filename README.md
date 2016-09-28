# swagger-json-collector
Collects all the documentation JSON files for the Swagger 2.0 into new one

### Example of usage

```
$collector = new DocsCollector();
$doc = $collector->generate(['file1.json', 'file2.json']);

file_put_contents('swaggerDoc.json', json_encode($doc))
```
