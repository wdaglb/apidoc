<?php
// +----------------------------------------------------------------------
// | apiDoc
// +----------------------------------------------------------------------
// | User: wzdon
// +----------------------------------------------------------------------
// | Author: king east <1207877378@qq.com>
// +----------------------------------------------------------------------

namespace ke\apidoc\parse;


use think\Db;
use think\facade\App;
use think\facade\Config;

class Tp60
{
    private $config = [];

    private $list = [];

    public function __construct($config)
    {
        $this->config = $config;

        if (isset($config['path'])) {
            $app_path = App::getAppPath();
            $path = $app_path . $config['path'];
            if (substr($path, -1) !== '/') {
                $path .= '/';
            }

            $list = $this->getList($path);
            if (is_array($list)) {
                foreach ($list as $file) {
                    $tmp = str_replace([$app_path, '.php', '/'], ['', '', '\\'], $file);

                    $this->list[] = 'app\\' . $tmp;
                }
            }
        }
    }


    /**
     * 获取php文件列表
     * @param $dir
     * @return array
     */
    private function getList($dir)
    {
        static $ret;
        $files = glob($dir . '*');
        if (is_array($files)) {
            foreach ($files as $file) {
                if (is_file($file) && preg_match(sprintf('/app\/(.+?)%s$/', $this->config['suffix'] . '\\.php'), $file, $match)) {
                    $ret[] = $file;
                } else {
                    $this->getList($file . '/');
                }
            }
        }
        return $ret;
    }


    /**
     * 获取数据表字段列表
     * @param string $table 表名,不含前缀
     * @return array
     */
    public function getDbColumns($table)
    {
        $pre = Config::get('database.prefix');
        $database = Config::get('database.database');

        $sql = "SELECT column_name,column_comment,data_type FROM information_schema.columns WHERE table_name='%s' AND table_schema='%s'";
        return Db::query(sprintf($sql, $pre . $table, $database));
    }


    public function result()
    {
        return $this->list;
    }

}
