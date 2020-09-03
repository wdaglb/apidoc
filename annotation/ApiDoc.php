<?php
// +----------------------------------------------------------------------
// | apiDoc
// +----------------------------------------------------------------------
// | User: wzdon
// +----------------------------------------------------------------------
// | Author: king east <1207877378@qq.com>
// +----------------------------------------------------------------------

namespace ke\apidoc\annotation;



use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * Class KeApiDoc
 * @package ke\apidoc\annotation
 * @Annotation
 */
class ApiDoc
{
    /**
     * 接口地址
     * @Required()
     * @var string
     */
    public $path;

    /**
     * @Enum({"get", "post", "delete", "put", "patch", "options", "head"})
     */
    public $method;

    /**
     * 接口名称
     * @var string
     */
    public $title;

    /**
     * 描述
     * @var string
     */
    public $description;

    /**
     * @var array
     */
    public $params;

    /**
     * @var array
     */
    public $response;

    /**
     * @var array
     */
    public $result;

}
