<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Quote;
use App\Models\Testimonial;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCmsController extends Controller
{
    // ARTICLES & NEWS
    public function articles()
    {
        $articles = Article::with('category')->latest()->paginate(10);
        $categories = ArticleCategory::all();
        return view('admin.cms.articles', compact('articles', 'categories'));
    }

    public function storeArticle(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:article,news'],
            'category_id' => ['nullable', 'exists:article_categories,id'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'author' => ['required', 'string'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        $validated['published_at'] = $validated['status'] === 'published' ? now() : null;

        $article = Article::create($validated);

        AuditLogService::log(
            action: 'create_article',
            module: 'CMS',
            recordType: 'Article',
            recordId: (string) $article->id,
            changes: $validated
        );

        return back()->with('success', 'Konten berhasil disimpan.');
    }

    // QUOTES
    public function quotes()
    {
        $quotes = Quote::latest()->paginate(10);
        return view('admin.cms.quotes', compact('quotes'));
    }

    public function storeQuote(Request $request)
    {
        $validated = $request->validate([
            'quote' => ['required', 'string'],
            'author' => ['required', 'string'],
            'source' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $validated['published_at'] = $validated['status'] === 'published' ? now() : null;
        $quote = Quote::create($validated);

        return back()->with('success', 'Kutipan Ruhiologi berhasil ditambahkan.');
    }

    // TESTIMONIALS
    public function testimonials()
    {
        $testimonials = Testimonial::latest()->paginate(10);
        return view('admin.cms.testimonials', compact('testimonials'));
    }

    public function updateTestimonialStatus(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $testimonial->update($validated);

        return back()->with('success', 'Status testimonial berhasil diperbarui.');
    }

    // PRODUCTS (STORE CATALOG)
    public function products()
    {
        $products = Product::with('category')->latest()->paginate(10);
        $categories = ProductCategory::all();
        return view('admin.cms.products', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:product_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string'],
            'isbn' => ['nullable', 'string'],
            'publisher' => ['nullable', 'string'],
            'year' => ['nullable', 'integer'],
            'pages' => ['nullable', 'integer'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        $product = Product::create($validated);

        AuditLogService::log(
            action: 'create_product',
            module: 'Store',
            recordType: 'Product',
            recordId: (string) $product->id,
            changes: $validated
        );

        return back()->with('success', 'Buku/Produk berhasil ditambahkan ke katalog.');
    }
}
