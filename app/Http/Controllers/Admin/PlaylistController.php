<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPlaylistRequest;
use App\Http\Requests\StorePlaylistRequest;
use App\Http\Requests\UpdatePlaylistRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlaylistController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('playlist_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.playlists.index');
    }

    public function create()
    {
        abort_if(Gate::denies('playlist_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.playlists.create');
    }

    public function store(StorePlaylistRequest $request)
    {
        $playlist = Playlist::create($request->all());

        return redirect()->route('admin.playlists.index');
    }

    public function edit(Playlist $playlist)
    {
        abort_if(Gate::denies('playlist_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.playlists.edit', compact('playlist'));
    }

    public function update(UpdatePlaylistRequest $request, Playlist $playlist)
    {
        $playlist->update($request->all());

        return redirect()->route('admin.playlists.index');
    }

    public function show(Playlist $playlist)
    {
        abort_if(Gate::denies('playlist_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.playlists.show', compact('playlist'));
    }

    public function destroy(Playlist $playlist)
    {
        abort_if(Gate::denies('playlist_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $playlist->delete();

        return back();
    }

    public function massDestroy(MassDestroyPlaylistRequest $request)
    {
        $playlists = Playlist::find(request('ids'));

        foreach ($playlists as $playlist) {
            $playlist->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
