<?php

namespace App\Http\Controllers;

use FFMpeg\Format\Video\WMV;
use Illuminate\Http\Request;
use Pawlox\VideoThumbnail\Facade;
///use Pawlox\VideoThumbnail\Facade\VideoThumbnail;
use Pawlox\VideoThumbnail\VideoThumbnail;
use Pawlox\VideoThumbnail\VideoCut;
use Illuminate\Support\Facades\Log;
use FFMpeg\Coordinate;
use FFMpeg\Format\Video\X264;
use FFMpeg\Media\Video;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFProbe;



class thumbnail extends Controller
{
    //
    //require 'vendor/autoload.php';

    protected $FFMpeg = NULL;

    public function  thumbnail(){
        $videoUrl = public_path('uploads\alan.mp4');


        $storageUrl = '..\public\uploads\thumbnails';
        $filename = uniqid(rand(), true).'.jpg';
        //$fileName = 'movie.jpg';

        $second = 1;
        Log::debug('An informational message.' . $videoUrl);
        $view = new VideoThumbnail();

        $view->createThumbnail($videoUrl, $storageUrl, $filename, $second, $width = 640, $height = 480);
        Log::debug('Finish');

    }

    public function  cutVideo(){
        $videoUrl = public_path('uploads\alan.mp4');
        $storageUrl = 'D:\xampp\htdocs\Newfolder\ffmpeg\public\uploads\thumbnails';
        $filename = uniqid(rand(), true);
        $fullname = "{$storageUrl}/{$filename}".".mp4";
        Log::debug('An informational message.' . $videoUrl);
        $view = new VideoThumbnail();


        //$videoUrl = public_path('uploads\avi.avi');
        $this->FFMpeg = FFMpeg::create([
            'ffmpeg.binaries'  => base_path() .'/bin/ffmpeg.exe',
            'ffprobe.binaries' => base_path() .'/bin/ffprobe.exe',
            Log::debug('Chay den day khong ?')

        ]);
        //$video = $this->FFMpeg->open('video.mpg');
        $video = $this->FFMpeg->open($videoUrl);
        Log::debug('Bat dau chay ?');

        $clip = $video->clip(Coordinate\TimeCode::fromSeconds(30), Coordinate\TimeCode::fromSeconds(15));
        //$clip->filters()->resize(new Coordinate\Dimension(320, 240), Filters\Video\ResizeFilter::RESIZEMODE_INSET, true);
        Log::debug('Truoc khi save ?');
        $obj = new X264('libmp3lame', 'libx264');
        $clip->save($obj, $fullname);
        Log::debug('Chay den day khong FRAME ?');
        //return $this->videoObject;

        //Log::debug('Finish');
    }
    public function createVideo() {
        $videoUrl = public_path('uploads\avi.avi');
        $this->FFMpeg = FFMpeg::create([
            'ffmpeg.binaries'  => 'D:\xampp\htdocs\Newfolder\ffmpeg\bin\ffmpeg.exe',
            'ffprobe.binaries' => 'D:\xampp\htdocs\Newfolder\ffmpeg\bin\ffprobe.exe',
            'timeout'          => 360000, // The timeout for the underlying process
            'ffmpeg.threads'   => 16,   // The number of threads that FFMpeg should use
            Log::debug('Chay den day khong ?')

        ]);
        //$video = $this->FFMpeg->open('video.mpg');
        $video = $this->FFMpeg->open($videoUrl);
        return $video;
    }

    public  function getThum(){
        return view('getThum');
    }
    public function postThum(Request $request){
        if(isset($_POST['submit'])){
            $ffmpeg = "D:\\xampp\\htdocs\\new\\Newfolder\\ffmpeg\\bin\\ffmpeg";
            $videoURL = public_path('uploads/demo.mp4');
            $videoFile = $_FILES["video"]["tmp_name"];
            $video_name = $request->video;

            $fileName = 'movie.jpg';
            $size = "120x90";
            $getFromSecond = 5;
            for ($num = 1 ; $num <= 3 ; $num ++){
                $intarval = $num * 3 ;
                shell_exec("$ffmpeg -i $video_name -an -ss $intarval -s $size $num.jpg");
                echo "Thum create - $num.jpg";
            }

            $cmd = "$ffmpeg -i $video_name -an -ss $intarval -s $size $num.jpg";
            // return $cmd;
            if (!shell_exec($cmd)){
                echo "Thum create";
            }
            else {
                echo "no ";
            }
        }
        return "sds";

    }
    public function handle(){
        $tempDir         = '/tmp/vid-concat/'; // temporary directory
        $start           = 20; // when to start
        $extension       = 'mp4';
        $snippetDuration = 2; // duration for each snipper
        $snippetCount    = 5; // snippet count
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'timeout'          => 360000,
            'ffmpeg.threads'   => 12,
        ]);
        $ffprobe = FFProbe::create([
            'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'timeout'          => 360000,
            'ffmpeg.threads'   => 12,
        ]);
        $storageUrl = 'D:\xampp\htdocs\Newfolder\ffmpeg\public\uploads\thumbnails';
        $filename = uniqid(rand(), true);
        $fullname = "{$storageUrl}/{$filename}".".mp4";

        $input    = public_path('uploads\alan.mp4');
        $file     = $ffmpeg->open($input);
        $duration = $ffprobe->format($input)->get('duration');
        $length   = round($duration);
        $interval = floor(($length - $start) / $snippetCount);
        $output   = $fullname;
        $format   = new X264('libmp3lame', 'libx264');
        // create snippets  (5x 2sec)
        mkdir($tempDir);
        $snippets = [];
        for ($i = 0; $i < $snippetCount; $i++) {
            $file->filters()->clip(TimeCode::fromSeconds($start), TimeCode::fromSeconds($snippetDuration));
            $file->save($format, $tempDir . '-' . $i . '-.' . $extension);
            $snippets[] = $tempDir . '-' . $i . '-.' . $extension;
            $start += $interval;
        }
        // concat the snippets into 1 $destinationFile
        $file = $ffmpeg->open($input);
        $file
            ->concat($snippets)
            ->saveFromSameCodecs($output, true);
        // remove temp files

        foreach ($snippets as $snippet) {
            unlink($snippet);
        }
        rmdir($tempDir);
    }
}
