# 基于TP5进行的扩展的基础框架

## 使用
### composer.json
下面是一个完整的文件
```
{
  "name": "yueer-pet/auth",
  "description": "the auth module of yueer-pet",
  "type": "module",
  "authors": [
    {
      "name": "JerryChaox",
      "email": "jerrychaox8406@gmail.com"
    }
  ],
  "repositories":[
    {
      "type":"git",
      "url": "https://dev.tencent.com/u/JerryChaox/p/mofeng-tp5-base/git"
    }
  ],
  "require": {
    "php": ">= 7.1.0",
    "mofeng/tp5/framework": "dev-master"
  },
  "autoload": {
    "psr-4": {
      "app\\auth\\": "application/auth"
    }
  }
}
```
由于该库不在公有仓库上，必须要有以下配置片段并拥有pull权限才能下载本仓库
```
"repositories":[
    {
      "type":"git",
      "url": "https://dev.tencent.com/u/JerryChaox/p/mofeng-tp5-base/git"
    }
],
```

初次安装composer后，先运行以下命令

```composer config -g repo.packagist composer https://packagist.laravel-china.org```

运行此命令下载

```composer install```

### 命名空间

如果项目拆分成了多个composer模块，则需要额外配置命名空间，和上面一样：
```
"autoload": {
    "psr-4": {
      "app\\auth\\": "application/auth"
    }
}
```
这个表示将application/auth/绑定到app\\auth\\空间下, 例如application/auth/HelloWorld.php，必须配置命名空间为：namespace app\auth;

如果没有进行模块拆分，则直接绑定app\\命名空间即可：
```
"autoload": {
    "psr-4": {
      "app\\": "application"
    }
}
```
