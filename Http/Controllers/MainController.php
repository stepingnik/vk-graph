<?php

namespace App\Http\Controllers;

use App\VKCore;
use App\TestCore;
use Illuminate\Http\Request;

use App\Http\Requests;

class MainController extends Controller
{
    public function index()
    {

    	return view('index');
    }

    public function indextest()
    {

        return view('indextest');
    }


    public function test(Request $r)
    {
        global $l,$c,$json_name,$resultUser, $resultP,$proc,$resultNames;
        $access_token = '02d18a93993563abbccaf8d6fe1e399fa4f16189c221d6ac16a528ebc3800b1159a5ccb8132b2103e21e4';
        $params = $r->except('_token');
        (new TestCore())->test($params['uid']);
        //return view('test');


        /*        $json_name = (new VKCore())->second($params['uid'], $params['post_id'],$params['lim']);
        return view('two',compact(['l', 'c', 'json_name']));*/

    }

    public function one(Request $r)
    {
        global $a2,$bw2,$bs2,$j2,$m2,$p2;

    	$access_token = '02d18a93993563abbccaf8d6fe1e399fa4f16189c221d6ac16a528ebc3800b1159a5ccb8132b2103e21e4';

    	$params = $r->except('_token');

	    (new VKCore())->init($params['uid'], $params['post_id'],$params['res']);

        //return view('one',['a'=>$a2,'bw'=>$bw2,'bs'=>$bs2,'j'=>$j2,'m'=>$m2,'p'=>$p2])->render();

    }

    public function index2()
    {

        return view('index2');
    }
    public function two(Request $r)
    {
        global $l,$c,$json_name,$resultUser, $resultP,$proc,$resultNames;
        $access_token = '02d18a93993563abbccaf8d6fe1e399fa4f16189c221d6ac16a528ebc3800b1159a5ccb8132b2103e21e4';
        $params = $r->except('_token');
        (new VKCore())->second($params['uid'], $params['post_id'],$params['lim'],$params['lvl']);
        return view('two',['l'=>$l, 'c'=>$c, 'json_name'=>$json_name,'resultUser'=>$resultUser,'resultP'=>$resultP,'proc'=>$proc, 'resultNames'=>$resultNames]);


        /*        $json_name = (new VKCore())->second($params['uid'], $params['post_id'],$params['lim']);
        return view('two',compact(['l', 'c', 'json_name']));*/

    }


    public function wall()
    {

        dd("asdfsdf");
    }

}
