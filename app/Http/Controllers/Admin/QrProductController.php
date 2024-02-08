<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrProduct;
use App\Models\QrProductKey;
use App\Models\QrScanHistory;
use Illuminate\Http\Request;

class QrProductController extends Controller
{
    public function printmore(Request $request){ 
        $qr_product_keys = QrProductKey::with('product')->whereIn('id',explode(',',$request->ids))->get(); 
        return view('admin.rBranches.partials.printmore',compact('qr_product_keys'));
    }
    public function qr_output(Request $request){
        $qr_product_key = QrProductKey::find(str_replace('https://ebtekarstore.com?id=','',$request->code));
        $qr_scan_history = QrScanHistory::find($request->id);
        if($qr_product_key && $qr_scan_history){
            $product_name = $qr_product_key->product->product ?? '';
            $tr = '<tr>'; 
            $tr .= '<td>'.$qr_product_key->id.'</td>';
            $tr .= '<td>'.$product_name.'</td>';
            $tr .= '<td>'.$qr_product_key->name.'</td>'; 
            $tr .= '</tr>';

            // scanned
            $scanned = [];
            if($qr_scan_history->scanned){
                $scanned = explode(',',$qr_scan_history->scanned);
            } 
            if(in_array($qr_product_key->id,$scanned)){ 
                return response()->json(['status' => false,'message'=>'already exist']);
            }else{ 
                $scanned[] = $qr_product_key->id;
                $qr_scan_history->scanned = implode(',',$scanned);
                
                // results
                $results = [];
                if($qr_scan_history->results){
                    $results = json_decode($qr_scan_history->results,TRUE);
                    $results[$qr_product_key->product->id]['product'] =  $product_name;
                    if(array_key_exists('names',$results[$qr_product_key->product->id])){
                        $names = $results[$qr_product_key->product->id]['names'];
                    }else{
                        $names = [];
                    }
                    $name = ['id' => $qr_product_key->id , 'name' => $qr_product_key->name]; 
                    array_push($names,$name);
                    $results[$qr_product_key->product->id]['names'] =  $names;
                }else{
                    $results[$qr_product_key->product->id]['product'] =  $product_name;
                    $results[$qr_product_key->product->id]['names'] =  $scanned;
                }

                $qr_scan_history->results = json_encode($results);
                $qr_scan_history->save();
            }

            $view_results = '<tr> <th>المنتج</th> <th>الكمية</th> </tr>';
            
            foreach(json_decode($qr_scan_history->results) as $key => $product){
                $qr_product = QrProduct::find($key);
                $quantity = $qr_product->quantity ?? 0;
                $count = count($product->names) ?? 0; 
                    
                $view_results .='<tr>';
                $view_results .='<th>'.$product->product.'</th>';
                $view_results .='<th>';
                $view_results .='<span class="badge badge-info"> الكمية الدورية : <h5>'. $quantity .'</h5></span>';
                $view_results .='<span class="badge badge-danger">  Scanned : <h5>' . $count . '</h5></span>';
                $view_results .='<span class="badge badge-success">  الاحتياج :  <h5>'. $quantity - $count .'</h5></span> ';
                $view_results .='</th>'; 
                $view_results .='</tr>';

            }
            return response()->json(['code'=> $request->code ,'status' => true,'tr' => $tr,'id'=>$request->id,'results'=> $view_results]);
        }else{
            return response()->json(['status' => false,'message'=>'not found']);
        }
    }
    
    public function start_scan(Request $request){ 
        QrScanHistory::create([
            'r_branch_id'=>$request->r_branch_id,
            'name'=>$request->name, 
        ]);  
        alert('Success','','success');
        return redirect()->back();
    }
    
    public function store(Request $request){ 
        $qr_product = QrProduct::create([
            'r_branch_id'=>$request->r_branch_id,
            'product'=>$request->product,
            'quantity'=>$request->quantity,
        ]);

        $names = [];
        foreach(explode(',',$request->keys) as $name){ 
            $names [] = [
                'qr_product_id' => $qr_product->id, 
                'name' => $name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $keys = QrProductKey::where('qr_product_id',$request->qr_product_id)->get();
        foreach($keys as $key){
            $names [] = [
                'qr_product_id' => $qr_product->id, 
                'name' => $key->name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        QrProductKey::insert($names);
        alert('Success','','success');
        return redirect()->back();
    }

    public function update(Request $request){
        if($request->has('update_keys')){ 
            $names = [];
            foreach(explode(',',$request->keys) as $name){ 
                $names [] = [
                    'qr_product_id' => $request->id, 
                    'name' => $name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            } 
            QrProductKey::insert($names);
            alert('Success','','success');
            return redirect()->back();
        }
        $qr_product = QrProduct::findOrFail($request->id);
        $qr_product->update([ 
            'product'=>$request->product,
            'quantity'=>$request->quantity,
        ]);
        alert('Success','','success');
        return redirect()->back();
    }

    public function show(Request $request){
        $qr_product = QrProduct::find($request->id);
        $qr_product->load('names');
        $names = $qr_product->names()->get();
        return view('admin.rBranches.partials.names',compact('qr_product','names'));
    }

    public function view_scanner(Request $request){
        $qr_scan_history = QrScanHistory::find($request->id); 
        return view('admin.rBranches.partials.scanner',compact('qr_scan_history'));
    }

    public function save_print(Request $request){
        $qr_scan_history = QrScanHistory::find($request->id); 
        $printed = [];
        if($qr_scan_history->printed){
            $printed = explode(',',$qr_scan_history->printed);
        } 
        if(!in_array($request->name_id,$printed)){ 
            $printed[] = $request->name_id;
            $qr_scan_history->printed = implode(',',$printed);
            $qr_scan_history->save();
        }
    }
    public function view_result(Request $request){
        $qr_scan_history = QrScanHistory::find($request->id); 

        $scanned = [];
        if($qr_scan_history->scanned){
            $scanned = explode(',',$qr_scan_history->scanned);
        } 
        $qr_products = QrProduct::where('r_branch_id',$request->r_branch_id)->with(['names' => function($q) use ($scanned) {
            return $q->whereNotIn('id',$scanned);
        }])->get();
        return view('admin.rBranches.partials.results',compact('qr_scan_history','qr_products'));
    }

    public function load_needs(Request $request){
        $qr_scan_history = QrScanHistory::find($request->qr_scan_history_id); 

        $scanned = [];
        if($qr_scan_history->scanned){
            $scanned = explode(',',$qr_scan_history->scanned);
        } 
        $qr_products = QrProduct::where('id',$request->qr_product_id)->with(['names' => function($q) use ($scanned) {
            return $q->whereNotIn('id',$scanned);
        }])->get();

        return view('admin.rBranches.partials.load_needs',compact('qr_scan_history','qr_products'));
    }
    
    public function print($id){
        $qr_product_key = QrProductKey::findOrFail($id); 
        return view('admin.rBranches.partials.print',compact('id','qr_product_key'));
    }
    
    public function delete_name($id){
        $qr_product_key = QrProductKey::findOrFail($id); 
        $qr_product_key->delete();
        alert('Success','','success');
        return redirect()->back();
    }

    public function destroy($id)
    { 
        $qr_product = QrProduct::findOrFail($id);
        $qr_product->delete();

        return redirect()->back();
    }
}
