<?php

namespace App;
//ini_set('max_execution_time', 900);
use ATehnix\LaravelVkRequester\Models\VkRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class TestCore extends Model
{
    public $api_url = 'https://api.vk.com/method/';
	public $api_version = '5.68';
	public $balls_per_like = 1;
	public $balls_per_repost = 2;
	public $balls_per_friend = 5;
	public $balls_per_favorite = 5;
    public $balls_per_comment = 5;
	public $strong_percentage = 5;
    public $date_timeout = 53217000; //Два года

	protected $table = 'Request';
    protected $fillable = [
        'id_user',
        'text',
        'status',
        'group_stat'
    ];

    protected $guarded = ['id'];
    public $timestamps = false;

	/**
	 * Подсчет рейтинга. Второй массив заносится в первый.
	 *
	 * @param array $return
	 * @param array $users
	 *
	 * @return array
	 */
	public function countRating(array $return, array $users)
	{
		foreach ($users as $f) {
			if(!isset($return[$f['id']]) && !isset($f['deactivated'])) {
				$return[$f['id']] = $f;
				$return[$f['id']]['rating'] = 0;

				if(!isset($f['deactivated'])) {
					if(isset($f['is_friend']) && $f['is_friend']) {
					    $return[$f['id']]['rating'] += $this->balls_per_friend;
					}

					if(isset($f['is_favorite']) && $f['is_favorite']) {
						$return[$f['id']]['rating'] += $this->balls_per_favorite;
					}

					unset($return[$f['id']]['is_friend']);
					unset($return[$f['id']]['is_favorite']);
					unset($return[$f['id']]['hidden']);
					unset($return[$f['id']]['lists']);
				}
			}
		}

		unset($users);

		return $return;
	}


	public function wall_test($uid, $post_count, array $users, $today)
    {
        /*
      Посты на стене пользователя, если стена открыта
      */
        $wall_ = isset($this->api_request('wall.get', [
                'owner_id' => $uid,
                'fields' => 'photo_50','count'=>$post_count,
                'extended' => '1','access_token' => '10a898f0cf7ac14e70adfe7fce3d9936edbb138954c98f15335d3fe138fab1859e634dd622ba7f1486148'
            ])['response']['items']) ? $this->api_request('wall.get', [
            'owner_id' => $uid,
            'fields' => 'photo_50','count'=>$post_count,
            'extended' => '1','access_token' => '10a898f0cf7ac14e70adfe7fce3d9936edbb138954c98f15335d3fe138fab1859e634dd622ba7f1486148'
        ])['response']['items'] : $this->api_request('wall.get', [
            'owner_id' => $uid,
            'fields' => 'photo_50','count'=>$post_count,
            'extended' => '1','access_token' => '10a898f0cf7ac14e70adfe7fce3d9936edbb138954c98f15335d3fe138fab1859e634dd622ba7f1486148'
        ]);
        /*
        Анализ постов пользователя
        */
        foreach ($wall_ as $i_ => $post_) {
            $date = isset($post_['date']) ? $post_['date'] : 0;
            if (($today - $date) > $this->date_timeout)
            {
                break;
            }
            $comments_ = isset($this->api_request('wall.getComments', [
                    'owner_id' => $uid,
                    'post_id' => isset($post_['id']) ? $post_['id'] : "",
                    'extended' => '0',
                    'count' => 10
                ])['response']['items']) ? $this->api_request('wall.getComments', [
                'owner_id' => $uid,
                'post_id' => isset($post_['id']) ? $post_['id'] : "",
                'extended' => '0',
                'count' => 10
            ])['response']['items'] : $this->api_request('wall.getComments', [
                'owner_id' => $uid,
                'post_id' => isset($post_['id']) ? $post_['id'] : "",
                'extended' => '0',
                'count' => 10
            ]);

            //Прибавляем баллы в рейтинг, могут быть закрыты!
            if(isset($comments_['0'])){
                for($k = 0; $k < count($comments_); $k++){
                    $friend_id = $comments_[$k]['from_id'];
                    //если ли в списке друзей (добавляем при необходимости)
                    if ($friend_id != $uid)
                    {
                        if(!isset($users[$friend_id]))
                        {
                            $friend = $this->api_request('users.get', [
                                'user_ids' => $friend_id,
                                'fields' => 'last_name, first_name, photo_50'
                            ])['response'][0];
                            $users[$friend_id] = $friend; // !!!???
                            $users[$friend_id]['rating'] = 0;
                        }
                        $users[$friend_id]['rating'] += $this->balls_per_comment;
                    }
                }
            }

            /**
             * Анализ лайков. !!! count = ???
             */
            $like_ = isset($this->api_request('likes.getList', [
                    'type' => 'post',
                    'owner_id' => $uid,
                    'item_id' => isset($post_['id']) ? $post_['id'] : "",
                    'extended' => '1',
                    'fields' => 'photo_50',
                    'filter' => 'likes'
                ])['response']['items']) ? $this->api_request('likes.getList', [
                'type' => 'post',
                'owner_id' => $uid,
                'item_id' => isset($post_['id']) ? $post_['id'] : "",
                'extended' => '1',
                'fields' => 'photo_50',
                'filter' => 'likes'
            ])['response']['items'] : $this->api_request('likes.getList', [
                'type' => 'post',
                'owner_id' => $uid,
                'item_id' => isset($post_['id']) ? $post_['id'] : "",
                'extended' => '1',
                'fields' => 'photo_50',
                'filter' => 'likes'
            ]);

            //+баллы за лайки

            for($r=0;$r < count($like_);$r++) {
                if (isset($like_[$r])) {//проверка на наличие лайков
                    $friend_id = $like_[$r]['id'];
                    //если ли в списке друзей (добавляем при необходимости)
                    if ($friend_id != $uid) {
                        if (!isset($users[$friend_id])) {
                            $friend = $this->api_request('users.get', [
                                'user_ids' => $friend_id,
                                'fields' => 'last_name, first_name, photo_50'
                            ])['response'][0];
                            $users[$friend_id] = $friend; // !!!???
                            $users[$friend_id]['rating'] = 0;
                        }
                        $users[$friend_id]['rating'] += $this->balls_per_like;
                        }
                        }
                        }

                        /**
                         * Есть лайки => могут быть репосты. Анализ.
                         */

                        $copy_ = isset($this->api_request('likes.getList', [
                                'type' => 'post',
                                'owner_id' => $uid,
                                'item_id' => isset($post_['id']) ? $post_['id'] : "",
                                'extended' => '1',
                                'fields' => 'photo_50',
                                'filter' => 'copies'
                            ])['response']['items']) ? $this->api_request('likes.getList', [
                            'type' => 'post',
                            'owner_id' => $uid,
                            'item_id' => isset($post_['id']) ? $post_['id'] : "",
                            'extended' => '1',
                            'fields' => 'photo_50',
                            'filter' => 'copies'
                        ])['response']['items'] : $this->api_request('likes.getList', [
                            'type' => 'post',
                            'owner_id' => $uid,
                            'item_id' => isset($post_['id']) ? $post_['id'] : "",
                            'extended' => '1',
                            'fields' => 'photo_50',
                            'filter' => 'copies'
                        ]);
                        //+баллы
                        if (isset($copy_[0])) {
                            for ($e = 0; $e < count($copy_); $e++) {
                                $friend_id = $copy_[$e]['id'];
                                $users[$friend_id]['rating'] += $this->balls_per_repost;
                            }
                        }
            unset($copy_);
            unset($like_);
            unset($comments_);
        }
        return $users;
    }

    public function test($uid)
    {
        $today = time();
        /**
         * Возможность ввода символьного id
         */
        if(is_numeric($uid)==false) {
            $ii1 = $this->api_request('utils.resolveScreenName', [
                'screen_name' => $uid
            ]);
            $uid = $ii1['response']['object_id'];
        }

        /**
         * Получаем исходного пользователя
         */

        $im = $this->api_request('users.get', [
            'user_ids' => $uid,
            'fields' => 'photo_50'
        ])['response'][0];

        /**
         * Паблики и пользоваели,на которые подписан пользователь.
         */
        $subscriptions = $this->api_request('users.getSubscriptions', [
            'user_id' => $uid,
            'extended' => 0
        ]);


        $subs_users = $subscriptions['response']['users']['items'];
        $subs_groups = $subscriptions['response']['groups']['items'];

        global $subs_total;
        $subs_total = count($subs_users) + count($subs_groups);

//dd($subscriptions);

        $post_count = 80;//количество учитываемых постов на стене

        /**
         * Подписчики
         */
        /*
        $followers = isset($this->api_request('users.getFollowers', [
                'user_id' => $uid,
                'fields' => 'photo_50,is_favorite,is_friend',
            ])['response']['items']) ? $this->api_request('users.getFollowers', [
            'user_id' => $uid,
            'fields' => 'photo_50,is_favorite,is_friend',
        ])['response']['items'] : $this->api_request('users.getFollowers', [
            'user_id' => $uid,
            'fields' => 'photo_50,is_favorite,is_friend',
        ]);
/*
        /**
         * Друзья
         */
        /*
        $friends = isset($this->api_request('friends.get', [
                'user_id' => $uid,
                'fields' => 'photo_50,is_favorite'
            ])['response']['items']) ? $this->api_request('friends.get', [
            'user_id' => $uid,
            'fields' => 'photo_50,is_favorite'
        ])['response']['items'] : $this->api_request('friends.get', [
            'user_id' => $uid,
            'fields' => 'photo_50,is_favorite'
        ]);
/*
//dd($followers);
//dd($friends);

        /**
         * Собираем в один массив друзей и считаем рейтинг.
         */
        $users = [];
        $users = $this->wall_test($uid, $post_count, $users, $today);
//$users = $this->countRating($users, $followers);
       // $users = $this->countRating($users, $friends);
//dd($users);
        foreach ($users as $new_user) {
            /**
             * Берем посты со стены друга.
             */
            $wall2 = isset($this->api_request('wall.get', [
                    'owner_id' => $new_user['id'],
                    'fields' => 'photo_50,friend_status,is_favorite,is_friend','count'=>$post_count,
                    'extended' => '1','access_token' => '10a898f0cf7ac14e70adfe7fce3d9936edbb138954c98f15335d3fe138fab1859e634dd622ba7f1486148'
                ])['response']['items']) ? $this->api_request('wall.get', [
                'owner_id' => $new_user['id'],
                'fields' => 'photo_50,friend_status,is_favorite,is_friend','count'=>$post_count,
                'extended' => '1','access_token' => '10a898f0cf7ac14e70adfe7fce3d9936edbb138954c98f15335d3fe138fab1859e634dd622ba7f1486148'
            ])['response']['items'] : $this->api_request('wall.get', [
                'owner_id' => $new_user['id'],
                'fields' => 'photo_50,friend_status,is_favorite,is_friend','count'=>$post_count,
                'extended' => '1','access_token' => '10a898f0cf7ac14e70adfe7fce3d9936edbb138954c98f15335d3fe138fab1859e634dd622ba7f1486148'
            ]);
            /**
             * Анализ комментариев.
             */
            foreach ($wall2 as $i => $post) {
                $date = isset($post['date']) ? $post['date'] : 0;
                if (($today - $date) > $this->date_timeout)
                {
                    break;
                }

                $comments = isset($this->api_request('wall.getComments', [
                        'owner_id' => $new_user['id'],
                        'post_id' => isset($post['id']) ? $post['id'] : "",
                        'extended' => '0',
                        'count' => 10
                    ])['response']['items']) ? $this->api_request('wall.getComments', [
                    'owner_id' => $new_user['id'],
                    'post_id' => isset($post['id']) ? $post['id'] : "",
                    'extended' => '0',
                    'count' => 10
                ])['response']['items'] : $this->api_request('wall.getComments', [
                    'owner_id' => $new_user['id'],
                    'post_id' => isset($post['id']) ? $post['id'] : "",
                    'extended' => '0',
                    'count' => 10
                ]);
                //Прибавляем баллы в рейтинг
                if(isset($comments['0'])){
                    for($i = 0; $i<count($comments); $i++){
                        //dd($comments[$i]);
                        if($comments[$i]['from_id'] == $uid)
                            $users[$new_user['id']]['rating'] += $this->balls_per_comment;
                    }
                }

                /**
                 * Анализ лайков.
                 */
                $like2 = isset($this->api_request('likes.getList', [
                        'type' => 'post',
                        'owner_id' => $new_user['id'],
                        'item_id' => isset($post['id']) ? $post['id'] : "",
                        'extended' => '1',
                        'fields' => 'photo_50',
                        'filter' => 'likes'
                    ])['response']['items']) ? $this->api_request('likes.getList', [
                    'type' => 'post',
                    'owner_id' => $new_user['id'],
                    'item_id' => isset($post['id']) ? $post['id'] : "",
                    'extended' => '1',
                    'fields' => 'photo_50',
                    'filter' => 'likes'
                ])['response']['items'] : $this->api_request('likes.getList', [
                    'type' => 'post',
                    'owner_id' => $new_user['id'],
                    'item_id' => isset($post['id']) ? $post['id'] : "",
                    'extended' => '1',
                    'fields' => 'photo_50',
                    'filter' => 'likes'
                ]);
                //dd($like2);
                //+баллы
                for($r=0;$r<count($like2);$r++) {
                    if (isset($like2[$r])) {//проверка на наличие лайков
                        if($like2[$r]['id']==$uid) {
                            $users[$new_user['id']]['rating'] += $this->balls_per_like;
                            //print_r($like2[$r]['id']);
                            /**
                             * Есть лайки => могут быть репосты. Анализ.
                             */
                            $copy2 = isset($this->api_request('likes.getList', [
                                    'type' => 'post',
                                    'owner_id' => $new_user['id'],
                                    'item_id' => isset($post['id']) ? $post['id'] : "",
                                    'extended' => '1',
                                    'fields' => 'photo_50',
                                    'filter' => 'copies'
                                ])['response']['items']) ? $this->api_request('likes.getList', [
                                'type' => 'post',
                                'owner_id' => $new_user['id'],
                                'item_id' => isset($post['id']) ? $post['id'] : "",
                                'extended' => '1',
                                'fields' => 'photo_50',
                                'filter' => 'copies'
                            ])['response']['items'] : $this->api_request('likes.getList', [
                                'type' => 'post',
                                'owner_id' => $new_user['id'],
                                'item_id' => isset($post['id']) ? $post['id'] : "",
                                'extended' => '1',
                                'fields' => 'photo_50',
                                'filter' => 'copies'
                            ]);
                            //+баллы
                            for($e=0;$e<count($copy2);$e++) {
                                if (isset($copy2[$e])) {
                                    if ($copy2[$e]['id'] == $uid) {
                                        $users[$new_user['id']]['rating'] += $this->balls_per_repost;
                                        //print_r($copy2[$e]['id']);
                                    }
                                }
                            }

                        }

                    }
                }

            }

        }
        //dd($users);


        $mas = [];
        $mas[0] = 0;

       // $users = $this->wall_test($uid, $post_count, $users);
        $mas = array_slice($mas, 0, 380);
        $mas = json_encode($mas);
        /**
         * Запись массивов о пользователей в файл
         */
        file_put_contents(public_path() . "/users.json", json_encode($users));
        file_put_contents(public_path() . "/im.json", json_encode($im));
        /**
         * Передача данных для составления графа
         */
        ?>
        <script src="https://d3js.org/d3.v3.min.js"></script>
        <script src="assets/d3.tip.v0.6.3.js"></script>
        <link href="assets/bootstrap.css" rel="stylesheet">
        <script src="assets/jquery.min.js"></script>
        <script src="assets/bootstrap.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script type='text/javascript' src="http://bost.ocks.org/mike/fisheye/fisheye.js?0.0.3"></script>

        <script src="assets/in3.js"></script>
        <script src="assets/jquery.min.js"></script>
        <script>

            setTimeout(function () {
                $.ajax({
                    url: '/ajax',
                    data: {
                        method: 'test_data',
                    }
                }).done(function (response) {
                    $('body').html(response);
                    //go();
                });
            }, 5000);

        </script>
        <?php

    }


	/**
	 * Формирует запрос к VK API
	 *
	 * @param     $method
	 * @param     $params
	 * @param int $array
	 *
	 * @return mixed
	 */
    public function api_request($method, $params, $array = 1)
    {
    	$params = http_build_query($params);

	    $url = $this->api_url.$method.'?'.$params.'&v='.$this->api_version.'&access_token='.env('VK_ACCESS_TOKEN');

	    $request = @file_get_contents($url);

	    return ($request) ? json_decode($request, $array) : false;
    }
 }

?>
<body>

</body>

