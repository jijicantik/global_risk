<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Port;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->is_admin) {
                abort(403, 'Unauthorized access. Only administrators can view this page.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::orderBy('name')->get();
        $ports = Port::orderBy('name')->get();
        $articles = Article::with('author')->orderBy('created_at', 'desc')->get();

        return view('admin.index', compact('users', 'ports', 'articles'));
    }

    // User Management
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin'),
        ]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        // Don't let user delete themselves
        if (auth()->id() == $id) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    // Port Management
    public function storePort(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:ports,code',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'country_code' => 'required|string|size:2',
            'country_name' => 'required|string|max:255',
        ]);

        Port::create($request->all());
        return redirect()->back()->with('success', 'Port added successfully.');
    }

    public function updatePort(Request $request, $id)
    {
        $port = Port::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:ports,code,' . $id,
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'country_code' => 'required|string|size:2',
            'country_name' => 'required|string|max:255',
        ]);

        $port->update($request->all());
        return redirect()->back()->with('success', 'Port updated successfully.');
    }

    public function deletePort($id)
    {
        Port::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Port deleted successfully.');
    }

    // Article Management
    public function storeArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:Draft,Published',
        ]);

        Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'status' => $request->status,
            'author_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Article added successfully.');
    }

    public function updateArticle(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:Draft,Published',
        ]);

        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Article updated successfully.');
    }

    public function deleteArticle($id)
    {
        Article::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Article deleted successfully.');
    }
}
