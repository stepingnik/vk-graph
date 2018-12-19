<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<form class="form" action="/two" method="post">
    {{csrf_field()}}
    Количество лайков и репостов<br>
    <input type="text" name="uid" placeholder="ID пользователя">
    <textarea name="post_id" placeholder="Текст поста"></textarea>
    <input type="text" name="lim" placeholder="Пороговое значение">
    <input type="text" name="lvl" placeholder="lvl">
    <input type="submit" value="Отправить">

</form>

<div class="col-xs-9">
<!---
    <h4 class="page-header">
        История запросов <small>из базы данных</small>
    </h4>
    <table class="table table-striped">
        <thead>
        <?php
        //use Illuminate\Support\Facades\DB;
        //$results = DB::select('select * from Request WHERE group_stat = "two"');
        ?>
        <tr>
            <th>
                id
            </th>
            <th>
                id пользователя
            </th>
            <th>
                Текст
            </th>
            <th>
                Статус
            </th>
        </tr>
        <?php
        function function_echo(){
            return view('two');
        }/*
        ?>
        @for($i=0;$i<count($results);$i++)
            <tr>
                <td>{{$results[$i]->id}}</td>
                <td>{{$results[$i]->user_id}}</td>
                <td>{{$results[$i]->text}}</td>
                <td>{{$results[$i]->status}}</td>
                <!--<td><input type="submit" name="create" value="Вывод"></td>-->
            </tr>
        @endfor
        <?
        if($create){
        function_echo();
        }*/
        ?>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

