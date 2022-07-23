<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use FFMPEG\FFMPEG;
use Illuminate\Support\Facades\File;

class VideoController extends Controller
{
    public function video(Request $request){
        $video=new Video;
        $file=$request->file('file')->store('VideoData');
        $location=base_path(str_replace('/', '\\', "storage/app/".$file));
        $thumbnail=base_path(str_replace('/', '\\', "storage/app/VideoData/output.png"));
        $gif=base_path(str_replace('/', '\\', "storage/app/VideoData/output.gif"));
        $shortVideo=base_path(str_replace('/', '\\', "storage/app/VideoData/output.mp4"));
        shell_exec("ffmpeg -i " . $location . " -vf fps=1/30 ". $thumbnail);
        shell_exec("ffmpeg -i " . $location . " -ss 00:00:00 -t 00:00:02 ". $gif);
        shell_exec("ffmpeg -i " . $location . " -ss 00:00:00 -t 00:01:00 ". $shortVideo);
        $video->thumbnail=$thumbnail;
        $video->gif=$gif;
        $video->shortVideo=$shortVideo;
        $video->save();
        File::delete(base_path(str_replace('/', '\\', "storage/app/".$file)));
        return $video;
    }
}
