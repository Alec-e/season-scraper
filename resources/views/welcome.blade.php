<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
/*            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }
*/
            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <section>
                <?php

                $allNBA = ['bos', 'bkn','ny','phi','tor','chi','cle','det','ind','mil','den','min','okc','por','utah','gs','lac','lal','phx','sac','atl','cha', 'mia', 'orl', 'wsh', 'dal', 'hou', 'mem', 'no', 'sa'];

                $allNBAfull = ['Boston', 'Brooklyn','New York','Philadelphia','Toronto','Chicago','Cleveland','Detroit','Indiana','Milwaukee','Denver','Minnesota','Oklahoma City','Portland','Utah','Golden State','LA','Los Angeles','Phoenix','Sacramento','Atlanta','Charlotte', 'Miami', 'Orlando', 'Washington', 'Dallas', 'Houston', 'Memphis', 'New Orleans', 'San Antonio'];

                $twoNBA = ['New York', 'Oklahoma City', 'Golden State', 'Los Angeles', 'New Orleans', 'San Antonio'];

               // function array_search_partial($arr, $keyword) {
               //      foreach($arr as $index => $string) {
               //          if (strpos($string, $keyword) !== FALSE){
               //              return $index;
               //          }
               //      }
               //  }
                //function store_schedule(){}

                $lakers = file_get_contents('http://www.espn.com/nba/team/schedule/_/name/utah');
                $lakers_sched = new DOMDocument();
                libxml_use_internal_errors(true);

                if(!empty($lakers)){ //if any html is actually returned

                    $lakers_sched->loadHTML($lakers);

                    libxml_clear_errors(); //remove errors for yucky html
                    
                    $lakers_xpath = new DOMXPath($lakers_sched);

                    //print_r($lakers_xpath);

                    //get all the h2's with an id
                    //ExpressionSelectingTable/tr[td//text()[contains(., 'targetString')]]
                    $sched_row = $lakers_xpath->query('//tbody[@class="Table2__tbody"]/tr');

                    $schedule = array();

                    if($sched_row->length > 0){

                        foreach($sched_row as $row){

                            //echo '<li>' . $row->nodeValue . '</li>';

                            $gameInfo = $row->nodeValue;

                            //echo '<li>' . $gameInfo . '</li>';


                            // BYPASS ROWS THAT ARE NOT GAMES
                            if(strpos($gameInfo, 'vs') == true || strpos($gameInfo, '@') == true){

                                if(strpos($gameInfo, 'vs') == true){
                                    $date = substr($gameInfo, 0, strpos($gameInfo, 'vs'));
                                    $opposingTeam = str_replace($date . 'vs', '', $gameInfo);
                                    $location = 'home';
                                }else{
                                    $date = substr($gameInfo, 0, strpos($gameInfo, '@'));
                                    $opposingTeam = str_replace($date . '@', '', $gameInfo);
                                    $location = 'away';
                                }

                                //echo '<hr>';

                                //echo $opposingTeam;


                                    // NEW GAMES
                                    if(strpos($opposingTeam, 'PM') !== ''){
                                        $opponent = strstr($opposingTeam, 'PM', true);
                                        $opponent = preg_replace('/[0-9]+/', '', $opponent);
                                        $opponent = trim(preg_replace('/:/', '', $opponent));
                                        $time = str_replace($opponent, '', $opposingTeam);
                                        $time = strstr($time , 'PM', true);
                                        $time = $time . ' PM';
                                        $status = '';
                                        $score = '';
                                    }


                                    // COMPLETED GAMES
                                    if(strpos($opposingTeam, 'PM') == ''){

                                        $completedGame = explode(' ', $opposingTeam);

                                        // echo '<pre>';
                                        // print_r($completedGame);
                                        // echo '</pre>';

                                        $opponent = $completedGame['0'];

                                        //echo $opponent;

                                        if(!in_array($opponent, $allNBAfull)){

                                            $completedGame = explode(' ', $opposingTeam);
                                            $opponent = $completedGame['0'];
                                            $opponent2 = NULL;
                                            if (array_key_exists('1', $completedGame)) {
                                                $opponent2 = $completedGame['1'];
                                            }

                                            $opponent = $opponent . ' ' . $opponent2;
                                        }

                                        $status = trim(str_replace($opponent, '', $opposingTeam));

                                        $GameStatus = strstr($status, ' ', true);

                                        //echo $GameStatus;

                                        if(!empty($GameStatus)){$status = $GameStatus[0];}

                                        //echo $status;

                                        $score = str_replace($status, '', $GameStatus);                          

                                        if($status == 'W'){
                                            $status = 'win';
                                        }elseif($status = 'L'){
                                            $status = 'loss';
                                        }

                                        $time = 'passed';

                                         //echo $status;

                                         //echo 'Score: ' . $score;
                                    
                                    }

                                    // else{
                                    //     $opponent = strstr($opposingTeam, 'AM', true);
                                    //     $opponent = preg_replace('/[0-9]+/', '', $opponent);
                                    //     $opponent = trim(preg_replace('/:/', '', $opponent));
                                    //     $time = str_replace($opponent, '', $opposingTeam);
                                    //     $time = strstr($time , 'AM', true);
                                    //     $time = $time . ' AM';
                                    // }

                                    //if($status !== ''){ echo '<p>Status: ' . $status . '</p>';};
                                    
                                    // echo '<p>Opponent: ' . $opponent . '</p>';

                                    // echo '<p>Date: ' . $date . '</p>';
                                    
                                    // echo '<p>Time: ' . $time . '</p>';

                                    // echo '<p>Location: ' . $location . '</p>';

                                    if(!empty($score)){$score = $score;}

                                    //store the data in the $pokemon_list array
                                    $schedule[] = array('opponent' => $opponent, 'date' => $date, 'time' => $time, 'location' => $location, 'status'=> $status, 'score' => $score);

                            }
                        }

                    }
                }

                echo '<pre>';
                print_r($schedule);
                echo '</pre>';
                ?>
                <!-- <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div> -->
                </section>
            </div>
        </div>
    </body>
</html>
