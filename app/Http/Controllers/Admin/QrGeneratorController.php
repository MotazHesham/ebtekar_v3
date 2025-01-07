<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrGeneratorController extends Controller
{
    public function index(){
        return view('admin.qrGenerator.index');
    }

    public function generate(Request $request){ 
        $generated = true; 
        if ($request->input('photo', false)) { 

            if (App::environment('production')) {
                $photo = storage_path('tmp/uploads/' . basename($request->input('photo'))); 

                $qr = base64_encode(QrCode::format('png')->merge($photo, 0.3, true)
                            ->size((int)$request->size)->errorCorrection('H')
                            ->generate($request->text));
            }else{
                $qr = QrCode::size((int)$request->size)->generate($request->text);
            }

        }elseif($request->has('color')){ 
            $hex = $request->color; 
            $hex = ltrim($hex, '#'); 
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            
            if (App::environment('production')) {
                $qr = base64_encode(QrCode::format('png')->size((int)$request->size)->backgroundColor($r,$g,$b)->generate($request->text));
            }else{
                $qr = QrCode::size((int)$request->size)->backgroundColor($r,$g,$b)->generate($request->text);
            }

        }else{
            if (App::environment('production')) {
                $qr = base64_encode(QrCode::format('png')->size((int)$request->size)->generate($request->text));
            }else{
                $qr = QrCode::size((int)$request->size)->generate($request->text);
            }
        }
        return view('admin.qrGenerator.index',compact('qr','generated'));
    }
}
