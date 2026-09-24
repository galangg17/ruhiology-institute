<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Quote;
use Illuminate\Http\Request;

class PublicCmsController extends Controller
{
    public function articles(Request $request)
    {
        $categories = ArticleCategory::all();
        $query = Article::where('status', 'published')->where('type', 'article')->with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $articles = $query->latest('published_at')->paginate(6);

        return view('public.cms.articles', compact('articles', 'categories'));
    }

    public function articleShow(string $slug)
    {
        $article = Article::where('slug', $slug)->with('category')->firstOrFail();
        $recentArticles = Article::where('status', 'published')
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.cms.article_show', compact('article', 'recentArticles'));
    }

    public function news()
    {
        $newsList = Article::where('status', 'published')
            ->where('type', 'news')
            ->latest('published_at')
            ->paginate(6);

        return view('public.cms.news', compact('newsList'));
    }

    public function newsShow(string $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        return view('public.cms.article_show', compact('article'));
    }

    public function quotes()
    {
        $quotes = Quote::where('status', 'published')->latest('published_at')->paginate(12);

        return view('public.cms.quotes', compact('quotes'));
    }
}
