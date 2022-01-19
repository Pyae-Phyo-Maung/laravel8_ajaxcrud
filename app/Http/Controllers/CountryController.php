<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Validator;
use DataTables;
class CountryController extends Controller
{
    public function index(){
        return view('country.countries_list');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'country_name' => 'required|unique:countries',
            'capital_city' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json([
                "code" => 0,
                "error" => $validator->errors()->toArray()
            ]);      
        }else {
            $country=new Country();
            $country->country_name = $request->country_name;
            $country->capital_city = $request->capital_city;
            $query = $country->save();
            if(!$query){
                return response()->json([
                    "code" => 0,
                    "msg" => "Something went wrong"
                ]);
            }else {
                return response()->json([
                    "code" => 1,
                    "msg" => "New Country has been successfully saved"
                ]);
            }
        }                            
    }
    //Get countries list
    public function getCountry(){
        $countries = Country::all();
        return  DataTables::of($countries)
                            ->addIndexColumn()
                            ->addColumn('actions',function($row){
                                return '<div class="btn-group">
                                        <button class="btn btn-sm btn-primary" id="editCountryBtn" data-id="'.$row['id'].'">Update</button>
                                        <button class="btn btn-sm btn-danger" id="deleteCountryBtn" data-id="'.$row['id'].'">Delete</button>
                                        </div>';
                            })
                             ->rawColumns(['actions'])
                            ->make(true);
    }

    //Get country detail
    public  function getCountryDetail(Request $request){
        $country_id=$request->country_id;
        $country_detail=Country::find($country_id);
        return response()->json([
            "details"=>$country_detail
        ]);
    }

    //Update country detail
    public function updateCountryDetail(Request $request){
        // return response()->json([
        //     "result"=>$request->all()
        // ]);
        $country_id=$request->cid;
        $validator = Validator::make($request->all(),[
            'country_name' => 'required|unique:countries',
            'capital_city' => 'required',
        ]);
        if(!$validator->passes()){
            return response()->json([
                "code" => 0,
                "error" => $validator->errors()->toArray()
            ]);
        }else {
            $country = Country::find($country_id);
            $country->country_name = $request->country_name;
            $country->capital_city = $request->capital_city;
            $query = $country->save();

            if(!$query){
                return response()->json([
                    "code" => 0,
                    "msg" => "Something went Wrong!"
                ]);
            }else {
                return response()->json([
                    "code" => 1,
                    "msg" => "Updated Successfully"
                ]);
            }
        }
    }
    
    //Delete Country Detail
    public function deleteCountry(Request $request){
        $country_id = $request->country_id;
        $query = Country::find($country_id)->delete();
        if($query){
            return response()->json([
                "code" => 1,
                "msg" => "Country Detail Deleted"
            ]);
        }else {
            return response()->json([
                "code" => 0,
                "msg" => "Something Went Wrong"
            ]);
        }
    }
}
