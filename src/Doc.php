<?php
// +----------------------------------------------------------------------
// | apiDoc
// +----------------------------------------------------------------------
// | User: wzdon
// +----------------------------------------------------------------------
// | Author: king east <1207877378@qq.com>
// +----------------------------------------------------------------------

namespace ke\apidoc\src;


use Doctrine\Common\Annotations\AnnotationReader;
use ke\apidoc\annotation\ApiDoc;
use ke\apidoc\annotation\ApiGroup;
use ke\apidoc\annotation\ApiIgnore;

class Doc
{
    private $docs = [];

    public function __construct(\ReflectionClass $reflectionClass)
    {
        $this->parse($reflectionClass);
    }

    private function parse(\ReflectionClass $reflectionClass)
    {
        $reader = new AnnotationReader();

        $ignore = $reader->getClassAnnotation($reflectionClass, 'KeApiIgnore');
        if ($ignore) {
            return;
        }

        $group = $reader->getClassAnnotations($reflectionClass);
        foreach ($group as $g) {
            if ($g instanceof ApiGroup) {
                $apiInfo['title'] = $g->title;
                $apiInfo['path'] = $g->path;
                $apiInfo['sort'] = $g->sort;
            }
        }
        $apiInfo['docs'] = [];

        $methods = $reflectionClass->getMethods();

        foreach ($methods as $method) {
            $res = $reader->getMethodAnnotations($method);
            if (!empty($res)) {
                foreach ($res as $param) {
                    if ($param instanceof ApiDoc) {
                        $doc = [];

                        if (substr($param->path, 0, 1) === '/') {
                            $doc['path'] = $param->path;
                        } else {
                            if ($param->path) {
                                $doc['path'] = ($apiInfo['path'] ?? '') . '/' . $param->path;
                            } else {
                                $doc['path'] = ($apiInfo['path'] ?? '');
                            }
                        }
                        if ($param->method) {
                            $doc['method'] = $param->method;
                        }
                        if ($param->title) {
                            $doc['title'] = $param->title;
                        }
                        if ($param->params) {
                            $doc['params'] = array_filter($param->params, function ($item) {
                                return $item;
                            });
                        }
                        if ($param->description) {
                            $doc['description'] = $param->description;
                        }
                        if ($param->result) {
                            $doc['result'] = $param->result;
                        }
                        if ($param->response) {
                            $doc['response'] = $param->response;
                        }
                    } else if ($param instanceof ApiIgnore) {
                        break;
                    }
                }
                if (isset($doc)) {
                    if (isset($apiInfo['path'])) {
                        $apiInfo['docs'][] = $doc;
                    } else {
                        $this->docs = array_merge($this->docs, $doc);
                    }
                }
            }
        }

        if (count($apiInfo['docs'])) {
            $this->docs = $apiInfo;
        }
    }


    public function result()
    {
        return empty($this->docs) ? false : $this->docs;
    }

}
