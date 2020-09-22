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
            $data_type = $column['data_type'];
            if (strpos($data_type, 'char') !== false || strpos($data_type, 'text') !== false || strpos($data_type, 'blob') !== false) {
                $data_type = 'string';
            } else if (strpos($data_type, 'int') !== false) {
                $data_type = 'int';
            } else if ($data_type === 'decimal' || $data_type === 'double') {
                $data_type = 'float';
            } else if (in_array($data_type, ['date', 'time', 'year', 'datetime', 'timestamp'])) {
                $data_type = 'string';
            }
            $comments .= sprintf('* @result %s %s %s', $data_type, $column['column_name'], $column['column_comment']) . PHP_EOL;
        }

        return $comments;
    }
}
