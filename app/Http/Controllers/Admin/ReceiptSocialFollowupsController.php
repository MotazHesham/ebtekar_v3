<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Models\ReceiptSocial;
use App\Models\ReceiptSocialFollowup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ReceiptSocialFollowupsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        $receipt = ReceiptSocial::with(['followups.created_by', 'followups.media'])->findOrFail($request->input('receipt_social_id'));
        return view('admin.receiptSocials.followups_list', compact('receipt'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receipt_social_id' => 'required|exists:receipt_socials,id',
            'comment' => 'nullable|string',
        ]);

        $followup = ReceiptSocialFollowup::create([
            'receipt_social_id' => $request->input('receipt_social_id'),
            'comment' => $request->input('comment'),
            'created_by_id' => Auth::id(),
        ]);

        foreach ((array) $request->input('files', []) as $file) {
            $followup->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $followup->id]);
        }

        return response()->json(['status' => 'ok'], Response::HTTP_CREATED);
    }

    public function edit(ReceiptSocialFollowup $followup)
    {
        $followup->load('created_by', 'media');
        return view('admin.receiptSocials.followups_edit', compact('followup'));
    }

    public function update(Request $request, ReceiptSocialFollowup $followup)
    {
        $request->validate([
            'comment' => 'nullable|string',
        ]);

        $followup->update([
            'comment' => $request->input('comment'),
        ]);

        if ($request->has('files')) {
            $existing = $followup->files->pluck('file_name')->toArray();
            foreach ($followup->files as $media) {
                if (! in_array($media->file_name, (array) $request->input('files', []))) {
                    $media->delete();
                }
            }
            foreach ((array) $request->input('files', []) as $file) {
                if (! in_array($file, $existing)) {
                    $followup->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('files');
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function destroy(ReceiptSocialFollowup $followup)
    {
        $followup->delete();
        return response()->json(['status' => 'ok']);
    }
}


