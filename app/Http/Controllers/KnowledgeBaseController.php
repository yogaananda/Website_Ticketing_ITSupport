<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\Auth;

class KnowledgeBaseController extends Controller
{
    public function index()
    {
        $articles = KnowledgeBase::with('author')->latest()->get();
        return view('it_knowledge_base', compact('articles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
        ]);

        KnowledgeBase::create([
            'title' => $request->title,
            'category' => $request->category,
            'content' => $request->content,
            'author_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Buku Panduan berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $article = KnowledgeBase::findOrFail($id);
        $article->delete();
        
        return redirect()->back()->with('success', 'Buku Panduan berhasil dihapus.');
    }
}
