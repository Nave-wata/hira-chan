<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DepartmentThreads;
use App\Models\CollegeYearThreads;
use App\Models\ClubThreads;
use App\Models\LectureThreads;
use App\Models\JobHuntingThreads;
use App\Models\User;
use App\Models\Hub;
use App\Models\ThreadCategorys;
use App\Models\Likes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class jQuery_ajax extends Controller
{
    public $thread_id;
    public $user_email;

    public function get_allRow(Request $request)
    {
        $this->user_email = $request->user()->email;
        $this->thread_id = $request->table;

        $thread = Hub::where('thread_id', '=', $this->thread_id)->first();


        switch ($thread->thread_category_type) {
            case '学科':
                return DepartmentThreads::select(
                    'department_threads.*',
                    DB::raw('COUNT(likes1.user_email) AS count_user'),
                    DB::raw('COALESCE((likes2.user_email), 0) AS user_like')
                )
                    ->leftjoin('likes AS likes1', function ($join) {
                        $join
                            ->where('likes1.thread_id', '=', $this->thread_id)
                            ->whereColumn('likes1.message_id', '=', 'department_threads.message_id');
                    })
                    ->leftjoin('likes AS likes2', function ($join) {
                        $join
                            ->where('likes2.thread_id', '=', $this->thread_id)
                            ->where('likes2.user_email', '=', $this->user_email)
                            ->whereColumn('likes2.message_id', '=', 'department_threads.message_id');
                    })
                    ->where('department_threads.thread_id', '=', $this->thread_id)
                    ->groupBy('department_threads.message_id')
                    ->get();

            case '学年':
                return CollegeYearThreads::select(
                    'college_year_threads.*',
                    DB::raw('COUNT(likes1.user_email) AS count_user'),
                    DB::raw('COALESCE((likes2.user_email), 0) AS user_like')
                )
                    ->leftjoin('likes AS likes1', function ($join) {
                        $join
                            ->where('likes1.thread_id', '=', $this->thread_id)
                            ->whereColumn('likes1.message_id', '=', 'college_year_threads.message_id');
                    })
                    ->leftjoin('likes AS likes2', function ($join) {
                        $join
                            ->where('likes2.thread_id', '=', $this->thread_id)
                            ->where('likes2.user_email', '=', $this->user_email)
                            ->whereColumn('likes2.message_id', '=', 'college_year_threads.message_id');
                    })
                    ->where('college_year_threads.thread_id', '=', $this->thread_id)
                    ->groupBy('college_year_threads.message_id')
                    ->get();

            case '部活':
                return ClubThreads::select(
                    'club_threads.*',
                    DB::raw('COUNT(likes1.user_email) AS count_user'),
                    DB::raw('COALESCE((likes2.user_email), 0) AS user_like')
                )
                    ->leftjoin('likes AS likes1', function ($join) {
                        $join
                            ->where('likes1.thread_id', '=', $this->thread_id)
                            ->whereColumn('likes1.message_id', '=', 'club_threads.message_id');
                    })
                    ->leftjoin('likes AS likes2', function ($join) {
                        $join
                            ->where('likes2.thread_id', '=', $this->thread_id)
                            ->where('likes2.user_email', '=', $this->user_email)
                            ->whereColumn('likes2.message_id', '=', 'club_threads.message_id');
                    })
                    ->where('club_threads.thread_id', '=', $this->thread_id)
                    ->groupBy('club_threads.message_id')
                    ->get();

            case '授業':
                return LectureThreads::select(
                    'lecture_threads.*',
                    DB::raw('COUNT(likes1.user_email) AS count_user'),
                    DB::raw('COALESCE((likes2.user_email), 0) AS user_like')
                )
                    ->leftjoin('likes AS likes1', function ($join) {
                        $join
                            ->where('likes1.thread_id', '=', $this->thread_id)
                            ->whereColumn('likes1.message_id', '=', 'lecture_threads.message_id');
                    })
                    ->leftjoin('likes AS likes2', function ($join) {
                        $join
                            ->where('likes2.thread_id', '=', $this->thread_id)
                            ->where('likes2.user_email', '=', $this->user_email)
                            ->whereColumn('likes2.message_id', '=', 'lecture_threads.message_id');
                    })
                    ->where('lecture_threads.thread_id', '=', $this->thread_id)
                    ->groupBy('lecture_threads.message_id')
                    ->get();

            case '就職':
                return JobHuntingThreads::select(
                    'job_hunting_threads.*',
                    DB::raw('COUNT(likes1.user_email) AS count_user'),
                    DB::raw('COALESCE((likes2.user_email), 0) AS user_like')
                )
                    ->leftjoin('likes AS likes1', function ($join) {
                        $join
                            ->where('likes1.thread_id', '=', $this->thread_id)
                            ->whereColumn('likes1.message_id', '=', 'job_hunting_threads.message_id');
                    })
                    ->leftjoin('likes AS likes2', function ($join) {
                        $join
                            ->where('likes2.thread_id', '=', $this->thread_id)
                            ->where('likes2.user_email', '=', $this->user_email)
                            ->whereColumn('likes2.message_id', '=', 'job_hunting_threads.message_id');
                    })
                    ->where('job_hunting_threads.thread_id', '=', $this->thread_id)
                    ->groupBy('job_hunting_threads.message_id')
                    ->get();

            default:
                return null;
        }
    }

    public function send_Row(Request $request)
    {
        $special_character_set = array(
            "&" => "&amp;",
            "<" => "&lt;",
            ">" => "&gt;",
            " " => "&ensp;",
            "　" => "&emsp;",
            "\n" => "<br>",
            "\t" => "&ensp;&ensp;"
        );

        foreach ($special_character_set as $key => $value) {
            $message = str_replace($key, $value, $request->message);
        }

        $thread = Hub::where('thread_id', '=', $request->table)->first();

        switch ($thread->thread_category_type) {
            case '学科':
                $message_id = DepartmentThreads::where('thread_id', '=', $request->table)->max('message_id');
                if ($message_id == NULL) {
                    $message_id = 0;
                }
                DepartmentThreads::create([
                    'thread_id' => $request->table,
                    'message_id' => $message_id + 1,
                    'user_name' => $request->user()->name,
                    'user_email' => $request->user()->email,
                    'message' => $message
                ]);
                break;

            case '学年':
                $message_id = CollegeYearThreads::where('thread_id', '=', $request->table)->max('message_id');
                if ($message_id == NULL) {
                    $message_id = 0;
                }
                CollegeYearThreads::create([
                    'thread_id' => $request->table,
                    'message_id' => $message_id + 1,
                    'user_name' => $request->user()->name,
                    'user_email' => $request->user()->email,
                    'message' => $message
                ]);
                break;

            case '部活':
                $message_id = ClubThreads::where('thread_id', '=', $request->table)->max('message_id');
                if ($message_id == NULL) {
                    $message_id = 0;
                }
                ClubThreads::create([
                    'thread_id' => $request->table,
                    'message_id' => $message_id + 1,
                    'user_name' => $request->user()->name,
                    'user_email' => $request->user()->email,
                    'message' => $message
                ]);
                break;

            case '授業':
                $message_id = LectureThreads::where('thread_id', '=', $request->table)->max('message_id');
                if ($message_id == NULL) {
                    $message_id = 0;
                }
                LectureThreads::create([
                    'thread_id' => $request->table,
                    'message_id' => $message_id + 1,
                    'user_name' => $request->user()->name,
                    'user_email' => $request->user()->email,
                    'message' => $message
                ]);
                break;

            case '就職':
                $message_id = JobHuntingThreads::where('thread_id', '=', $request->table)->max('message_id');
                if ($message_id == NULL) {
                    $message_id = 0;
                }
                JobHuntingThreads::create([
                    'thread_id' => $request->table,
                    'message_id' => $message_id + 1,
                    'user_name' => $request->user()->name,
                    'user_email' => $request->user()->email,
                    'message' => $message
                ]);
                break;

            default:
                break;
        }
    }

    public function create_thread(Request $request)
    {
        $uuid = str_replace('-', '', Str::uuid());

        $category = ThreadCategorys::where('category_name', '=', $request->thread_category)->first();

        Hub::create([
            'thread_id' => $uuid,
            'thread_name' => $request->table,
            'thread_category' => $request->thread_category,
            'thread_category_type' => $category->category_type,
            'user_email' => $request->user()->email
        ]);
    }

    public function like(Request $request)
    {
        Likes::insertOrIgnore([
            'thread_id' => $request->thread_id,
            'message_id' => $request->message_id,
            'user_email' => $request->user()->email,
            'created_at' => now(),
        ]);
    }

    public function unlike(Request $request)
    {
        Likes::where('thread_id', '=', $request->thread_id)
            ->where('message_id', '=', $request->message_id)
            ->where('user_email', '=', $request->user()->email)
            ->delete();
    }

    public function delete_thread(Request $request)
    {
        DB::statement('DROP TABLE ?', [$request->thread_id]);
        Hub::where('thread_id', '=', $request->thread_id)->delete();

        return null;
    }

    public function edit_thread(Request $request)
    {
        Hub::where('threead_id', '=', $request->thread_id)
            ->update([
                'thread_name' => $request->thread_name
            ]);

        return null;
    }

    public function delete_message(Request $request)
    {
        DB::connection('mysql')
            ->table($request->thread_id)
            ->where('no', '=', $request->message_id)
            ->update([
                'is_validity' => 0
            ]);

        return null;
    }

    public function restore_message(Request $request)
    {
        DB::connection('mysql')
            ->table($request->threead_id)
            ->where('no', '=', $request->message_id)
            ->update([
                'is_validity' => 1
            ]);

        return null;
    }

    public function page_thema(Request $request)
    {
        $page_thema = $request->page_thema;
        $user = User::find($request->user()->id);
        $user->thema = $page_thema;
        $user->save();

        return null;
    }
}
