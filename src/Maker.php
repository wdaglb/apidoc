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

    private $file = '';

    public function __construct($result, $file)
    {
        $this->result = $result;
        $this->file = $file;
    }


    public function save()
    {
        $html = file_get_contents(__DIR__ . '/../template/index.html');
        $html = str_replace('{#docs}', json_encode($this->result, JSON_UNESCAPED_UNICODE), $html);

        file_put_contents($this->file, $html);
    }

}
