<!DOCTYPE html>
<html ng-app="myApp">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <link rel="icon" type="image/png" href="/_assets/img/b2b-tools.ico"/>
    <script type="text/javascript" src="/_assets/jquery/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script type="text/javascript" src="/_assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/_assets/ace-builds/src-noconflict/ace.js"></script>
    <script type="text/javascript" src="/_assets/main.js"></script>

    <link type="text/css" rel="stylesheet" href="/_assets/bootstrap/css/bootstrap.min.css"/>
    <link type="text/css" rel="stylesheet" href="/_assets/bootstrap/css/bootstrap-theme.min.css"/>
    <link type="text/css" rel="stylesheet" href="/_assets/main.css"/>
    <link type="text/css" rel="stylesheet" href="/_assets/font-awesome-4.2.0/css/font-awesome.min.css"/>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

    <link rel="icon" href="/favicon.ico"/>

    <title>Query Mail - The simple way to create fancy statistics or alerting emails</title>
</head>
<body>

<header id="mainHeader">
    <a href="/"><img src="/_assets/img/querymail.png" style="width:80px;"/> Query Mail</a>
</header>

<?= $content; ?>

<div class="popinFake panel panel-default" style="display:none;">
    <div class="panel-heading">Informations <a href="#" class="closePopinFake"><i
                class="fa fa-times pull-right"></i></a></div>
    <div class="panel-body" id="popinFakeContent"></div>
</div>

</body>
</html>