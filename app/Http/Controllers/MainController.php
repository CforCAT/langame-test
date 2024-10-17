<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsResource;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(Request $request): View
    {
        return view('main');
    }

    public function getNews(Request $request): JsonResource
    {
        $news = News::orderBy('published_at', 'desc');

        if ($request->input('query')) {
            $news = $news->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . Str::lower($request->input('query')) . '%')
                    ->orWhere('description', 'like', '%' . Str::lower($request->input('query')) . '%');
            });
        }

        if ($request->input('after')) {
            $news->where('published_at', '<', Carbon::parse($request->input('after'))->format('Y-m-d H:i:s'));
        }

        if ($request->input('before')) {
            $news->where('published_at', '>', Carbon::parse($request->input('before'))->format('Y-m-d H:i:s'));
        } else {
            $news->limit($request->input('count', 5));
        }

        $news = $news->get();

        NewsResource::withoutWrapping();
        return NewsResource::collection($news->all());
    }
}
