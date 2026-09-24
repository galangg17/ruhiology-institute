<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Testimonial;
use App\Models\TrainingProgram;

class HomeController extends Controller
{
    public function index()
    {
        $trainings = TrainingProgram::where('status', 'published')->with('batches')->take(3)->get();
        $books = Product::where('status', 'published')->take(4)->get();
        $articles = Article::where('status', 'published')->latest('published_at')->take(4)->get();
        $quotes = Quote::where('status', 'published')->latest('published_at')->take(4)->get();
        $testimonials = Testimonial::where('status', 'approved')->take(3)->get();

        $stats = [
            'total_provinces' => \App\Models\Province::count(),
            'total_institutions' => \App\Models\University::count() + \App\Models\School::count() + \App\Models\Institution::count(),
            'total_participants' => \App\Models\Participant::count(),
            'total_submissions' => \App\Models\AssessmentSubmission::count(),
        ];

        return view('public.home', compact('trainings', 'books', 'articles', 'quotes', 'testimonials', 'stats'));
    }

    public function aboutRuhiology()
    {
        return view('public.about_ruhiology');
    }

    public function aboutInstitute()
    {
        return view('public.about_institute');
    }
}
