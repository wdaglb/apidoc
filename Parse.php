<?php
// +----------------------------------------------------------------------
// | apiDoc
// +----------------------------------------------------------------------
// | User: wzdon
// +----------------------------------------------------------------------
// | Author: king east <1207877378@qq.com>
// +----------------------------------------------------------------------

namespace ke\apidoc;



use ke\apidoc\parse\Thinkphp;
use ke\apidoc\src\Doc;
use ke\apidoc\src\Maker;

class Parse
{
    private $config = [
        "title"=>'XX接口文档',
        // 目录
        'path'=>'',
        // 解析器
        'parse'=>Thinkphp::class,
        // 生产器
        'maker'=>Maker::class,
        // 文档模板
        'template'=>__DIR__ . '/template/index.html',
        // 控制器后缀
        'suffix'=>'Controller',
        // 文件保存路径
        'json'=>'api.json',
        // html文件路径
        'html'=>'api.html',
        // 是否开启@route解析
        'route'=>false,
    ];

    private $results = [];


    public function __construct($config)
    {
        $this->config = array_merge($this->config, $config);
    }


    public function execute()
    {
        print_r('parse....' . PHP_EOL);
        $time = microtime(true);

        $parse = new $this->config['parse']($this->config);

        $this->for2File($parse->result());

        $i = 0;
        $volume = [];
        foreach ($this->results as $key=>&$row) {
            $i++;
            $volume[$key] = $row['order'] ?? ($_SERVER['REQUEST_TIME'] + $i);
            // 子文档
            if (isset($row['docs'])) {
                $kv = [];
                foreach ($row['docs'] as $k=>&$v) {
                    $i++;
                    $kv[$k] = $v['order'] ?? ($_SERVER['REQUEST_TIME'] + $i);
                    unset($v['order']);
                }
                unset($v);
                array_multisort($kv, SORT_ASC, $row['docs']);
            }
            unset($row['order']);
        }
        unset($row);
        array_multisort($volume, SORT_ASC, $this->results);

        $groups = [];
        foreach ($this->results as &$result) {
            if (isset($result['group'])) {
                $groups[$result['group']] = &$result['docs'];
            }
        }
        unset($result);

        // 转移分组
        foreach ($this->results as &$result) {
            if ($result['title'] === '@extend') {
                if (isset($groups[$result['extend']])) {
                    $groups[$result['extend']] = array_merge($groups[$result['extend']], $result['docs']);
                }
            }
        }
        unset($result);

        $this->results = array_values(array_filter($this->results, function ($item) {
            return $item['title'] !== '@extend';
        }));

        if (!empty($this->config['maker'])) {
            $maker = new $this->config['maker']($this->results, $this->config);

            if ($this->config['html']) {
                $maker->asHtml();
            }
            if ($this->config['json']) {
                $maker->asJson();
            }
        }

        print_r('parse complete!  ' . round(microtime(true) - $time, 2) . 's' . PHP_EOL);
    }


    private function for2File($list)
    {
        $order = 0;
        foreach ($list as $value) {
            if (!class_exists($value)) {
                continue;
            }
            $order++;
            $reflClass = new \ReflectionClass($value);

            list($order, $res) = $this->getReflection($order, $reflClass);
            if (!empty($res)) {
                if (isset($res['title'])) {
                    $this->results[] = $res;
                } else {
                    $this->results = array_merge($this->results, $res);
                }
            }
        }
    }


    private function getReflection($order, $reflClass)
    {
        $doc = new Doc($this->config, $order, $reflClass);
        return $doc->result();
    }

}
