<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;

class TagController extends Controller
{

    public function index()
    {
        $tags = Tag::orderBy('created_at', 'desc')->get();
        return view('dashboard.tags.index', [
            'tags' => $tags
        ]);
    }

    public function store(TagRequest $request)
    {
        Tag::create($request->all());
        return redirect()->route('tags.index')->with('message', 'Tag registada com sucesso');
    }

    public function edit(Tag $tag)
    {
        return view('dashboard.tags.edit', [
            'tag' => $tag
        ]);
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update($request->all());
        return redirect()->route('tags.index')->with('message', 'Tag atualizada com sucesso');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index')->with('message', 'Tag excluída com sucesso');
    }
}
