## Parse配置说明

参数 | 类型 | 说明
--- | --- | ---
title | string | 站点标题
parse | class namespace | 解析器,可选
maker | class namespace | 生产器,可选
template | string | 文档基础html模板(绝对路径),可选；模板使用{#变量名}替换数据
json | string/boolean | json保存的路径,设为false不会生成json文件
html | string/boolean | html保存的路径,设为false不会生成html文件
route | boolean | 是否开启@route解析

```
(new Parse([
    'title'=>'接口文档',
    'api'=>App::getRootPath() . 'api.json',
    'html'=>App::getRootPath() . 'api.html',
]))->execute();
```
