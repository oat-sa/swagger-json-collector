<?php
/**
 * Copyright (c) 2016.
 *
 * @author Alexander Zagovorichev <zagovorichev@gmail.com>
 */

namespace swaggerCollector;


class Swagger2_0
{

    private $requiredStructure = [
        "swagger" => "2.0",
        "info" => [
            'title' => true,
            'version' => true
        ],
        "basePath" => true,
        "paths" => true,
        "tags" => true
    ];

    public function validate(\stdClass $swagger)
    {
        return $this->check($this->requiredStructure, $swagger);
    }

    private function check(array $structure, \stdClass $object)
    {
        foreach ($structure as $key => $value) {
            if (!isset($object->$key)) {
                throw new RestApiDocsException('Property "' . $key . '" not found in the Swagger2.0 required structure');
            }

            if (is_array($value)) {

                if ($object->$key instanceof \stdClass) {
                    return self::check($value, $object->$key);
                }else{
                    return false;
                }

            } elseif (is_string($value) && !empty($value) && $object->$key !== $value) {
                throw new RestApiDocsException('In property "' . $key . '" for Swagger2.0 required structure should be value "'. $value .'"');
            }
        }
        return true;
    }
    
    public function append(\stdClass $docs, \stdClass $part)
    {
        try {
            $this->validate($part);
        } catch (RestApiDocsException $e) {
            throw new RestApiDocsException('Incorrect file structure');
        }

        // description
        $title = $part->info->title .' '. $part->info->version;
        $docs->info->description .= "\n\n## " . $title;
        if(isset($part->info->description)) {
            $docs->info->description .= "\n\n" . $part->info->description;
        }
        
        if (isset($part->externalDocs)) {
            $docs->info->description .= "\n\n[".$part->externalDocs->description."](".$part->externalDocs->url.")";
        }

        if (isset($part->securityDefinitions)) {
            $docs->securityDefinitions =
                isset($docs->securityDefinitions)
                    ? (object) array_merge((array) $docs->securityDefinitions, (array) $part->securityDefinitions)
                    : $part->securityDefinitions;
        }

        if (isset($part->definitions)) {
            $docs->definitions =
                isset($docs->definitions)
                    ? (object) array_merge((array) $docs->definitions, (array) $part->definitions)
                    : $part->definitions;
        }

        // add extension tag to all part paths
        $extensionTag = ['name' => $title, 'description' => 'Extension ' . $part->info->title];
        $partTags = [];
        foreach ($part->paths as $path => $body) {
            foreach ($body as $param => $obj) {
                
                $hasTag = false;
                if (isset($obj->tags)) {
                    foreach ($obj->tags as $tag) {
                        
                        if ( (is_object($tag) && $tag->name == $extensionTag['name'])
                            || $tag == $extensionTag['name']) {
                            $hasTag = true;
                            break;
                        } else {
                            $partTags[] = is_object($tag) ? '**' . $tag->name . '**' . ($tag->description ? ': ' . $tag->description : '') : $tag; 
                        }
                    }
                } 
                
                if (!$hasTag) {
                    $part->paths->$path->$param->tags = [$extensionTag['name']];
                }
            }
        }
        
        $tagDesc = [];
        foreach ($part->tags as $tag) {
            $tagDesc[$tag->name] = $tag->description;
        }
        
        // write all part tags into the documentation
        if(count($partTags)) {
            $partTags = array_unique($partTags);
            array_walk($partTags, function (&$val) use ($tagDesc) {
                if (isset($tagDesc[$val])) {
                    $val = $tagDesc[$val];
                }
            });
            $docs->info->description .= "\n\n####Operations:\n\n" . implode("\n   -", $partTags);
        }
        
        $docs->paths =
            isset($docs->paths)
                ? (object) array_merge((array) $docs->paths, (array) $part->paths)
                : $part->paths;

        $docs->tags = array_merge((array) $docs->tags, [$extensionTag]);

        return $docs;
    }
    
}
