<?php

namespace App\Http\Controllers;

use App\Models\DeveloperModel;
use App\Models\TaskModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use MongoDB\Driver\Session;

class HomeController extends Controller
{

    public function index(){
        $developers = DeveloperModel::All();
        $tasks = TaskModel::All();
        $maxweek = TaskModel::All()->max('week');
        return view('home',compact('developers','tasks','maxweek'));
    }

    public function temizle(){
        TaskModel::query()->truncate();
        return back();
    }

    public function goruntule(){
        $id = request('id');
        $developer = DeveloperModel::Where('id',$id)->First();
        $tasks = TaskModel::Where('ref_dev',$id)->get();
        return view('show',compact('tasks','developer'));
    }

    public function getlink(){
        return view('getlink');
    }

    public function redirectprovider(){
        $link = request('link');
        $provider = request('providerselection');
        if($provider == "Provider1"){
            return redirect()->route('provider1')->with(['link' => $link]);
        }
        if($provider == "Provider2"){
            return redirect()->route('provider2')->with(['link' => $link]);
        }
    }

    public function provider1(){
        try{
            $link = session()->get('link');
            $tasks = json_decode(file_get_contents($link), true);
            $jsonlength = count($tasks);

            $level1duration = 0;
            $level2duration = 0;
            $level3duration = 0;
            $level4duration = 0;
            $level5duration = 0;
            $level1taskcounter = 0;
            $level2taskcounter = 0;
            $level3taskcounter = 0;
            $level4taskcounter = 0;
            $level5taskcounter = 0;

            // Toplam İş Süresi ve Adeti
            for($i = 0; $i < $jsonlength; $i++) {
                foreach ($tasks[$i] as $key => $value) {
                    switch ($value['level']) {
                        case $value['level'] == 1;
                            $level1duration += $value['estimated_duration'];
                            $level1taskcounter++;
                            break;
                        case $value['level'] == 2;
                            $level2duration += $value['estimated_duration'];
                            $level2taskcounter++;
                            break;
                        case $value['level'] == 3;
                            $level3duration += $value['estimated_duration'];
                            $level3taskcounter++;
                            break;
                        case $value['level'] == 4;
                            $level4duration += $value['estimated_duration'];
                            $level4taskcounter++;
                            break;
                        case $value['level'] == 5;
                            $level5duration += $value['estimated_duration'];
                            $level5taskcounter++;
                            break;
                    }
                }
            }

            for($i = 1; $i <= 5; $i++){
                $developers = DeveloperModel::Where('level',$i)->get();
                if(count($developers) > 0){
                    switch($i){
                        case 1:
                            $taskforeach = (int)($level1taskcounter/count($developers));
                            break;
                        case 2:
                            $taskforeach = (int)($level2taskcounter/count($developers));
                            break;
                        case 3:
                            $taskforeach = (int)($level3taskcounter/count($developers));
                            break;
                        case 4:
                            $taskforeach = (int)($level4taskcounter/count($developers));
                            break;
                        case 5:
                            $taskforeach = (int)($level5taskcounter/count($developers));
                            break;
                    }
                }

                if(count($developers) > 1) {
                    foreach ($developers as $developer) {
                        $totalhours = TaskModel::Where('ref_dev',$developer->id)->get()->Sum('duration');
                        $week = 0;
                        $counter = 0;
                        for ($j = 0; $j < $jsonlength; $j++) {
                            foreach ($tasks[$j] as $key => $value) {
                                //task baska birisine eklenmiş mi?
                                $ifexist = TaskModel::Where('task', '=', $key)->First();
                                if ($ifexist == null) {
                                    $totalhours += $value['estimated_duration'];
                                    switch ((int)$totalhours){
                                        case (int)$totalhours >= 0 && (int)$totalhours <= 45;
                                            $week = 1;
                                            break;
                                        case (int)$totalhours >= 46 && (int)$totalhours <= 90;
                                            $week = 2;
                                            break;
                                        case (int)$totalhours >= 91 && (int)$totalhours <= 135;
                                            $week = 3;
                                            break;
                                        case (int)$totalhours >= 136 && (int)$totalhours <= 180;
                                            $week = 4;
                                            break;
                                        case (int)$totalhours >= 181 && (int)$totalhours <= 225;
                                            $week = 5;
                                            break;
                                        case (int)$totalhours >= 226 && (int)$totalhours <= 270;
                                            $week = 6;
                                            break;
                                        case (int)$totalhours >= 271 && (int)$totalhours <= 315;
                                            $week = 7;
                                            break;
                                        case (int)$totalhours >= 316 && (int)$totalhours <= 360;
                                            $week = 8;
                                            break;
                                    }
                                    if ($value['level'] == $i && $developer->level == $i && $counter <= $taskforeach) {
                                        TaskModel::create([
                                            'ref_dev' => $developer->id,
                                            'task' => $key,
                                            'week' => $week,
                                            'duration' => $value['estimated_duration']
                                        ]);
                                        $counter++;
                                    }
                                }
                            }
                        }
                    }
                    $totalhours = 0;
                    $week = 0;
                }
                elseif(count($developers) == 1) {
                    $devid = DeveloperModel::Where('level',$i)->First();
                    $totalhours = TaskModel::Where('ref_dev',$devid->id)->get()->Sum('duration');
                    $week = 0;
                    for ($j = 0; $j < $jsonlength; $j++) {
                        foreach ($tasks[$j] as $key => $value) {
                            if ($value['level'] == $i) {
                                $ifexist = TaskModel::Where('task', '=', $key)->First();
                                if ($ifexist == null) {
                                    $totalhours += $value['estimated_duration'];
                                    switch ((int)$totalhours){
                                        case (int)$totalhours >= 0 && (int)$totalhours <= 45;
                                            $week = 1;
                                            break;
                                        case (int)$totalhours >= 46 && (int)$totalhours <= 90;
                                            $week = 2;
                                            break;
                                        case (int)$totalhours >= 91 && (int)$totalhours <= 135;
                                            $week = 3;
                                            break;
                                        case (int)$totalhours >= 136 && (int)$totalhours <= 180;
                                            $week = 4;
                                            break;
                                        case (int)$totalhours >= 181 && (int)$totalhours <= 225;
                                            $week = 5;
                                            break;
                                        case (int)$totalhours >= 226 && (int)$totalhours <= 270;
                                            $week = 6;
                                            break;
                                        case (int)$totalhours >= 271 && (int)$totalhours <= 315;
                                            $week = 7;
                                            break;
                                        case (int)$totalhours >= 316 && (int)$totalhours <= 360;
                                            $week = 8;
                                            break;
                                    }
                                    TaskModel::create([
                                        'ref_dev' => $devid->id,
                                        'task' => $key,
                                        'week' => $week,
                                        'duration' => $value['estimated_duration']
                                    ]);
                                }
                            }
                        }
                    }
                }
                elseif(count($developers) == 0){
                    echo $i . ". seviye developer mevcut değil";
                }
            }
            return redirect()->route('home');
        }catch (\Exception $ex){
            echo "Link ile provider uyumuşmuyor. Lütfen Tekrar deneyiniz";
        }
    }

