## 注解

phpstorm安装插件PHP Annotations

声明接口分组
```
// 直接在class注释

@ApiGroup(
    path="api/news",
    title="文章"
)
```

声明接口
```
// 直接在method注释

@ApiDoc(
    path="",
    title="列表",
    description="获取文章列表",
    params={
     @ApiParam(name="page", type="number", description="页码"),
    },
    result={
     @ApiResult(
      name="list",
      description="列表数据",
      type="array",
      cols={
         @ApiResult(name="id", type="number", description="文章ID"),
     }),
     @ApiResult(name="total", type="number", description="数据总数")
    }
)
```

此文件不需要解析
```
@ApiIgnore()
```

> ApiGroup参数说明

参数|类型|必选|描述
---|---|---|---
path|string|是|分组地址
title|string|是|分组名称

> ApiDoc参数说明

参数|类型|必选|描述
---|---|---|---
path|string|是|接口地址,如果开头不为/则会继承group的路径
title|string|是|接口名称
description|string|否|接口描述
params|array<@ApiParam>|否|请求参数
result|array<@ApiResult>|否|返回参数
response|array<@ApiResponse>|否|响应描述

> ApiParam参数说明

参数|类型|必选|描述
---|---|---|---
name|string|是|参数名
in|string|否|参数类目：params,query,formdata
required|boolean|否|是否必选
description|string|否|参数说明
type|string|否|参数类型：number,string,boolean,array,float,object

> ApiResult参数说明

参数|类型|必选|描述
---|---|---|---
name|string|是|参数名
required|boolean|否|是否必选
description|string|否|参数说明
type|string|否|参数类型：number,string,boolean,array,float,object
cols|array<ApiParam>|否|下级参数

> ApiResponse参数说明

参数|类型|必选|描述
---|---|---|---
code|number|是|状态码
description|string|否|消息内容
