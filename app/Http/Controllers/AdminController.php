<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::orderBy('created_at', 'desc')->paginate(2);

        return view('admin.index', [
            'users' => $users
        ]);
    }

    public function codes(Request $request): View
    {
        $codes = Code::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.codes', [
            'codes' => $codes
        ]);
    }

    public function news(Request $request): View
    {
        $news = News::orderBy('published_at', 'desc')->paginate(10);

        return view('admin.news', [
            'news' => $news
        ]);
    }
}