    public function provider2(){
        try{
            $link = session()->get('link');
            $level1duration = 0;
            $level2duration = 0;
            $level3duration = 0;
            $level4duration = 0;
            $level5duration = 0;
            $level1taskcounter = 0;
            $level2taskcounter = 0;
            $level3taskcounter = 0;
            $level4taskcounter = 0;
            $level5taskcounter = 0;

            $tasks = json_decode(file_get_contents($link), true);

            // Toplam İş Süresi ve Adeti
            foreach($tasks as $task){
                switch($task){
                    case $task['zorluk'] == 1;
                        $level1duration += $task['sure'];
                        $level1taskcounter++;
                        break;
                    case $task['zorluk'] == 2;
                        $level2duration += $task['sure'];
                        $level2taskcounter++;
                        break;
                    case $task['zorluk'] == 3;
                        $level3duration += $task['sure'];
                        $level3taskcounter++;
                        break;
                    case $task['zorluk'] == 4;
                        $level4duration += $task['sure'];
                        $level4taskcounter++;
                        break;
                    case $task['zorluk'] == 5;
                        $level5duration += $task['sure'];
                        $level5taskcounter++;
                        break;
                }
            }

            for($i = 1; $i <= 5; $i++){
                $collection = collect($tasks)->where('zorluk','=',$i);
                $developers = DeveloperModel::Where('level',$i)->get();
                if(count($developers) > 0) {
                    $taskforeach = (int)(count($collection) / count($developers));
                }

                if(count($developers) > 1){
                    foreach($developers as $developer){
                        $totalhours = TaskModel::Where('ref_dev',$developer->id)->get()->Sum('duration');
                        $week = 0;
                        $counter = 0;
                        foreach($collection as $task){
                            //task baska birisine eklenmiş mi?
                            $ifexist = TaskModel::Where('task','=',$task['id'])->First();
                            if($ifexist == null){
                                if($task['zorluk'] == $i && $developer->level == $i && $counter <= $taskforeach){
                                    $totalhours += $task['sure'];
                                    switch ((int)$totalhours){
                                        case (int)$totalhours >= 0 && (int)$totalhours <= 45;
                                            $week = 1;
                                            break;
                                        case (int)$totalhours >= 46 && (int)$totalhours <= 90;
                                            $week = 2;
                                            break;
                                        case (int)$totalhours >= 91 && (int)$totalhours <= 135;
                                            $week = 3;
                                            break;
                                        case (int)$totalhours >= 136 && (int)$totalhours <= 180;
                                            $week = 4;
                                            break;
                                        case (int)$totalhours >= 181 && (int)$totalhours <= 225;
                                            $week = 5;
                                            break;
                                        case (int)$totalhours >= 226 && (int)$totalhours <= 270;
                                            $week = 6;
                                            break;
                                        case (int)$totalhours >= 271 && (int)$totalhours <= 315;
                                            $week = 7;
                                            break;
                                        case (int)$totalhours >= 316 && (int)$totalhours <= 360;
                                            $week = 8;
                                            break;
                                    }
                                    TaskModel::create([
                                        'ref_dev' => $developer->id,
                                        'task' => $task['id'],
                                        'week' => $week,
                                        'duration' => $task['sure']
                                    ]);
                                    $counter++;
                                }
                            }
                        }
                    }
                }
                elseif(count($developers) == 1){
                    $devid = DeveloperModel::Where('level', $i)->First();
                    $totalhours = TaskModel::Where('ref_dev',$devid->id)->get()->Sum('duration');
                    $week = 0;
                    foreach($tasks as $task){
                        if($task['zorluk'] == $i){
                            $ifexist = TaskModel::Where('task','=',$task['id'])->First();
                            if($ifexist == null) {
                                $totalhours += $task['sure'];
                                switch ((int)$totalhours){
                                    case (int)$totalhours >= 0 && (int)$totalhours <= 45;
                                        $week = 1;
                                        break;
                                    case (int)$totalhours >= 46 && (int)$totalhours <= 90;
                                        $week = 2;
                                        break;
                                    case (int)$totalhours >= 91 && (int)$totalhours <= 135;
                                        $week = 3;
                                        break;
                                    case (int)$totalhours >= 136 && (int)$totalhours <= 180;
                                        $week = 4;
                                        break;
                                    case (int)$totalhours >= 181 && (int)$totalhours <= 225;
                                        $week = 5;
                                        break;
                                    case (int)$totalhours >= 226 && (int)$totalhours <= 270;
                                        $week = 6;
                                        break;
                                    case (int)$totalhours >= 271 && (int)$totalhours <= 315;
                                        $week = 7;
                                        break;
                                    case (int)$totalhours >= 316 && (int)$totalhours <= 360;
                                        $week = 8;
                                        break;
                                }
                                TaskModel::create([
                                    'ref_dev' => $devid->id,
                                    'task' => $task['id'],
                                    'week' => $week,
                                    'duration' => $task['sure']
                                ]);
                            }
                        }
                    }
                    $totalhours = 0;
                    $week = 0;
                }
                elseif(count($developers) == 0){
                    echo $i . ". seviye developer mevcut değil";
                }
            }

            return redirect()->route('home');
        }catch (\Exception $ex){
            echo "Link ile provider uyumuşmuyor. Lütfen Tekrar deneyiniz";
        }
    }

}
