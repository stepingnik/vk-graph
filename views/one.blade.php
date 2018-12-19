<!DOCTYPE html>
<meta charset="utf-8">

<script src="https://d3js.org/d3.v3.min.js"></script>
<script src="assets/d3.tip.v0.6.3.js"></script>
<link href="assets/bootstrap.css" rel="stylesheet">
<script src="assets/jquery.min.js"></script>
<script src="assets/bootstrap.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type='text/javascript' src="http://bost.ocks.org/mike/fisheye/fisheye.js?0.0.3"> </script>

<script src="assets/in3.js"></script>
<link href="assets/in.css" rel="stylesheet">

<body >
<!--<div class="panel panel-success">
    <div class="panel-heading">Результаты</div>
    <div class="panel-body">
        <ul>
            <li>Ближе к центру расположены люди, которые вероятно составляют круг общения пользователя</li>
            <li>При нажатии на пользователя можно увидеть его силу связи</li>
            <li>Анализ проводился по общедоступным данным за последние несколько месяцев</li>



        </ul>
    </div>
</div>-->
<center>

<div class="container">
    <table class="table table-bordered" style="width:30%">
        <thead>
        <tr >
            <th style="width:16%">a</th>
            <th style="width:16%">bw</th>
            <th style="width:16%">bs</th>
            <th style="width:16%">k</th>
            <th style="width:16%">n</th>
            <th style="width:16%">P(t)</th>
        </tr>
        </thead>
        <tbody >
        <tr>
            <td> {{ $a }}</td>
            <td> {{ $bw }}</td>
            <td> {{ $bs }}</td>
            <td> {{ $j }}</td>
            <td> {{ $m }}</td>
            <td>{{ $p }} </td>
        </tr>
        </tbody>
    </table>
</div>

<div>
    <strong id="popover_content_wrapper" style="display: none" >
        <span id="check"><img src="check.png" alt="Пост" title="Пост присутствует" style="width:15px;height:15px"></span>
        <span class="content" id="img-container"></span>
        <strong><span class='value'></span></strong><br>
        Связь: <span class='connection'></span><br>
        Рейтинг: <span class='rating'></span>
    </strong>
</div>
<script>
    go();
</script>
</center>
</body>

