<?php

namespace App\Http\Controllers;

use App\VKCore;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;

use App\Http\Requests;

class AjaxController extends Controller
{

    public function ajax(Request $request)
    {


        if($request->has('method') /*&& $request->ajax()*/) {
            $function_name = 'ajax_'.$request->input('method');
            if(method_exists($this, $function_name)) {
                $params = $request->except('method');
                return call_user_func_array([$this, $function_name], $params);
            } else {
                return $this->json_response_error('Метод не существует');
            }
        } else {
            return $this->json_response_error('Неверный AJAX запрос');
        }
    }


    public function json_response_success($data = false)
    {
        return ($data) ?
            response()->json([
                'success' => true,
                'data' => $data
            ])
            :
            response()->json([
                'success' => true
            ]);
    }

    /**
     * Ошибочный JSON ответ
     *
     * @param $error
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function json_response_error($error = false)
    {
        return ($error) ?
            response()->json([
                'success' => false,
                'error' => $error
            ])
            :
            response()->json([
                'success' => false
            ]);
    }

    public $strong_percentage = 10;

    public function ajax_pars_user($mas2,$mas){

        //dd($mas);
        //echo "<script>console.log( 'Debug Objects: " . json_encode($mas). "' );</script>";

        file_put_contents(public_path() . "/data2.json",json_encode($mas) );
        file_put_contents(public_path() . "/data2.json",json_encode($mas2) );
        //print_r($mas);
    }


    public function ajax_test_data()
    {
        $users = json_decode(file_get_contents(public_path() . "/users.json"), true);
        $im = json_decode(file_get_contents(public_path() . "/im.json"), true);

//dd($mas);

        usort($users, [$this, 'cmp']);

        $users_old = $users;
        $users = [];

        foreach ($users_old as $user) {
            if(!isset($users[$user['id']])) {
                $users[$user['id']] = $user;
            }
        }

        $strong_count = ceil(count($users)*$this->strong_percentage/100);
        //dd($strong_count);
        $week_count = count($users) - $strong_count;

        $strong = array_slice($users, 0, $strong_count, true);
        $week = array_slice($users, $strong_count, $week_count, true);

        $week_with_post = 0;
        $strong_with_post = 0;

        foreach ($week as $w) {
            if(isset($w['post']) && $w['post']) {
                $week_with_post++;
            }
        }

        foreach ($strong as $s) {
            if(isset($s['post']) && $s['post']) {
                $strong_with_post++;
            }
        }

        $array['nodes'] = [];
        $array['nodes'][0] = [
            'name' => $im['first_name'].' '.$im['last_name'],
            'img' => $im['photo_50'],
            'ratings' => false,
            'post' => false,
            'group' => 3
        ];

        $array['links'] = [];

        $i = 0;

        foreach ($strong as $user) {
            if($user['rating']>0) {
                //print_r($user);
                $i++;
                $array['nodes'][] = [
                    'name' => isset($user['first_name']) ?
                        ($user['first_name'] . ' ' . $user['last_name']) : $user['name'],
                    'rating' => $user['rating'],
                    'post' => (isset($user['post'])) ? true : false,
                    'img' => $user['photo_50'],
                    //'group' =>  3
                    'group' => (isset($user['post'])) ? 13 : 3,
                ];
                $array['links'][] = [
                    'source' => 0,
                    'target' => $i,
                    'connection' => 'week'
                ];
            }
        }


        foreach ($week as $user) {
            $i++;
            $array['nodes'][] = [
                'name' => isset($user['first_name']) ?
                    ($user['first_name'].' '.$user['last_name']):$user['name'],
                'rating' => $user['rating'],
                'post' => (isset($user['post'])) ? true : false,
                'img' => $user['photo_50'],
                //'group' =>  2
                'group' =>  (isset($user['post'])) ? 12 : 2,
            ];
            $array['links'][] = [
                'source' => 0,
                'target' => $i,
                'connection' => 'strong'
            ];
        }
        $s=$w=0;
        foreach ($strong as $user) {
            if($user['rating']>0) {
                //$s =$s+1;
            }
            else
                $w=$w+1;
        }



        $json = json_encode($array);
        //print_r($json);
        file_put_contents(public_path() . "/data2.json", $json);


        return view('test');
    }


    public function ajax_new_data($nn,$subs_total,$mas)
    {
        $users = json_decode(file_get_contents(public_path() . "/users.json"), true);
        $im = json_decode(file_get_contents(public_path() . "/im.json"), true);
        global $subs_with_post;
        //global $a2,$bw2,$bs2,$j2,$m2,$p2;
        $subs_with_post = $nn;

//dd($mas);



if($mas[0]!=0) {
    //NEW
    for ($i = 0; $i < count($mas); $i++)
        $users[$mas[$i]]['post'] = 1;
}
    usort($users, [$this, 'cmp']);

                $users_old = $users;
                $users = [];

                foreach ($users_old as $user) {
                    if(!isset($users[$user['id']])) {
                        $users[$user['id']] = $user;
                    }
                }

                $strong_count = ceil(count($users)*$this->strong_percentage/100);
                //dd($strong_count);
                $week_count = count($users) - $strong_count;

                $strong = array_slice($users, 0, $strong_count, true);
                $week = array_slice($users, $strong_count, $week_count, true);

                $week_with_post = 0;
                $strong_with_post = 0;

                foreach ($week as $w) {
                    if(isset($w['post']) && $w['post']) {
                        $week_with_post++;
                    }
                }

                foreach ($strong as $s) {
                    if(isset($s['post']) && $s['post']) {
                        $strong_with_post++;
                    }
                }

                $array['nodes'] = [];
                $array['nodes'][0] = [
                    'name' => $im['first_name'].' '.$im['last_name'],
                    'img' => $im['photo_50'],
                    'ratings' => false,
                    'post' => false,
                    'group' => 3
                ];

                $array['links'] = [];

                $i = 0;

                foreach ($strong as $user) {
                if($user['rating']>0) {
                    //print_r($user);
                    $i++;
                    $array['nodes'][] = [
                        'name' => isset($user['first_name']) ?
                            ($user['first_name'] . ' ' . $user['last_name']) : $user['name'],
                        'rating' => $user['rating'],
                        'post' => (isset($user['post'])) ? true : false,
                        'img' => $user['photo_50'],
                        //'group' =>  3
                        'group' => (isset($user['post'])) ? 13 : 3,
                    ];
                    $array['links'][] = [
                        'source' => 0,
                        'target' => $i,
                        'connection' => 'week'
                    ];
                }
                }


                foreach ($week as $user) {
                    $i++;
                    $array['nodes'][] = [
                        'name' => isset($user['first_name']) ?
                            ($user['first_name'].' '.$user['last_name']):$user['name'],
                        'rating' => $user['rating'],
                        'post' => (isset($user['post'])) ? true : false,
                        'img' => $user['photo_50'],
                        //'group' =>  2
                        'group' =>  (isset($user['post'])) ? 12 : 2,
                    ];
                    $array['links'][] = [
                        'source' => 0,
                        'target' => $i,
                        'connection' => 'strong'
                    ];
                }
                $s=$w=0;
        foreach ($strong as $user) {
            if($user['rating']>0) {
                //$s =$s+1;
            }
            else
                $w=$w+1;
        }


        $strong_count = count($strong)-$w;
        $week_count = count($week)+$w;

                $json = json_encode($array);
                //print_r($json);
                file_put_contents(public_path() . "/data2.json", $json);

                //$a = $subs_with_post / $subs_total;
                $bw = $week_with_post/$week_count;
                $bs = $strong_with_post/$strong_count;
                $j =  $week_count;
                $m = $strong_count;

        $a = $subs_with_post/$subs_total;
        $p = 1-((1-$a)*((1-$bw)**$j)*((1-$bs)**$m));

        if($mas[0]==0 && $nn==999)
            return view('test');
        else
            return view('one',['a'=>$a,'bw'=>$bw,'bs'=>$bs,'j'=>$j,'m'=>$m,'p'=>$p]);
        }

    static function cmp($a, $b)
    {
        if ($a['rating'] == $b['rating']) {
            return 0;
        }
        return ($a['rating'] > $b['rating']) ? -1 : 1;
    }
}
