<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
{
    use MediaUploadingTrait;

    public function duplicate($id){
        $product = Product::findOrFail($id);
        $product->duplicate();
        alert()->success('Product duplicated successfully');
        return redirect()->route('admin.products.index');
    }
    public function edit_prices(Request $request){
        if($request->category == null && $request->sub_category == null && $request->sub_sub_category == null){ 
            toast('Missing Fields','warning');
            return redirect()->back();
        }

        $products = Product::orderBy('created_at','desc');

        if($request->category != null){
            $products = $products->where('category_id',$request->category);
        }

        if($request->sub_category != null){
            $products = $products->where('sub_category_id',$request->sub_category);
        }

        if($request->sub_sub_category != null){
            $products = $products->where('sub_sub_category_id',$request->sub_sub_category);
        }
        $products = $products->get();

        foreach($products as $product){
            if($request->money_type == 'flat'){ 
                $product->unit_price += $request->unit_price;
                $product->purchase_price += $request->purchase_price; 
            }else{
                $product->unit_price += ($product->unit_price / 100) * $request->unit_price;
                $product->purchase_price += ($product->purchase_price / 100) * $request->purchase_price; 
            }
            $product->save();
        } 
        toast('Success...','success');
        return redirect()->back();
    }

    public function sorting_images(Request $request) {
        foreach($request->media as $key => $value){
            $media = Media::find($key);
            $media->order_column = $value;
            $media->save();
        } 
        toast('Success...','success');
        return redirect()->back();
    }

    public function update_statuses(Request $request){ 
        $type = $request->type;
        $product = Product::findOrFail($request->id);
        $product->$type = $request->status; 
        $product->save();
        Cache::forget('home_new_products_' . $product->website_setting_id);
        Cache::forget('home_featured_categories_' . $product->website_setting_id);
        Cache::forget('best_selling_products_' . $product->website_setting_id);
        Cache::forget('freatured_categories_' . $product->website_setting_id);
        return 1;
    }

    public function get_sub_categories_by_category(Request $request)
    {
        $subcategories = SubCategory::where('category_id', $request->category_id)->get();
        return $subcategories;
    }
    
    public function get_sub_sub_categories_by_subcategory(Request $request)
    {
        $subsubcategories = SubSubCategory::where('sub_category_id', $request->sub_category_id)->get();
        return $subsubcategories;   
    }
    

    public function sku_combination_edit(Request $request)
    { 
        $product = Product::findOrFail($request->id);
        $product->load('stocks');

        $options = array();
        $colors_active = 0;
        if($request->has('colors')){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;
        $purchase_price = $request->purchase_price;

        if($request->has('attribute_options')){
            foreach ($request->attribute_options as $key => $no) {
                $name = 'attribute_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('admin.products.sku_combinations_edit', compact('combinations', 'unit_price', 'purchase_price', 'colors_active', 'product_name', 'product'));
    }
    
    public function sku_combination(Request $request)
    {
        $options = array();

        $colors_active = 0;
        if($request->has('colors')){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        $unit_price = $request->unit_price;
        $purchase_price = $request->purchase_price;
        $product_name = $request->name;

        if($request->has('attribute_options')){
            foreach ($request->attribute_options as $key => $no) {
                $name = 'attribute_options_'.$no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('admin.products.sku_combinations', compact('combinations', 'unit_price', 'purchase_price', 'colors_active', 'product_name'));
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $website_setting_id = $request->website_setting_id != null  ? $request->website_setting_id  : null;
        $category_id = $request->category_id != null ? $request->category_id : null;
        $sub_category_id = $request->sub_category_id != null ? $request->sub_category_id : null;
        $sub_sub_category_id = $request->sub_sub_category_id != null ? $request->sub_sub_category_id : null; 
        $weight = $request->weight != null ? $request->weight : null; 
        $flash_deal = $request->flash_deal != null ? $request->flash_deal : null; 
        $published = $request->published != null ? $request->published : null; 
        $featured = $request->featured != null ? $request->featured : null; 
        $todays_deal = $request->todays_deal != null ? $request->todays_deal : null; 

        $query = Product::with(['user', 'category', 'sub_category', 'sub_sub_category', 'design','website']);
        if($website_setting_id != null){
            $query = $query->where('website_setting_id',$website_setting_id);
        }
        if($category_id != null){
            $query = $query->where('category_id',$category_id);
        }
        if($sub_category_id != null){
            $query = $query->where('sub_category_id',$sub_category_id);
        }
        if($sub_sub_category_id != null){
            $query = $query->where('sub_sub_category_id',$sub_sub_category_id);
        }
        if($weight != null){
            $query = $query->where('weight',$weight);
        }
        if($flash_deal != null){
            $query = $query->where('flash_deal',$flash_deal);
        }
        if($published != null){
            $query = $query->where('published',$published);
        }
        if($featured != null){
            $query = $query->where('featured',$featured);
        }
        if($todays_deal != null){
            $query = $query->where('todays_deal',$todays_deal);
        }
        if($request->has('download')){
            $query = $query->get();
            return Excel::download(new ProductsExport($query), 'products.xlsx');
        }
        if ($request->ajax()) {
            $query = $query->select(sprintf('%s.*', (new Product)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'product_show';
                $editGate      = 'product_edit';
                $deleteGate    = 'product_delete';
                $crudRoutePart = 'products';

                $duplicate = '<a class="btn btn-xs btn-warning" href="' . \route('admin.' . $crudRoutePart . '.duplicate', $row->id) .'">
                                ' . __('global.duplicate') .'
                            </a>';  

                return $duplicate . ' &nbsp;' . view('partials.datatablesActions', compact(
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
                $name = $row->name ? $row->name : '';
                if($row->special){
                    $name .= ' <span class="badge badge-info badge-sm">مخصوص</span> ';
                }
                return $name;
            }); 
            $table->editColumn('weight', function ($row) {
                return $row->weight ? Product::WEIGHT_SELECT[$row->weight] : '';
            }); 
            $table->editColumn('unit_price', function ($row) {   
                if ($row->discount > 0){
                    return $row->calc_discount($row->unit_price) . '<br>' . '<span style="text-decoration:line-through">'.$row->unit_price.'</span>';
                }else{
                    return $row->unit_price;
                } 
            }); 
            $table->editColumn('video_provider', function ($row) {
                return $row->video_provider ? $row->video_provider : '';
            });
            $table->editColumn('video_link', function ($row) {
                return $row->video_link ? $row->video_link : '';
            });
            $table->editColumn('photos', function ($row) {
                if (! $row->photos) {
                    return '';
                }
                $links = [];
                foreach ($row->photos as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank"><img src="' . $media->getUrl('thumb') . '" width="50px" height="50px"></a>';
                }

                return implode(' ', $links);
            });
            $table->editColumn('statuses', function ($row) { 
                $flash_deal = '<label class="c-switch c-switch-pill c-switch-success">
                                    <input onchange="update_statuses(this,\'flash_deal\')" value="' . $row->id . '" type="checkbox" class="c-switch-input" '. ($row->flash_deal ? "checked" : null) .'>
                                    <span class="c-switch-slider"></span>
                                </label>';
                $published = '<label class="c-switch c-switch-pill c-switch-success">
                                <input onchange="update_statuses(this,\'published\')" value="' . $row->id . '" type="checkbox" class="c-switch-input" '. ($row->published ? "checked" : null) .'>
                                <span class="c-switch-slider"></span>
                            </label>';
                $featured = '<label class="c-switch c-switch-pill c-switch-success">
                                <input onchange="update_statuses(this,\'featured\')" value="' . $row->id . '" type="checkbox" class="c-switch-input" '. ($row->featured ? "checked" : null) .'>
                                <span class="c-switch-slider"></span>
                            </label>';
                $todays_deal = '<label class="c-switch c-switch-pill c-switch-success">
                                    <input onchange="update_statuses(this,\'todays_deal\')" value="' . $row->id . '" type="checkbox" class="c-switch-input" '. ($row->todays_deal ? "checked" : null) .'>
                                    <span class="c-switch-slider"></span>
                                </label>';
                
                $str = '<div style="display: flex;justify-content: space-between;">';
                $str .= '<div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;" class="badge text-bg-light mb-1">';     
                $str .= '<span>'.__('cruds.product.fields.flash_deal').'</span>'; 
                $str .=  $flash_deal;
                $str .=  '</div>';
                $str .= '<div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;" class="badge text-bg-light mb-1">';     
                $str .= '<span>'.__('cruds.product.fields.published').'</span>'; 
                $str .=  $published;
                $str .=  '</div>'; 
                
                $str .= '<div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;" class="badge text-bg-light mb-1">';     
                $str .= '<span>'.__('cruds.product.fields.featured').'</span>'; 
                $str .=  $featured;
                $str .=  '</div>';
                $str .= '<div style="display: flex;justify-content: space-between;flex-direction:column;margin: 0px 3px;" class="badge text-bg-light mb-1">';     
                $str .= '<span>'.__('cruds.product.fields.todays_deal').'</span>'; 
                $str .=  $todays_deal;
                $str .=  '</div>'; 
                $str .=  '</div>';   
                return $str;
            }); 
            $table->editColumn('current_stock', function ($row) {
                return $row->current_stock ? $row->current_stock : '';
            });

            $table->addColumn('categories', function ($row) {
                $category = $row->category ? '<span class="badge badge-info">' . $row->category->name . '</span>' : '';
                $sub_category = $row->sub_category ? '<span class="badge badge-warning">' . $row->sub_category->name . '</span>' : '';
                $sub_sub_category = $row->sub_sub_category ? '<span class="badge badge-danger">' . $row->sub_sub_category->name . '</span>' : ''; 
                return $category . $sub_category . $sub_sub_category;
            }); 

            $table->editColumn('website_site_name', function ($row) { 
                return $row->website->site_name ?? '';
            });

            $table->rawColumns(['actions', 'placeholder', 'photos' , 'statuses', 'categories','unit_price','name','website_site_name']);

            return $table->make(true);
        }


        $websites = WebsiteSetting::pluck('site_name', 'id');
        return view('admin.products.index',compact('websites','website_setting_id','category_id','sub_category_id','sub_sub_category_id','weight','flash_deal','published','featured','todays_deal'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 

        $colors = Color::pluck('name', 'code');

        $attributes = Attribute::pluck('name', 'id'); 

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(__('global.pleaseSelect'), '');

        return view('admin.products.create', compact('colors','attributes' , 'websites'));
    }

    public function store(StoreProductRequest $request)
    { 
        $validated_request = $request->all();
        $validated_request['user_id'] = Auth::id();
        $validated_request['added_by'] = 'staff';  
        $validated_request['published'] = 1;  
        $validated_request['tags'] = implode('|',$request->tags);  
        $validated_request['slug'] = Str::slug($request->name, '-',null) . '-' . Str::random(7); 
        $validated_request['colors'] = $request->has('colors') ? json_encode($request->colors) : json_encode(array());

        $attribute_options = array();

        if($request->has('attribute_options')){
            foreach ($request->attribute_options as $key => $no) {
                $str = 'attribute_options_'.$no; 
                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str])); 
                array_push($attribute_options, $item);
            }
        }

        $validated_request['attributes'] = !empty($request->attribute_options) ?  json_encode($request->attribute_options) : json_encode(array());
        $validated_request['attribute_options'] = json_encode($attribute_options);

        $product = Product::create($validated_request);

        //combinations start
        $options = array(); 
        if($request->has('colors')){ 
            array_push($options, $request->colors);
        }

        if($request->has('attribute_options')){
            foreach ($request->attribute_options as $key => $no) {
                $name = 'attribute_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        } 

        foreach ($request->input('photos', []) as $file) {
            $product->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
        }

        if ($request->input('pdf', false)) {
            $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('pdf'))))->toMediaCollection('pdf');
        }

        if ($request->input('object_3d', false)) {
            $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('object_3d'))))->toMediaCollection('object_3d');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $product->id]);
        }

        //Generates the combinations of attributes options options
        $combinations = combinations($options);
        if(count($combinations[0]) > 0){
            $product->variant_product = 1;
            $product->save();
            foreach ($combinations as $key => $combination){
                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0){
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

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if($product_stock == null){
                    $product_stock = new ProductStock();
                    $product_stock->product_id = $product->id;
                } 
                $product_stock->variant = $str;
                $product_stock->unit_price = $request['unit_price_'.str_replace('.', '_', $str)];
                $product_stock->purchase_price = $request['purchase_price_'.str_replace('.', '_', $str)]; 
                $product_stock->stock = $request['stock_'.str_replace('.', '_', $str)];
                $product_stock->save();
            }
        } 
        
        Cache::forget('home_new_products_' . $product->website_setting_id); 

        if($request->has('arrange_photos')){
            toast(__('flash.global.success_title'),'success'); 
            return redirect()->route('admin.products.show',$product->id);
        }

        if($request->has('duplicate')){
            $new_product = $product->duplicate();
            alert()->success('تم أضافة المنتج بنجاح وتكراره','انت الأن تقوم بتعديل المنتج المكرر');
            return redirect()->route('admin.products.edit',$new_product->id);
        }
        toast(__('flash.global.success_title'),'success'); 
        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $colors = Color::pluck('name', 'code');

        $attributes = Attribute::pluck('name', 'id');

        $categories = Category::where('website_setting_id',$product->website_setting_id)->get()->pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');  

        $product->load('user', 'category', 'sub_category', 'sub_sub_category', 'design');

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(__('global.pleaseSelect'), ''); 

        return view('admin.products.edit', compact('categories', 'product', 'colors', 'attributes','websites'));
    }

    public function update(UpdateProductRequest $request)
    {
        $product = Product::findOrFail($request->id);
        $product->load('stocks');
        
        $validated_request = $request->all(); 

        $validated_request['tags'] = implode('|',$request->tags);  
        $validated_request['colors'] = $request->has('colors') ? json_encode($request->colors) : json_encode(array());

        $attribute_options = array();

        if($request->has('attribute_options')){
            foreach ($request->attribute_options as $key => $no) {
                $str = 'attribute_options_'.$no; 
                $item['attribute_id'] = $no;
                $item['values'] = explode(',', implode('|', $request[$str])); 
                array_push($attribute_options, $item);
            }
        }

        if($product->variant_product){
            foreach ($product->stocks as $key => $stock) {
                $stock->delete();
            }
            $product->variant_product = 0;
        }

        $validated_request['attributes'] = !empty($request->attribute_options) ?  json_encode($request->attribute_options) : json_encode(array());
        $validated_request['attribute_options'] = json_encode($attribute_options); 

        //combinations start
        $options = array();
        if($request->has('colors')){
            array_push($options, $request->colors);
        }

        if($request->has('attribute_options')){
            foreach ($request->attribute_options as $key => $no) {
                $name = 'attribute_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        if(count($combinations[0]) > 0){
            $product->variant_product = 1;
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

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if($product_stock == null){
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }

                $product_stock->variant = $str;
                $product_stock->unit_price = $request['unit_price_'.str_replace('.', '_', $str)];
                $product_stock->purchase_price = $request['purchase_price_'.str_replace('.', '_', $str)]; 
                $product_stock->stock = $request['stock_'.str_replace('.', '_', $str)];

                $product_stock->save();
            }
        }

        $product->update($validated_request);

        if (count($product->photos) > 0) {
            foreach ($product->photos as $media) {
                if (! in_array($media->file_name, $request->input('photos', []))) {
                    $media->delete();
                }
            }
        }
        $media = $product->photos->pluck('file_name')->toArray();
        foreach ($request->input('photos', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $product->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
            }
        }

        if ($request->input('pdf', false)) {
            if (! $product->pdf || $request->input('pdf') !== $product->pdf->file_name) {
                if ($product->pdf) {
                    $product->pdf->delete();
                }
                $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('pdf'))))->toMediaCollection('pdf');
            }
        } elseif ($product->pdf) {
            $product->pdf->delete();
        }

        if ($request->input('object_3d', false)) {
            if (! $product->object_3d || $request->input('object_3d') !== $product->object_3d->file_name) {
                if ($product->object_3d) {
                    $product->object_3d->delete();
                }
                $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('object_3d'))))->toMediaCollection('object_3d');
            }
        } elseif ($product->object_3d) {
            $product->object_3d->delete();
        }

        if($request->has('arrange_photos')){
            return redirect()->route('admin.products.show',$product->id);
        }
        Cache::forget('home_new_products_' . $product->website_setting_id);
        Cache::forget('home_featured_categories_' . $product->website_setting_id);
        Cache::forget('best_selling_products_' . $product->website_setting_id);
        toast(__('flash.global.update_title'),'success'); 
        return redirect()->route('admin.products.index');
    }

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->load('user', 'category', 'sub_category', 'sub_sub_category', 'design');

        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->delete();

        alert(__('flash.deleted'),'','success');
        Cache::forget('home_new_products_' . $product->website_setting_id);
        Cache::forget('home_featured_categories_' . $product->website_setting_id);
        Cache::forget('best_selling_products_' . $product->website_setting_id);
        return 1;
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        $products = Product::find(request('ids'));

        foreach ($products as $product) {
            $product->delete();
            Cache::forget('home_new_products_' . $product->website_setting_id);
            Cache::forget('home_featured_categories_' . $product->website_setting_id);
            Cache::forget('best_selling_products_' . $product->website_setting_id);
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('product_create') && Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Product();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
