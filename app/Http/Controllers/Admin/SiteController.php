<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::latest()->get();
        return view('admin.sites.index', compact('sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'site_code'   => 'required|string|max:100|unique:sites,site_code',
            'description' => 'nullable|string|max:255',
        ]);

        Site::create($request->only('site_code', 'description'));

        return back()->with('success', 'Site added successfully');
    }

    public function update(Request $request, Site $site)
    {
        $request->validate([
            'site_code'   => 'required|string|max:100|unique:sites,site_code,' . $site->id,
            'description' => 'nullable|string|max:255',
        ]);

        $site->update($request->only('site_code', 'description'));

        return back()->with('success', 'Site updated successfully');
    }

    public function destroy(Site $site)
    {
        $site->delete();
        return back()->with('success', 'Site deleted successfully');
    }
}
