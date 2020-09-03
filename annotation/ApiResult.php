<?php
// +----------------------------------------------------------------------
// | 种菜郎服务端
// +----------------------------------------------------------------------
// | User: wzdon
// +----------------------------------------------------------------------
// | Author: king east <1207877378@qq.com>
// +----------------------------------------------------------------------

namespace ke\apidoc\annotation;


use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ApiResult
 * @package ke\apidoc\annotation
 * @Annotation
 */
class ApiResult
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var boolean
     */
    public $required = true;

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

    /**
     * 字段
     * @var array
     */
    public $cols;

}
