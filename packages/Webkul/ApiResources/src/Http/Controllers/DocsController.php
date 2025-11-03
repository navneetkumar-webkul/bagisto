<?php

namespace Webkul\ApiResources\Http\Controllers;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Paths;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Symfony\Component\Serializer\SerializerInterface;

class DocsController extends Controller
{
    public function __construct(private readonly OpenApiFactoryInterface $openApiFactory, private readonly SerializerInterface $serializer) {}

    public function ui(Request $request, string $area): Response
    {
        $jsonUrl = url(rtrim($request->getPathInfo(), '/').'/openapi.json');

        $html = <<<HTML
                    <!doctype html>
                    <html>
                    <head>
                        <meta charset="utf-8" />
                        <meta name="viewport" content="width=device-width, initial-scale=1" />
                        <title>API docs - {$area}</title>
                        <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@4/swagger-ui.css" />
                    </head>
                    <body>
                        <div id="swagger-ui"></div>
                        <script src="https://unpkg.com/swagger-ui-dist@4/swagger-ui-bundle.js"></script>
                        <script>
                            window.onload = function() {
                                SwaggerUIBundle({
                                url: '{$jsonUrl}',
                                dom_id: '#swagger-ui',
                                });
                            };
                        </script>
                    </body>
                    </html>
                HTML;

        return new Response($html, 200, ['Content-Type' => 'text/html']);
    }

    public function json(Request $request, string $area): Response
    {
        $context = ['request' => $request, 'spec_version' => '3.0.0'];

        $openApi = $this->openApiFactory->__invoke($context);

        $areaPath = rtrim('/'.trim($area, '/'), '/');

        $paths = $openApi->getPaths()->getPaths();

        $filtered = new Paths;

        foreach ($paths as $path => $pathItem) {
            if ((function_exists('str_starts_with') && str_starts_with($path, $areaPath)) || strpos($path, $areaPath) === 0) {
                $filtered->addPath($path, $pathItem);
            }
        }

        $openApi = $openApi->withPaths($filtered);

        $usedTagNames = [];
        $usedSchemaNames = [];
        foreach ($filtered->getPaths() as $path => $pathItem) {

            if (method_exists($pathItem, 'getOperations')) {
                foreach ($pathItem->getOperations() as $operation) {
                    $opTags = method_exists($operation, 'getTags') ? $operation->getTags() : [];
                    if ($opTags === null) {
                        continue;
                    }
                    foreach ($opTags as $t) {
                        $usedTagNames[$t] = true;
                    }

                    if ($operation && method_exists($operation, 'getSummary')) {
                        $opSummary = $operation->getSummary();
                        $usedSchemaNames[$opSummary] = true;
                    }
                }
            } else {
                $methods = ['get', 'post', 'put', 'patch', 'delete', 'options', 'head', 'trace'];
                foreach ($methods as $m) {
                    $getter = 'get'.ucfirst($m);
                    if (method_exists($pathItem, $getter)) {
                        $operation = $pathItem->{$getter}();
                        if ($operation && method_exists($operation, 'getTags')) {

                            $opTags = $operation->getTags() ?: [];
                            foreach ($opTags as $t) {
                                $usedTagNames[$t] = true;
                            }
                        }

                        if ($operation && method_exists($operation, 'getSummary')) {
                            $opSummary = $operation->getSummary();
                            $usedSchemaNames[$opSummary] = true;
                        }
                    }
                }
            }
        }

        if (method_exists($openApi, 'getTags') && is_array($openApi->getTags())) {
            $filteredTags = [];
            foreach ($openApi->getTags() as $tag) {
                if (method_exists($tag, 'getName') && isset($usedTagNames[$tag->getName()])) {
                    $filteredTags[] = $tag;
                }
            }
            if (! empty($filteredTags) && method_exists($openApi, 'withTags')) {
                $openApi = $openApi->withTags($filteredTags);
            }
        }

        if (method_exists($openApi, 'getComponents')) {
            $schemas = $openApi->getComponents()->getSchemas();

            if ($schemas instanceof \ArrayObject) {
                $filteredSchemas = new \ArrayObject;
                foreach ($schemas as $schemaName => $schema) {
                    if (isset($usedSchemaNames[$schemaName])) {
                        $filteredSchemas[$schemaName] = $schema;
                    }
                }
            }

            if (! empty((array) $filteredSchemas) && method_exists($openApi, 'withComponents') && method_exists($openApi->getComponents(), 'withSchemas')) {
                $openApi = $openApi->getComponents()->withSchemas($filteredSchemas);
            }
        }

        $json = $this->serializer->serialize($openApi, 'jsonopenapi', ['spec_version' => '3.0.0']);

        if (strpos($json, '"openapi"') !== false) {
            $json = preg_replace('/("openapi"\s*:\s*)"3\.[0-9]+\.[0-9]+"/', '$1"3.0.0"', $json);
        }

        return new Response($json, 200, ['Content-Type' => 'application/vnd.openapi+json']);
    }
}
