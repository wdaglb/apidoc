<?php
// +----------------------------------------------------------------------
// | apiDoc
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


    public function asHtml()
    {
        $html = file_get_contents($this->config['template']);

        $vars = array_merge($this->config, [
            'docs'=>json_encode($this->result, JSON_UNESCAPED_UNICODE)
        ]);

        $html = preg_replace_callback('/{#(\w+)}/', function ($match) use($vars) {
            return $vars[$match[1]];
        }, $html);

        file_put_contents($this->config['html'], $html);
    }


    public function asJson()
    {
        file_put_contents($this->config['api'], json_encode($this->result, JSON_UNESCAPED_UNICODE));
    }

}
