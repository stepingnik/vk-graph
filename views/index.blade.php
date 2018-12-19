
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Graph</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<h1></h1>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<center><h1>Анализ социальных связей ВКонтакте</h1></center>
<div class="container" >
    <div class="col-xs-3">

        <!--<div  style="margin-top: 50%; height: 306px; width: 150% ">-->


            <div class="panel panel-info">
                <div class="panel-heading">Правила</div>
                <div class="panel-body">
                    <ul>
                        <li><font color="red">Важно:</font> ваша страница должна быть видна незарегистрированным пользователям "Вконтакте"</li>
                        <li>Вводите ID только цифрами, например: 256485</li>
                        <li>ID может состоять из букв, если в нем нет цифр</li>


                    </ul>
                </div>
            </div>


        <!--</div>-->


    </div>
<div class="col-xs-5">
    <center>


    <form class="form" action="/one" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label for="email">ID</label>
            <input type="text" class="form-control" name="uid" placeholder="ID пользователя">
        </div>
        <div class="form-group" >
            <label for="pass">Текст</label>
            <label>
                <input name="res" value="0" type="hidden">
                <input type='checkbox' value = "1" name="res" class="myinput">
                <span></span>
            </label>
            <textarea  type="text" class="form-control" name="post_id" style="height:200px;" placeholder="Текст поста"></textarea>
        </div>
        <button data-toggle="modal" data-target=".bd-example-modal-sm" type="submit" class="btn btn-success" >Отправить</button>

    </form>
<!--
    <form class="form" action="/one" method="post">
        {{csrf_field()}}
        <input type="text" name="uid" placeholder="ID пользователя">
        <textarea name="post_id" placeholder="Текст поста"></textarea>
        <input type="submit" value="Отправить">
    </form>
    --->

    </center>
</div>
    <div class="col-xs-3"></div>
</div>

<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
            <center>Загрузка...</center><br>
                        <center><img src="25.gif" class="img-responsive"></center>
                </div>
            </div>
        </div>
    </div>
</div>
</body>


<style>
    label input {
        /*visibility: hidden;/* <-- hide the default checkbox, the rest is to hide and alllow tabbing, which display:none prevents */
        /*display:block;
        height:0;
        width:0;
        position:absolute;
        overflow:hidden;*/
    }
    label span {/* <-- style the artificial checkbox */
        height: 10px;
        width: 10px;
        border: 2px white;
        display: inline-block;
    }
    [type=checkbox]:checked + span {/* <-- style its checked state */
        background: white;
    }
</style>


</html>


