<?php
// +----------------------------------------------------------------------
// | 种菜郎服务端
// +----------------------------------------------------------------------
// | User: wzdon
// +----------------------------------------------------------------------
// | Author: king east <1207877378@qq.com>
// +----------------------------------------------------------------------

namespace ke\apidoc\annotation;


use Doctrine\Common\Annotations\Annotation\Enum;

/**
 * Class KeApiParam
 * @package ke\apidoc\annotation
 * @Annotation
 */
class ApiParam
{
    /**
     * 参数名
     * @Required
     * @var string
     */
    public $name;

    /**
     * @Enum({"params", "query", "formdata"})
     */
    public $in;

    /**
     * @var bool
     */
    public $required;

    /**
     * 描述
     * @var string
     */
    public $description;

    /**
     * 数据类型
     * @Enum({"number", "string", "boolean", "array", "float", "object"})
     */
    public $type;

}