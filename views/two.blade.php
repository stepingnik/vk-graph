<!DOCTYPE html>
<meta charset="utf-8">

<script src="https://d3js.org/d3.v3.min.js"></script>
<script src="assets/d3.tip.v0.6.3.js"></script>
<link href="assets/bootstrap.css" rel="stylesheet">
<script src="assets/jquery.min.js"></script>
<script src="assets/bootstrap.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type='text/javascript' src="http://bost.ocks.org/mike/fisheye/fisheye.js?0.0.3"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/seedrandom/2.4.3/seedrandom.min.js"></script>


<script src="assets/in2.js"></script>
<script src="assets/in.js"></script>

<link href="assets/in.css" rel="stylesheet">
<input type="hidden" id="json_name" value="{{ $json_name }}">
<body >
<script>
    var action = 1;
    function viewSomething() {
        if (action == 1) {
            startGraph2();startGraph();
            action = 2;
        } else {
            d3.select("svg").remove();
            d3.select("svg").remove();
            action = 1;
        }
    }
</script>


<div class="container" >
    <table class="table table-bordered" style="width:30%">
        <thead>
        <tr >
            <strong><th style="width:16%">Likes</th></strong>
            <strong><th style="width:16%">Reposts</th></strong>
            <strong><th style="width:16%">Model
                  @if(count($resultUser)>count($l))
                        {{$manCount = count($resultUser)}}
                    @else
                        {{$manCount = count($l)}}
                    @endif
                </th></strong>
            <strong><th style="width:16%">% = {{$proc}}</th></strong>
        </tr>
        </thead>

        <tbody >
        @for($i=0;$i<$manCount;$i++)
        <tr>
            <td>@if($i<count($l))
                    {{$l[$i]}}
                @endif</td>
            <td> @if($i<count($c))
                    {{$c[$i]}}
                @endif </td>

             <td>@if($i<count($resultUser))
                     {{$resultNames[$i]}}
                (id{{$resultUser[$i]}})
                     p={{$resultP[$i]}}
                     @endif
            </td>
        </tr>
        @endfor

        </tbody>
    </table>
    <button onclick = "viewSomething()">Graphs</button>
</div>
<div>
    <strong id="popover_content_wrapper" style="display: none" >
        <span class="content" id="img-container"></span>
        <strong><span class='value'></span></strong><br>
        Вероятность: <span class='rating'></span>
    </strong>
</div>
</body>

