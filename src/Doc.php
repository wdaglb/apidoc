<?php
// +----------------------------------------------------------------------
// | apiDoc
// +----------------------------------------------------------------------
// | User: wzdon
// +----------------------------------------------------------------------
// | Author: king east <1207877378@qq.com>
// +----------------------------------------------------------------------

namespace ke\apidoc\src;


class Doc
{
    private $config = [];

    private $docs = [];

    private $patterns = [
        'controller'=>'^app\\\\(\w+)\\\\controller',
        'apiIgnore'=>'@apiIgnore',
        'groupIgnore'=>'@groupIgnore',
        'title'=>'\/\*\*[\$\s]+?\*\s*(\s|.+?)[\$\s]+?',
        'param'=>'@(?P<type>param|result|order|group)\s(?P<content>.*)',
        'name'=>'@apiName\s(.*)',
        'route'=>'@route\((.+?)\)',
        'path'=>'@path\s(.*)',
        'group'=>'@group\s(?P<content>.*)',
    ];

    private $order;

    public function __construct($config, $order, \ReflectionClass $reflectionClass)
    {
        $this->config = $config;
        $this->order = $order;
        $this->parse($reflectionClass);
    }


    protected function getPattern($key, $flag = '')
    {
        return '/' . $this->patterns[$key] . '/' . $flag;
    }


    protected function getTitle($comment)
    {
        $list = explode("\n", $comment);
        if (count($list) < 2) {
            return null;
        }
        $str = trim(str_replace('*', '', $list[1]));
        if (substr($str, 0, 1) === '@') {
            return null;
        }
        return $str;
    }


    private function parse(\ReflectionClass $reflectionClass)
    {
        if (!preg_match($this->getPattern('controller'), $reflectionClass->getNamespaceName())) {
            return null;
        }
//        if ($reflectionClass->getFileName() !== '/www/gxy_core/application/api/controller/v1/NewsController.php') {
//            return null;
//        }
        $group = null;
        $comment = $reflectionClass->getDocComment();
        if ($comment) {
            // 判断是否过滤
            if (preg_match($this->getPattern('apiIgnore'), $comment)) {
                return;
            }

            if (!preg_match($this->getPattern('groupIgnore'), $comment)) {

                // 获取分组名称
                $group['title'] = $this->getTitle($comment);

                if (preg_match($this->getPattern('name'), $comment, $match)) {
                    $group['name'] = $match[1];
                } else {
                    $group['name'] = str_replace('\\', '/', $reflectionClass->getName());
                }

                if (!is_null($group['title'])) {
                    // 获取参数
                    if (preg_match_all($this->getPattern('param'), $comment, $matchs, PREG_SET_ORDER)) {
                        foreach ($matchs as $match) {
                            switch ($match[1]) {
                                case 'order':
                                    $value = intval($match[2]);
                                    break;
                                default:
                                    $value = $match[2];
                                    break;
                            }
                            $group[$match[1]] = $value;
                        }

                    }
                } else {
                    // 获取分组
                    if (preg_match($this->getPattern('group'), $comment, $match)) {
                        $group['title'] = '@extend';
                        $group['extend'] = $match[1];
                    } else {
                        $group = null;
                    }
                }
            }
        }

        $methods = $reflectionClass->getMethods();
        $docs = [];
        foreach ($methods as $method) {
            $api = [];
            $this->order++;
            $comment = $method->getDocComment();
            // 判断是否过滤
            if (preg_match($this->getPattern('apiIgnore'), $comment)) {
                continue;
            }
            $api['title'] = $this->getTitle($comment);
            if (is_null($api['title'])) {
                continue;
            }
            if (preg_match($this->getPattern('name'), $comment, $match)) {
                $api['name'] = $match[1];
            } else {
                $api['name'] = str_replace('\\', '/', $reflectionClass->getName()) . '@' . $method->getName();
            }
            // 获取接口地址 类型
            if ($this->config['route']) {
                if (preg_match($this->getPattern('route'), $comment, $match)) {
                    $str = str_replace(['\'', ' '], ['', ''], $match[1]);
                    $routes = explode(',', $str);
                    $api['path'] = $routes[0];
                    $api['method'] = $routes[1] ?? 'any';
                }
            }

            if (!isset($api['path'])) {
                if (preg_match($this->getPattern('path'), $comment, $match)) {
                    $routes = explode(' ', $match[1]);
                    try {
                        $api['path'] = $routes[1];
                        $api['method'] = $routes[0] ?? 'any';
                    } catch (\Throwable $e) {
                        throw new \Exception('@path 结构错误');
                    }
                } else {
                    continue;
                }
            }

            $api['order'] = $this->order;
            // 获取参数
            if (preg_match_all($this->getPattern('param'), $comment, $matchs, PREG_SET_ORDER)) {
                $api = array_merge($api, $this->commentParse($api['method'], $matchs));
            }

            $docs[] = $api;
        }

        if (empty($docs)) {
            $this->docs = null;
            return;
        }
        if ($group) {
            $this->docs = $group;
            $this->docs['docs'] = $docs;
        } else {
            $this->docs = $docs;
        }
    }


    private function commentParse($method, $matchs)
    {
        $api = [];
        foreach ($matchs as $match) {
            if (!isset($match['type']) || !isset($match['content'])) {
                continue;
            }
            if ($match['type'] === 'order') {
                $api['order'] = intval($match['content']);
                continue;
            }
            if ($match['type'] === 'param') {
                $api['params'][] = $this->getParam($method, $match['content'], 0);
            } else if ($match['type'] === 'result') {
                $api['result'][] = $this->getParam($method, $match['content'], 1);
            }
        }
        return $api;
    }


    /**
     * 获取参数
     * @param $method
     * @param $str
     * @param $mode 0 请求参， 1 返回参
     */
    private function getParam($method, $str, $mode = 0)
    {
        $tmp = explode(' ', $str);
        $len = count($tmp);

        $params = [];

        $params['required'] = true;

        if ($mode === 0) {
            $params['in'] = (!$method || $method === 'get' || $method === 'any') ? 'query' : 'formdata';
        }

        if ($len === 1) {
            $params['type'] = 'string';
            $params['name'] = $tmp[0];
            $params['description'] = '';
        } else if ($len === 4) {
            $params['type'] = $tmp[0];

            if ($mode === 0) {
                $params['in'] = $tmp[1] ?? 'query';
            }

            $params['name'] = $tmp[2] ?? '';

            $params['description'] = $tmp[3] ?? '';
        } else {

            $params['type'] = $tmp[0];

            $params['name'] = $tmp[1] ?? '';

            $params['description'] = $tmp[2] ?? '';
        }
        $params['name'] = str_replace('$', '', $params['name']);
        if (substr($params['name'], 0, 1) === '*') {
            $params['name'] = substr($params['name'], 1);
            $params['required'] = false;
        }

        return $params;
    }


    public function result()
    {
        return [$this->order, empty($this->docs) ? false : $this->docs];
    }

}
