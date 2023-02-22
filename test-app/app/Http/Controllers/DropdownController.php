<?php

namespace App\Http\Controllers;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\Seller;
use App\Models\Steering;
use App\Models\Models;
use App\Models\Properties;
use App\Models\All_Data;
use App\Models\Monthly_data;
use App\Models\SFX;
use App\Models\Variant;
use App\Models\Color;

class DropdownController extends Controller
{
     /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        $data['suppliers'] = Supplier::get(["name", "id"]);
       
        return view('welcome', $data);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function fetchSeller(Request $request)
    {   
        $data['sellers'] = Seller::get();
        // echo json_encode( $data['sellers']);die();
        return response()->json($data);
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function fetchSteering(Request $request)
    {
        $data['steerings'] = Steering::get();
                                      
        return response()->json($data);
    }
    public function welcome2(Request $request)
    {
        $supplier = Supplier::where('id','=',$request->supplier)->first();
        $seller = Seller::where('id','=',$request->seller)->first();
        $steering = Steering::where('id','=',$request->steering)->first();
        $all_data = new All_Data;
        $all_data->supplier = $supplier->name;
        $all_data->seller = $seller->seller_type;
        $all_data->steering = $steering->type;
        $all_data->save();
        $data['models'] = Models::get(["name", "id"]);

        return view('welcome2', $data);
    }
    public function fetchSFX(Request $request)
    {   $model_id = $request->model_id;
        $all_data = All_Data::orderBy('id','DESC')->first();
        $last_id = $all_data->id;
        $model = Models::where('id','=',$request->model_id)->first();
        All_Data::where('id','=',$last_id)
        ->update([
            'model' => $model->name
         ]);
        
        $data['sfxs'] = Properties::where("model_id", $request->model_id)
                                ->get();
        
    
        return response()->json($data);
    }
    public function fetchVariant(Request $request)
    {
        $sfx_id = $request->sfx_id;
        $all_data = All_Data::orderBy('id','DESC')->first();
        $last_id = $all_data->id;
        $sfx = SFX::where('id','=',$request->sfx_id)->first();
        All_Data::where('id','=',$last_id)
        ->update([
            'sfx' => $sfx->name
         ]);
        $data['variants'] = Properties::where("sfx_id", $request->sfx_id)
                                ->get();
  
        return response()->json($data);
    }
    public function fetchColor(Request $request)
    {
        $variant_id = $request->variant_id;
        $all_data = All_Data::orderBy('id','DESC')->first();
        $last_id = $all_data->id;
        $variant = Variant::where('id','=',$request->variant_id)->first();
        All_Data::where('id','=',$last_id)
        ->update([
            'variant' => $variant->name
         ]);
        $data['colors'] = Properties::where("variant_id", $request->variant_id)
                                ->get();
        return response()->json($data);
    }
    public function saveData(Request $request)
    {
        $color_id = $request->color_id;
        $all_data = All_Data::orderBy('id','DESC')->first();
        $last_id = $all_data->id;
        $color = Color::where('id','=',$request->color_id)->first();
        All_Data::where('id','=',$last_id)
        ->update([
            'color' => $color->name
         ]);
        $data = All_Data::orderBy('id','DESC')->take(1)->get();
        foreach($data as $d){
            $id = $d['id'];
            $data['all_data']=All_Data::orderBy('id','DESC')->take(1)->get();
           
            // $data['all_data']= All_Data::select(
            //     "sfx.name as sfx",
            //     "color.name as color",
            //     "variant.name as variant",
            //     "models.name as model",
            //     "supplier.name as supplier",
            //     "steering_type.type as steering",
            //     "whole_seller.seller_type as seller"
            // )
            // ->join("sfx", "sfx.id", "=", "all_data.sfx")
            // ->join("color", "color.id", "=", "all_data.color")
            // ->join("variant", "variant.id", "=", "all_data.variant")
            // ->join("models", "models.id", "=", "all_data.model")
            // ->join("supplier", "supplier.id", "=", "all_data.supplier")
            // ->join("whole_seller", "whole_seller.id", "=", "all_data.seller")
            // ->join("steering_type", "steering_type.id", "=", "all_data.steering")
            // ->get();
           
            
        }
        return response()->json($data);
    }
    public function save_data(Request $request)
    {
        $value=$request->hiddenrow;
        $data = [
                'success' => true,
                'message'=> $value
              ] ;
        for($count = 0; $count < $value; $count++)
        {
            $check = All_Data::where('id','=',$request->id[$count])->first();
                if(empty($check)){
                    $all_data = new All_Data;
                    $all_data->supplier = $request->supplier[$count];
                    $all_data->seller = $request->seller[$count];
                    $all_data->steering = $request->steering[$count];
                    $all_data->model = $request->model[$count];
                    $all_data->variant = $request->variant[$count];
                    $all_data->sfx = $request->sfx[$count];
                    $all_data->color = $request->color[$count];
                    $all_data->save();
                    $data = [
                        'success' => true,
                        'message'=> "Added Successfull"
                      ] ;
                }
                else{
        
                    All_Data::where('id','=',$request->id[$count])
                ->update([
                    'supplier' => $request->supplier[$count],
                    'seller' => $request->seller[$count],
                    'steering' => $request->steering[$count],
                    'sfx' => $request->sfx[$count],
                    'variant' => $request->variant[$count],
                    'color' => $request->color[$count],
                 ]);
                 $data = [
                    'success' => true,
                    'message'=> $check
                  ] ;
                }
        }
        $all = All_Data::get();
        $data['all_data2'] = All_Data::all();
        $data['count'] = $all->count();
            
        return response()->json($data);
    }
    public function view_data(Request $request) {
        // get the search term
        $text = $request->text;
    
        // search the members table
        $patients = DB::table('monthly_data')->where('month', 'Like', $text)->get();
    
    
        // return the results
        return response()->json($patients);
    }
    public function filter(Request $request)
    {
        $month = $request->input('month');

        //You should validate these inputs your way

        $query = Monthly_data::query();
        if (!empty($month)) {
            //Filter by month
            // $query->where('month', $month);
             $query->select(
                "supplier.name as supplier",
                "monthly_data.id as id",
                "monthly_data.quantity as quantity",
                "monthly_data.month as month",
                "monthly_data.year as year"
             )
            ->join("supplier", "supplier.id", "=", "monthly_data.supplier_id")
            ->where('monthly_data.month', 'like' ,$month)
            // ->whereMonth(
            //             'monthly_data.created_at', '=', Carbon::now()->subMonth(1)->month
            //         )
            // ->whereBetween('monthly_data.created_at', 
            //                 [Carbon::now()->subMonth(2)->month, Carbon::now()]
            //             )
            ->get();
           
        }
        // $collection = array();
        $collection = $query->get();
        $data['collection'] = $collection;

        return response()->json($data);
    }


    
}
