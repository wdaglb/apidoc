## 注解

声明接口分组
```
// 直接在class注释

/**
 * 分组名称(注释里的第一行)
 *
 * ↓ 如果增加这行则本class不解析
 * @apiIgnore
 * ↓ 分组标识,同一个标识会合并到一个分组
 * @group name
 * ↓ 菜单里的顺序,从小到大
 * @order 100
 */
class CLASSNAME {}
```

声明接口
```
/**
 * 接口名称(注释里的第一行)
 *
 * ↓ 如果增加这行则本method不解析
 * @apiIgnore
 *
 * ↓ 菜单里的顺序,从小到大
 * @order 10
 *
 * ↓ 接口路径设置(path模式)，与@route二选其一即可
 * @path 请求method 接口URL
 *
 * ↓ 接口请求参数
 * @param [datatype] [in] [*|-]name [description]
 *
 * ↓ 接口响应参数
 * @result [datatype] [*|-]name [description]
 *
 * ↓ 接口路径设置(路由注解模式), 需要开启route
 * @route('接口URL', '请求method')
 */
public function methodName() {}
```

> param 请求参数说明

参数 | 必选 | 说明
--- | --- | ---
datatype | 否 | 数据类型
in | 否 | 参数场景, 可选值：params,query,formdata
name | 是 | 参数名称：默认是必选参数，前缀添加*变为可选参数，前缀添加-可以增加缩进（每个“-”20px）
description | 否 | 参数说明


> result 响应参数说明

参数 | 必选 | 说明
--- | --- | ---
datatype | 否 | 数据类型
name | 是 | 参数名称：默认是必选参数，前缀添加*变为可选参数，前缀添加-可以增加缩进（每个“-”20px）
description | 否 | 参数说明


excample

```
/**
 * 资讯中心
 */
class NewsController
{
    /**
     * 文章列表
     *
     * @path get /api/v1/news
     *
     * @param int *page 页码
     * @param int *limit 每页数量
     * @param int *is_home 首页显示
     * @param int *cate_id 分类ID
     * @param int *is_recommend 推荐内容
     *
     * @result int id 文章ID
     * @result string title 文章标题
     * @result string summary 内容摘要
     * @result string thumb 缩略图
     * @result int status 状态：0未发布，1已发布
     * @result string update_time 发布时间
     * @result int view_count 浏览量
     * @result int is_recommend 是否推荐
     * @result int is_home 是否首页显示
     * @result int is_top 是否置顶
     * @result object cate 分类信息
     * @result int -id 分类ID
     * @result string -name 分类名称
     * @result int total 数据总量
     */
    public function index() {}



    /**
     * 文章详情
     *
     * @path get /api/v1/news/:id
     *
     * @param $id
     *
     * @result int id 文章ID
     * @result string title 文章标题
     * @result string summary 内容摘要
     * @result string thumb 缩略图
     * @result int status 状态：0未发布，1已发布
     * @result string update_time 发布时间
     * @result int view_count 浏览量
     * @result int is_recommend 是否推荐
     * @result int is_home 是否首页显示
     * @result int is_top 是否置顶
     * @result object cate 分类信息
     * @result int -id 分类ID
     * @result string -name 分类名称
     */
     public function read($id) {}
}
```