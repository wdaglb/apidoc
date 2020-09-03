<?php
// +----------------------------------------------------------------------
// | apiDoc
// +----------------------------------------------------------------------
// | User: wzdon
// +----------------------------------------------------------------------
// | Author: king east <1207877378@qq.com>
// +----------------------------------------------------------------------

namespace ke\apidoc;


use Doctrine\Common\Annotations\AnnotationReader;
use ke\apidoc\parse\Thinkphp;
use ke\apidoc\src\Doc;
use ke\apidoc\src\Maker;

class Parse
{
    private $config = [
        "title"=>'XX接口文档',
        // 解析器
        'parse'=>Thinkphp::class,
        // 生产器
        'maker'=>Maker::class,
        // 控制器后缀
        'suffix'=>'Controller',
        // 文件保存路径
        'api'=>'api.json',
        // html文件路径
        'html'=>'api.html',
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

        AnnotationReader::addGlobalIgnoredName('route');

        $parse = new $this->config['parse']($this->config);

        $this->for2File($parse->result());

        if ($this->config['api']) {
            $parse->save($this->results);
        }

        if ($this->config['html']) {
            $maker = new $this->config['maker']($this->results, $this->config);
            $maker->save();
        }

        print_r('parse complete!  ' . round(microtime(true) - $time, 2) . 's' . PHP_EOL);
    }


    private function for2File($list)
    {
        foreach ($list as $value) {
            if (!class_exists($value)) {
                continue;
            }
            $reflClass = new \ReflectionClass($value);

            $res = $this->getReflection($reflClass);
            if ($res) {
                $this->results[] = $res;
            }
        }
    }


    private function getReflection($reflClass)
    {
        $doc = new Doc($reflClass);
        return $doc->result();
    }

}
