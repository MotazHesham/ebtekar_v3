<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Mockup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MockupController extends Controller
{
    
    public function categories(){
        $site_settings = get_site_setting();
        $categories = Category::where('design',1)->where('website_setting_id',$site_settings->id)->paginate(9);
        return view('frontend.designer.mockups.categories',compact('categories'));
    }

    public function mockups($id){
        $path = 'public/uploads/designers/' . Auth::user()->store_name;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            mkdir($path.'/my-designes', 0777, true);
            mkdir($path.'/collections', 0777, true);
        }
        $mockups = Mockup::where('category_id',$id)->paginate(9); 
        if(count($mockups) > 0){
            return view('frontend.designer.mockups.mockups',compact('mockups'));
        }else{
            alert('No mockups avaliable right now For This Category','','warning');
            return redirect()->route('frontend.mockups.categories');
        }
    } 
}
