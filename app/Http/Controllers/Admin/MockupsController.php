<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use App\Http\Requests\MassDestroyMockupRequest;
use App\Http\Requests\StoreMockupRequest;
use App\Http\Requests\UpdateMockupRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Color;
use App\Models\Mockup;
use App\Models\MockupStock;
use App\Models\SubCategory;
use App\Models\SubSubCategory;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MockupsController extends Controller
{ 

    public function sku_combination_edit(Request $request)
    { 
        $mockup = Mockup::find($request->id);
        $mockup->load('stocks');

        $options = array();
        $colors_active = 0;
        if($request->has('colors')){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        $mockup_name = $request->name;
        $purchase_price = $request->purchase_price; 

        if($request->has('attribute_options')){
            foreach ($request->attribute_options as $key => $no) {
                $name = 'attribute_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('admin.mockups.sku_combinations_edit', compact('combinations', 'purchase_price','mockup_name','colors_active', 'mockup'));
    }
    
    public function sku_combination(Request $request)
    {
        $options = array();

        $colors_active = 0;
        if($request->has('colors')){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        $purchase_price = $request->purchase_price; 
        $mockup_name = $request->name;

        if($request->has('attribute_options')){
            foreach ($request->attribute_options as $key => $no) {
                $name = 'attribute_options_'.$no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('admin.mockups.sku_combinations', compact('combinations', 'purchase_price','colors_active','mockup_name'));
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('mockup_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Mockup::with(['category', 'sub_category', 'sub_sub_category'])->select(sprintf('%s.*', (new Mockup)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = false;
                $editGate      = 'mockup_edit';
                $deleteGate    = 'mockup_delete';
                $crudRoutePart = 'mockups';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            }); 
            $table->editColumn('preview_1', function ($row) {
                if($row->preview_1 != null) {
                    $prev_1 = json_decode($row->preview_1);
                    $image_1 = $prev_1 ? $prev_1->image  : '';   
                }
                if($row->preview_2 != null) {
                    $prev_2 = json_decode($row->preview_2);
                    $image_2 = $prev_2 ? $prev_2->image  : '';   
                }
                if($row->preview_3 != null) {
                    $prev_3 = json_decode($row->preview_3);
                    $image_3 = $prev_3 ? $prev_3->image  : '';   
                }
                $preview_1 = ' <img src="'.asset($image_1).'" alt="" class="img-thumbnail" height="70" width="70"> ';
                $preview_2 = isset($image_2) ? ' <img src="'.asset($image_2).'" alt="" class="img-thumbnail" height="70" width="70"> ' : '';
                $preview_3 = isset($image_3) ? ' <img src="'.asset($image_3).'" alt="" class="img-thumbnail" height="70" width="70"> ' : '';
                return $preview_1 . $preview_2 . $preview_3 ;
            });  
            $table->editColumn('colors', function ($row) {
                $colors = '';
                if ($row->colors != null){
                    $colors .= '<ul class="list-inline checkbox-color">';
                    foreach (json_decode($row->colors) as $key => $color){
                        $colors .= '<li style="width: 30px;height:30px;border-radius:50%;background:'.$color.'"></li>';
                    }  
                    $colors .='</ul>';
                } 
                return $colors;
            });
            $table->editColumn('attribute_options', function ($row) { 
                $attribute_options = '';
                if ($row->attribute_options != null){
                    foreach (json_decode($row->attribute_options) as $key => $attribute){
                        $attribute_options .= '<ul class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2">';
                        foreach ($attribute->values as $key => $value){
                            $attribute_options .= '<li>  <label>'. $value .'</label> </li>';
                        }
                        $attribute_options .= '</ul>';
                    }
                }
                return $attribute_options;
            });
            $table->editColumn('purchase_price', function ($row) {
                return $row->purchase_price ? $row->purchase_price : '';
            });
            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });

            $table->addColumn('sub_category_name', function ($row) {
                return $row->sub_category ? $row->sub_category->name : '';
            });

            $table->addColumn('sub_sub_category_name', function ($row) {
                return $row->sub_sub_category ? $row->sub_sub_category->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'preview_1', 'category', 'sub_category', 'sub_sub_category','colors','attribute_options']);

            return $table->make(true);
        }

        return view('admin.mockups.index');
    }

    public function create()
    {
        abort_if(Gate::denies('mockup_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''); 

        $colors = Color::pluck('name', 'code');

        $attributes = Attribute::pluck('name', 'id'); 
        
        return view('admin.mockups.create', compact('categories','colors','attributes'));
    }

    public function store(StoreMockupRequest $request)
    {
        
        if($request->hasFile('preview_1')){
            $preview_1 = $request->preview_1->store('uploads/mockups');
        }else{
            $preview_1 = null;
        }
        
        if($request->hasFile('preview_2')){
            $preview_2 = $request->preview_2->store('uploads/mockups');
        }else{
            $preview_2 = null;
        }
        
        if($request->hasFile('preview_3')){
            $preview_3 = $request->preview_3->store('uploads/mockups');
        }else{
            $preview_3 = null;
        }

        $prev_1 = [
            'image' => $preview_1,
            'left' => $request->left_preview_1,
            'top' => $request->top_preview_1,
            'height' => $request->height_preview_1,
            'width' => $request->width_preview_1,
            'name' => $request->name_preview_1,
        ];
        $prev_2 = [
            'image' => $preview_2,
            'left' => $request->left_preview_2,
            'top' => $request->top_preview_2,
            'height' => $request->height_preview_2,
            'width' => $request->width_preview_2,
            'name' => $request->name_preview_2,
        ];
        $prev_3 = [
            'image' => $preview_3,
            'left' => $request->left_preview_3,
            'top' => $request->top_preview_3,
            'height' => $request->height_preview_3,
            'width' => $request->width_preview_3,
            'name' => $request->name_preview_3,
        ];

        $category =  Category::find($request->category_id);
        if($category){
            $category->design = 1;
            $category->save();
        }
        $subcategory =  SubCategory::find($request->sub_category_id);
        if($subcategory){
            $subcategory->design = 1;
            $subcategory->save();
        }
        if($request->sub_sub_category_id != null){
            $subsubcategory =  SubSubCategory::find($request->sub_sub_category_id);
            if($subsubcategory){
                $subsubcategory->design = 1;
                $subsubcategory->save();
            }
        }
        
        $mockup = new Mockup;
        $mockup->preview_1 = json_encode($prev_1); 
        $mockup->preview_2 = $preview_2 ? json_encode($prev_2) : null; 
        $mockup->preview_3 = $preview_3 ? json_encode($prev_3) : null;
        $mockup->category_id = $request->category_id;
        $mockup->sub_category_id = $request->sub_category_id;
        $mockup->sub_sub_category_id = $request->sub_sub_category_id;
        $mockup->name = $request->name;
        $mockup->description = $request->description;
        $mockup->video_provider = $request->video_provider;
        $mockup->video_link = $request->video_link;
        $mockup->purchase_price = $request->purchase_price;
        
        $options = array();
        $attribute_options = array();

        if($request->has('colors')){ 
            $mockup->colors = json_encode($request->colors); 
            array_push($options, $request->colors);
        }else {
            $colors = array();
            $mockup->colors = json_encode($colors); 
        }


        $mockup->attributes = !empty($request->attribute_options) ?  json_encode($request->attribute_options) : json_encode(array()); 

        if($request->has('attribute_options')){
            foreach ($request->attribute_options as $key => $no) {
                $str = 'attribute_options_'.$no;

                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str]));

                array_push($attribute_options, $item);

                $name = 'attribute_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $mockup->attribute_options = json_encode($attribute_options);
        $mockup->save();

        //Generates the combinations of customer choice options
        $combinations = combinations($options);
        if(count($combinations[0]) > 0){
            
            foreach ($combinations as $key => $combination){
                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                    }else{
                        if($request->has('colors')){
                            $color_name = Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        }else{
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                
                
                $mockup_stock = new MockupStock();
                $mockup_stock->mockup_id = $mockup->id;
                $mockup_stock->variant = $str;
                $mockup_stock->price = $request['purchase_price_'.str_replace('.', '_', $str)];
                $mockup_stock->quantity = $request['quantity_'.str_replace('.', '_', $str)];
                $mockup_stock->save();
            }
        }
        //combinations end

        alert('Mockup added successfully','','success'); 
        return redirect()->route('admin.mockups.index');
    }

    public function edit(Mockup $mockup)
    {
        abort_if(Gate::denies('mockup_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 
        
        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), ''); 

        $colors = Color::pluck('name', 'code');

        $attributes = Attribute::pluck('name', 'id'); 

        $mockup->load('category', 'sub_category', 'sub_sub_category');

        return view('admin.mockups.edit', compact('categories', 'mockup', 'colors', 'attributes'));
    }

    public function update_mockup(UpdateMockupRequest $request)
    {
        
        $mockup = Mockup::findOrFail($request->id);
        
        if($request->hasFile('preview_1')){
            $preview_1 = $request->preview_1->store('uploads/mockups');
        }else{
            if($mockup->preview_1 != null){
                $preview_1 = json_decode($mockup->preview_1)->image;
            }else{
                $preview_1 = null;
            }
        }
        
        if($request->hasFile('preview_2')){
            $preview_2 = $request->preview_2->store('uploads/mockups');
        }else{
            if($mockup->preview_2 != null){
                $preview_2 = json_decode($mockup->preview_2)->image;
            }else{
                $preview_2 = null;
            }
        }
        
        if($request->hasFile('preview_3')){
            $preview_3 = $request->preview_3->store('uploads/mockups');
        }else{
            if($mockup->preview_3 != null){
                $preview_3 = json_decode($mockup->preview_3)->image;
            }else{
                $preview_3 = null;
            }
        }

        $prev_1 = [
            'image' => $preview_1,
            'left' => $request->left_preview_1,
            'top' => $request->top_preview_1,
            'height' => $request->height_preview_1,
            'width' => $request->width_preview_1,
            'name' => $request->name_preview_1,
        ];
        $prev_2 = [
            'image' => $preview_2,
            'left' => $request->left_preview_2,
            'top' => $request->top_preview_2,
            'height' => $request->height_preview_2,
            'width' => $request->width_preview_2,
            'name' => $request->name_preview_2,
        ];
        $prev_3 = [
            'image' => $preview_3,
            'left' => $request->left_preview_3,
            'top' => $request->top_preview_3,
            'height' => $request->height_preview_3,
            'width' => $request->width_preview_3,
            'name' => $request->name_preview_3,
        ];

        $mockup->preview_1 = json_encode($prev_1); 
        $mockup->preview_2 = $preview_2 ? json_encode($prev_2) : null; 
        $mockup->preview_3 = $preview_3 ? json_encode($prev_3) : null;
        $mockup->category_id = $request->category_id;
        $mockup->sub_category_id = $request->sub_category_id;
        $mockup->sub_sub_category_id = $request->sub_sub_category_id;
        $mockup->name = $request->name;
        $mockup->description = $request->description;
        $mockup->video_provider = $request->video_provider;
        $mockup->video_link = $request->video_link;
        $mockup->purchase_price = $request->purchase_price;

        $category =  Category::find($request->category_id);
        if($category){
            $category->design = 1;
            $category->save();
        }
        $subcategory =  SubCategory::find($request->sub_category_id);
        if($subcategory){
            $subcategory->design = 1;
            $subcategory->save();
        }
        if($request->subsubcategory_id != null){
            $subsubcategory =  SubSubCategory::find($request->sub_sub_category_id);
            if($subsubcategory){
                $subsubcategory->design = 1;
                $subsubcategory->save();
            }
        }

        $options = array();
        $attribute_options = array();

        if($request->has('colors')){ 
            $mockup->colors = json_encode($request->colors); 
            array_push($options, $request->colors);
        }else {
            $colors = array();
            $mockup->colors = json_encode($colors);
            array_push($options, $request->colors);
        }


        $mockup->attributes = !empty($request->attribute_options) ?  json_encode($request->attribute_options) : json_encode(array()); 

        if($request->has('attribute_options')){
            foreach ($request->attribute_options as $key => $no) {
                $str = 'attribute_options_'.$no;

                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str]));

                array_push($attribute_options, $item);

                $name = 'attribute_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $mockup->attribute_options = json_encode($attribute_options);

        //combinations start  
        $combinations = combinations($options);
        if(count($combinations[0]) > 0){ 
            foreach ($combinations as $key => $combination){
                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                    }else{
                        if($request->has('colors')){ 
                            $color_name = \App\Models\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        }else{
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }

                $mockup_stock = MockupStock::where('mockup_id', $mockup->id)->where('variant', $str)->first();
                if($mockup_stock == null){
                    $mockup_stock = new MockupStock;
                    $mockup_stock->mockup_id = $mockup->id;
                } 

                $mockup_stock->variant = $str;
                $mockup_stock->price = $request['purchase_price_'.str_replace('.', '_', $str)]; 
                $mockup_stock->quantity = $request['quantity_'.str_replace('.', '_', $str)];

                $mockup_stock->save();
            }
        }

        $mockup->save();

        alert('Mockup Uodated successfully','','success');
        return redirect()->route('admin.mockups.index');
    }

    public function show(Mockup $mockup)
    {
        abort_if(Gate::denies('mockup_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mockup->load('category', 'sub_category', 'sub_sub_category');

        return view('admin.mockups.show', compact('mockup'));
    }

    public function destroy(Mockup $mockup)
    {
        abort_if(Gate::denies('mockup_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mockup->delete();

        return back();
    }

    public function massDestroy(MassDestroyMockupRequest $request)
    {
        $mockups = Mockup::find(request('ids'));

        foreach ($mockups as $mockup) {
            $mockup->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('mockup_create') && Gate::denies('mockup_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Mockup();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
