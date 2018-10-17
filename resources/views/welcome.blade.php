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
            html, body {
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

                //if(!empty($html)){ //if any html is actually returned

                    //$pokemon_doc->loadHTML($html);
                    //libxml_clear_errors(); //remove errors for yucky html
                    
                    //$pokemon_xpath = new DOMXPath($pokemon_doc);

                    //get all the h2's with an id
                //     $pokemon_row = $pokemon_xpath->query('//h2[@id]');

                //     if($pokemon_row->length > 0){
                //         foreach($pokemon_row as $row){
                //             echo $row->nodeValue . "<br/>";
                //         }
                //     }
                // }
                //$lakers = file_get_contents('https://www.nba.com/lakers/schedule');
                $lakers = file_get_contents('http://www.espn.com/nba/team/schedule/_/name/lal');
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
                    ////table[@class="info"]//td[2]/text()
                    //$pokemon_and_type = $pokemon_xpath->query('//span[@class="infocard-tall "]');

                    //print_r($sched_row);

                    // if($sched_row->length > 0){

                    //     foreach($sched_row as $row){
                    //         echo '<li>' . $row->nodeValue . '</li>';
                    //     }
                        
                    // }

                    if($sched_row->length > 0){

                        foreach($sched_row as $row){

                            //echo '<li>' . $row->nodeValue . '</li>';

                            $gameInfo = $row->nodeValue;
                            // $date = substr($gameInfo, 0, strpos($gameInfo, 'vs'));
                            // $date = substr($gameInfo, 0, strpos($gameInfo, '@'));

                            //echo '<li>' . $gameInfo . '</li>';

                            if(strpos($gameInfo, 'vs') == true){
                                $date = substr($gameInfo, 0, strpos($gameInfo, 'vs'));
                                $opposingTeam = str_replace($date . 'vs', '', $gameInfo);
                            }else{
                                $date = substr($gameInfo, 0, strpos($gameInfo, '@'));
                                $opposingTeam = str_replace($date . '@', '', $gameInfo);
                            }

                            //echo $opposingTeam;

                            if(strpos($opposingTeam, 'PM') !== ''){
                                $opponent = strstr($opposingTeam, 'PM', true);
                                $opponent = preg_replace('/[0-9]+/', '', $opponent);
                                $opponent = trim(preg_replace('/:/', '', $opponent));
                                $time = str_replace($opponent, '', $opposingTeam);
                                $time = strstr($time , 'PM', true);
                                $time = $time . ' PM';
                                //echo $time;
                            }else{
                                $opponent = strstr($opposingTeam, 'AM', true);
                                $opponent = preg_replace('/[0-9]+/', '', $opponent);
                                $opponent = trim(preg_replace('/:/', '', $opponent));
                                $time = str_replace($opponent, '', $opposingTeam);
                                $time = strstr($time , 'AM', true);
                                $time = $time . ' AM';
                            }

                            // $opponent = strstr($opposingTeam, 'PM', true);
                            // $opponent = preg_replace('/[0-9]+/', '', $opponent);
                            // $opponent = preg_replace('/:/', '', $opponent);
                            //$time = substr($opposingTeam, 0, strpos($opposingTeam, 'PM '));
                            //strpos($opposingTeam, 'PM');
                            //$date = strpos('vs', $gameInfo);
                            echo '<hr>';
                            echo '<p>Date: ' . $date . '</p>';
                            echo '<p>Opponent: ' . $opponent . '</p>';
                            echo '<p>Time: ' . $time . '</p>';

                        }

                    }
                }
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
