## ke/apidoc

安装

```
composer require ke/apidoc dev-master
```

[查看注解使用说明](./docs/README.md)

ThinkPHP使用

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
            'api'=>false,
            'html'=>App::getRootPath() . 'api.html',
        ]))->execute();
    }

}
```

命令行生成静态文档文件
```
php think apidoc
```
