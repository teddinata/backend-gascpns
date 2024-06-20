<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $perPage = $request->get('per_page', 10); // Default to 10 if not specified
        $notifications = Notification::latest()->with('user')->paginate($perPage);

        return view('admin.notifications.index', compact('notifications', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // Validate icon as an image
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('icons', $filename, 'public');
            $iconPath = $path;
        }

        $users = User::all();
        foreach ($users as $user) {
            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->title = $request->title;
            $notification->message = $request->message;
            $notification->type = 'information';
            $notification->link = $request->link;
            $notification->icon = $iconPath;
            $notification->save();
        }

        return redirect()->route('dashboard.notifications.index')->with('success', 'Notification created successfully and sent to all users');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        if ($notification->icon) {
            Storage::delete('public/' . $notification->icon);
        }
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully');
    }
}
