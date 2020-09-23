# @result注释片段生成器

只是一个根据数据表字段、注释生成一段@result的字符串，偷懒神器;

生成的字符串，虽然不一定100%可用，但是减少了大部分工作效率，所以copy字符串后直接微调即可。

## MakeComment配置说明

参数 | 类型 | 说明
--- | --- | ---
parse | class namespace | 解析器,可选

```
$comment = (new MakeComment([...配置参数]))
    ->getComment($tablename // 表名,无需传入前缀, $prefix = '字段前缀');

print_r($comment);
```
