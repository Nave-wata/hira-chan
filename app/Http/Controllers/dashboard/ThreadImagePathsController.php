<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;

use App\Models\ThreadImagePaths;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ThreadImagePathsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $message_id
     *
     * @return void
     */
    public function store(Request $request, int $message_id)
    {
        if ($request->file('img')) {
            $img = Image::make($request->file('img'))->encode('jpg')->orientate()->save();

            $size = $img->filesize();
            $path = 'public/images/thread_message/' . str_replace('-', '', Str::uuid()) . '.jpg';
            Storage::put($path, $img);

            if ($path) {
                ThreadImagePaths::create([
                    'thread_id' => $request->table,
                    'message_id' => $message_id,
                    'user_email' => $request->user()->email,
                    'img_path' => $path,
                    'img_size' => $size
                ]);
            }

            $img->destroy();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ThreadImagePaths  $threadImagePaths
     * @return \Illuminate\Http\Response
     */
    public function show(ThreadImagePaths $threadImagePaths)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ThreadImagePaths  $threadImagePaths
     * @return \Illuminate\Http\Response
     */
    public function edit(ThreadImagePaths $threadImagePaths)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ThreadImagePaths  $threadImagePaths
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ThreadImagePaths $threadImagePaths)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ThreadImagePaths  $threadImagePaths
     * @return \Illuminate\Http\Response
     */
    public function destroy(ThreadImagePaths $threadImagePaths)
    {
        //
    }
}
