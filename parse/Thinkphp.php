<?php
// +----------------------------------------------------------------------
// | 种菜郎服务端
// +----------------------------------------------------------------------
// | User: wzdon
// +----------------------------------------------------------------------
// | Author: king east <1207877378@qq.com>
// +----------------------------------------------------------------------

namespace ke\apidoc\parse;


use think\facade\App;

class Thinkphp
{
    private $config = [];

    private $list = [];

    public function __construct($config)
    {
        $this->config = $config;

        $path = App::getAppPath();

        $list = $this->get_list($path);
        foreach ($list as $file) {
            $tmp = str_replace([$path, '.php', '/'], ['', '', '\\'], $file);

            $this->list[] = 'app\\' . $tmp;
        }
    }


    /**
     * 获取php文件列表
     * @param $dir
     * @return array
     */
    private function get_list($dir)
    {
        static $ret;
        $files = glob($dir . '*');
        if (is_array($files)) {
            foreach ($files as $file) {
                if (is_file($file) && preg_match(sprintf('/application\/(.+?)%s$/', $this->config['suffix'] . '\\.php'), $file, $match)) {
                    $ret[] = $file;
                } else {
                    $this->get_list($file . '/');
                }
            }
        }
        return $ret;
    }


    public function result()
    {
        return $this->list;
    }


    public function save($result)
    {
        file_put_contents(App::getRootPath() . $this->config['api'], json_encode($result, JSON_UNESCAPED_UNICODE));
    }

}
