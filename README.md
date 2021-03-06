## ke/apidoc

一个0侵入性的php接口文档生成助手，默认支持Thinkphp5.1/Thinkphp6.0，需要支持更多，自行配置解析器即可(根据./parse/Thinkphp.php修改，很简单)。

安装

```
composer require ke/apidoc
或
composer require ke/apidoc dev-master
```

1. [查看注解使用说明](./docs/README.md)
2. [查看Parse完整配置](./docs/parse.md)
3. [查看注释生成器说明](./docs/comment.md)

ThinkPHP使用注解文档

```
// command.php
return [
    \app\command\ApiDoc::class,
];

// app/command/ApiDoc.php
<?php
namespace app\command;


use ke\apidoc\Parse;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\App;

class ApiDoc extends Command
{
    public function configure()
    {
        $this->setName('apidoc');
    }


    public function execute(Input $input, Output $output)
    {
        (new Parse([
            'title'=>'XX接口文档',
            'api'=>false, // 如果需要json列表，赋予一个路径就可以
            'html'=>App::getRootPath() . 'api.html', // 设为false则不会生成html
        ]))->execute();
    }

}
```

命令行生成静态文档文件
```
php think apidoc
```

![QQ截图20200903144519](./docs/QQ截图20200903144519.png "QQ截图20200903144519.png")



ThinkPHP数据表字段生成@result注释片段

```
// command.php
return [
    \app\command\ApiDocComment::class,
];

// app/command/ApiDocComment.php
<?php
namespace app\command;


use ke\apidoc\MakeComment;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\App;

class ApiDocComment extends Command
{
    public function configure()
    {
        $this->setName('apidoc:comment')
             ->addArgument('table', Argument::REQUIRED)
             ->addOption('prefix', 'p', Option::VALUE_OPTIONAL, '字段前缀');
    }


    public function execute(Input $input, Output $output)
    {
        $table = $input->getArgument('table');
        $prefix = $input->getOption('prefix');
        $info = (new MakeComment())->getComment($table, $prefix);
        $output->writeln('comment content:');
        $output->writeln($info);
    }

}
```

命令行生成静态文档文件
```
php think apidoc:comment health_school
or
# 使用-字段前缀
php think apidoc:comment health_school -p-
```

![QQ截图20200922183038](./docs/QQ截图20200922183038.png "QQ截图20200922183038.png")
