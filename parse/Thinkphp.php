<?php
// +----------------------------------------------------------------------
// | apiDoc
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

        $app_path = App::getAppPath();
        $path = $app_path . $config['path'];
        if (substr($path, -1) !== '/') {
            $path .= '/';
        }

        $list = $this->get_list($path);
        if (is_array($list)) {
            foreach ($list as $file) {
                $tmp = str_replace([$app_path, '.php', '/'], ['', '', '\\'], $file);

                $this->list[] = 'app\\' . $tmp;
            }
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

}
