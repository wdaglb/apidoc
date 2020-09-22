# 注释生成器

只是一个根据数据表字段、注释生成一段@result的字符串，偷懒神器

## MakeComment配置说明

参数 | 类型 | 说明
--- | --- | ---
parse | class namespace | 解析器,可选

```
$comment = (new MakeComment([...配置参数]))
    ->getComment($tablename // 表名,无需传入前缀);

print_r($comment);
```
