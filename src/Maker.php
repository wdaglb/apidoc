<?php
// +----------------------------------------------------------------------
// | 种菜郎服务端
// +----------------------------------------------------------------------
// | User: wzdon
// +----------------------------------------------------------------------
// | Author: king east <1207877378@qq.com>
// +----------------------------------------------------------------------

namespace ke\apidoc\src;


class Maker
{
    private $result = [];

    private $config = [];

    public function __construct($result, $config)
    {
        $this->result = $result;
        $this->config = $config;
    }


    public function save()
    {
        $html = file_get_contents(__DIR__ . '/../template/index.html');

        $vars = array_merge($this->config, [
            'docs'=>json_encode($this->result, JSON_UNESCAPED_UNICODE)
        ]);

        $html = preg_replace_callback('/{#(\w+)}/', function ($match) use($vars) {
            return $vars[$match[1]];
        }, $html);

        file_put_contents($this->config['html'], $html);
    }

}
