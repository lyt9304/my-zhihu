<!DOCTYPE html>
<html lang="en" ng-app="my-zhihu">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>我的知乎</title>

    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/js/base.js"></script>
</head>
<body>
    <div class="navbar">
        导航栏
        <a href="#" ui-sref="home">首页</a>
        <a href="#" ui-sref="login">登录</a>
    </div>
    <div>
        <div class="page" ui-view>
            <!--这里的内容会被template替换-->
        </div>
    </div>
</body>

<script type="text/ng-template" id="home.tpl">
    <div class="home"></div>
</script>

<script type="text/ng-template" id="login.tpl">
    <div class="login"></div>
</script>
</html>
