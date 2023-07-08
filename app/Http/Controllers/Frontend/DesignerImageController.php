<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DesignerImage;
use Illuminate\Support\Facades\Auth;

class DesignerImageController extends Controller
{
    public function index(){
        $designer_images = DesignerImage::where('user_id',Auth::id())->paginate(10);
        return view('frontend.designer.images',compact('designer_images'));
    }

    public function store(Request $request){
        $user = Auth::user();

        $designer_image = new DesignerImage;
        $designer_image->image = $request->design->store('uploads/designers/'.$user->store_name.'/my-designes');
        $designer_image->user_id = $user->id;
        $designer_image->save();
        
        alert('Uploaded Successfully','','success');
        return redirect()->route('frontend.designer-images.index');
    }

    public function delete($id){
        $designer_image = DesignerImage::findOrFail($id);
        $designer_image->delete();
        alert('Deleted Successfully','','success');
        return redirect()->route('frontend.designer-images.index');
    }
}
