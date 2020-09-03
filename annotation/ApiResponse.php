<?php
// +----------------------------------------------------------------------
// | apiDoc
// +----------------------------------------------------------------------
// | User: wzdon
// +----------------------------------------------------------------------
// | Author: king east <1207877378@qq.com>
// +----------------------------------------------------------------------

namespace ke\apidoc\annotation;


/**
 * Class KeApiResponse
 * @package ke\apidoc\annotation
 * @Annotation
 */
class ApiResponse
{
    /**
     * 状态码
     * @var int
     */
    public $code;

    /**
     * 描述信息
     * @var string
     */
    public $description;
}
