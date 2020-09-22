<?php


namespace ke\apidoc;


use ke\apidoc\parse\Thinkphp;
use ke\apidoc\src\Maker;

class MakeComment
{
    private $config = [
        // 解析器
        'parse'=>Thinkphp::class,
    ];

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
    }


    public function getComment($table)
    {
        $parse = new $this->config['parse']($this->config);
        $columns = $parse->getDbColumns($table);

        $comments = '';
        foreach ($columns as $column) {
            $comments .= sprintf('* @result %s %s %s', $column['data_type'], $column['column_name'], $column['column_comment']) . PHP_EOL;
        }

        return $comments;
    }
}
