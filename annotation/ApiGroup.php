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
 * Class KeApiGroup
 * @package ke\apidoc\annotation
 * @Annotation
 * @Target({"CLASS"})
 */
class ApiGroup
{
    /**
     * 路径,分组下所有path都会继承该path
     * @var string
     */
    public $path;

    /**
     * 排序，从小到大
     * @var int
     */
    public $sort = 0;

    /**
     * 分组名称
     * @var string
     */
    public $title;

}