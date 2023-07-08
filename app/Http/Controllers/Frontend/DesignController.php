<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Design; 
use App\Models\Mockup; 
use App\Models\User; 
use App\Models\UserAlert;  
use App\Jobs\SendPushNotification;
use App\Models\DesignImage; 
use Illuminate\Support\Facades\Storage;  
use App\Models\DesignerImage; 
use Illuminate\Support\Facades\Auth;

class DesignController extends Controller
{
    
    public function start($id){
        $designer_images = DesignerImage::where('user_id',Auth::id())->get();
        $mockup = Mockup::findOrFail($id); 
        return view('frontend.designer.designs.start',compact('designer_images','mockup'));
    }  
    
    public function index(){  
        $designs = Design::with('design_images','product')->where('user_id',Auth::id())->orderBy('created_at','desc');
        $designs_calculations = designs_calculations($designs->get());
        $pending =  $designs_calculations['pending'];
        $available =  $designs_calculations['available'];
        $total =  $designs_calculations['pending'] + $designs_calculations['available'];
        $designs = $designs->paginate(8);

        return view('frontend.designer.designs.index',compact('designs','pending','available','total'));
    }

    public function edit(Design $design){ 
        $mockup = Mockup::findOrFail($design->mockup_id);
        $designer_images = DesignerImage::where('user_id',Auth::id())->get();
        return view('frontend.designer.designs.edit',compact('design','mockup','designer_images'));
    }

    public function store(Request $request){
        $mockup = Mockup::find($request->mockup_id);
        $design = new Design;
        $design->dataset1 = json_encode($request->dataset1);
        $design->dataset2 = json_encode($request->dataset2);
        $design->dataset3 = json_encode($request->dataset3);
        $design->user_id = Auth::id();
        $design->design_name = $request->design_name;
        $design->profit = $request->profit;
        $design->mockup_id = $request->mockup_id; 
        $design->colors = $mockup->colors ;
        $design->save();

        $store_name = auth()->user()->designer ? auth()->user()->designer()->store_name : 'no-name-store'; 
        $incrementer = 1;
        foreach($request->images as $key => $image){
            $image = explode(";",$image)[1];
            $image = explode(",",$image)[1];
            $image = str_replace(" ","+",$image);
            $image = base64_decode($image);
            $path = 'uploads/designers/'
                                . $store_name
                                .'/collections/'
                                .strtotime(date('Y-m-d H:i:s'))
                                .'-'
                                .$request->design_name
                                .'-'
                                .$incrementer
                                .'.png';
            file_put_contents('public/'.$path,$image);
            $design_image = new DesignImage;
            $design_image->image = $path;
            $design_image->design_id = $design->id;
            $explode = explode("-",$key);
            $design_image->color = $explode[0];
            $design_image->preview = $explode[1];
            $design_image->save();
            $incrementer++;
        }
        
        $title = Auth::user()->name;
        $body = 'تصميم جديد';
        $userAlert = UserAlert::create([
            'alert_text' => $title . ' ' . $body . ' من ',
            'alert_link' => route('admin.mockups.index'),
            'type' => 'design', 
        ]);   
        // only users has permission design_show can see the notification
        $allowed_users_ids = User::where('user_type','staff')->whereHas('roles.permissions',function($query){
            $query->where('permissions.title','design_show');
        })->pluck('id')->all();
        $userAlert->users()->sync($allowed_users_ids); 

        $site_settings = get_site_setting();
        // send push notification to users has the permission and has a device_token to send via firebase
        $tokens = User::whereNotNull('device_token')->whereHas('roles.permissions',function($query){
            $query->where('permissions.title','design_show');
        })->where('user_type','staff')->pluck('device_token')->all();   
        SendPushNotification::dispatch($title, $body, $tokens,route('admin.mockups.index'),$site_settings);  // job for sending push notification 

        return 1;
    }

    public function update(Request $request, $id){
        $i = 1;

        $design = Design::findOrFail($id);
        $design->dataset1 = json_encode($request->dataset1);
        $design->dataset2 = json_encode($request->dataset2);
        $design->dataset3 = json_encode($request->dataset3);
        $design->design_name = $request->design_name;
        $design->profit = $request->profit;
        $design->colors = json_encode($request->colors);
        $design->save();

        $old_design_image = DesignImage::where('design_id',$design->id)->get();
        foreach($old_design_image as $raw){
            Storage::delete($raw->image);
            $raw->delete();
        }

        foreach($request->images as $key => $image){
            $image = explode(";",$image)[1];
            $image = explode(",",$image)[1];
            $image = str_replace(" ","+",$image);
            $image = base64_decode($image);
            $path = 'uploads/designers/'
                                .auth()->user()->store_name
                                .'/collections/'
                                .strtotime(date('Y-m-d H:i:s'))
                                .'-'
                                .$request->design_name
                                .'-'
                                .$i
                                .'.png';
            file_put_contents('public/'.$path,$image);
            $design_image = new DesignImage;
            $design_image->image = $path;
            $design_image->design_id = $design->id;
            $explode = explode("-",$key);
            $design_image->color = $explode[0];
            $design_image->preview = $explode[1];
            $design_image->save();
            $i++;
        }
        return 1;
    }

    public function destroy($id){
        $design = Design::findOrFail($id);
        $old_design_image = DesignImage::where('design_id',$design->id)->get();
        foreach($old_design_image as $raw){
            Storage::delete($raw->image);
            $raw->delete();
        }
        $design->delete();
        alert('Success Deleted!','','success');
        return redirect()->route('frontend.designer-images.index');
    }

}
