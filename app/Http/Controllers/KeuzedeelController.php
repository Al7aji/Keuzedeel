<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Keuzedeel;
use Illuminate\Support\Facades\Storage;


class KeuzedeelController extends Controller
{
    public function index(){
           
      $Keuzedeelposts = Keuzedeel::all();
    
      return view("KeuzedeelPosts.index", ['posts' => $Keuzedeelposts ] );
    }

    public function create(){

        return view("KeuzedeelPosts.create");
    }
    public function store(Request $request){


    $request->validate(
        [
            'titel' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'code' => 'required|string|max:255|unique:keuzedeels,code',
            'status' => 'required|in:Active,Inactive',
            'description' => 'required|string',
        ],
        [
            'titel.required' => 'The title is required',
            'titel.string' => 'The title must be a string',
            'titel.max' => 'The title may not be greater than 255 characters',
            
            'image.required' => 'The image is required',
            'image.image' => 'You must upload a valid image',
            'image.mimes' => 'Allowed image types: jpeg, png, jpg, webp, gif',
            'image.max' => 'The image size may not exceed 2MB',

            'code.required' => 'The code is required',
            'code.unique' => 'This code already exists',
            'code.string' => 'The code must be a string',
            'code.max' => 'The code may not be greater than 255 characters',

            'status.required' => 'The status is required',
            'status.in' => 'The status must be either Active or Inactive',

            'description.required' => 'The description is required',
            'description.string' => 'The description must be a string',
        ]
    );

        $titel = request()->titel;
        $code = request()->code;
        $status = request()->status;
        $description = request()->description;

        if ($request->hasFile('image')) {
           $image = $request->file('image')->store('images', 'public');
        }
       
      Keuzedeel::create([ 
        'titel'=>$titel,
        'image'=>$image,
        'code'=>$code,
        'status'=>$status,
        'description' => $description,
        ]);

         return to_route('Keuzedeel.create')->with('success','Keuezdell is cteated ');
    }

    public function edit(Keuzedeel $Keuzedeel){
        
        

        return view("KeuzedeelPosts.edit", ['Keuzedeel' => $Keuzedeel]);

    } 

    public function update(request  $request ,$Keuzedeel_id ){
       
    $keuzedeel = Keuzedeel::findOrFail($Keuzedeel_id);

    $request->validate([
        'titel' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        'code' => 'required|string|max:255|unique:keuzedeels,code,' . $Keuzedeel_id,
        'status' => 'required|in:Active,Inactive',
        'description' => 'nullable|string',
    ]);

    $data = $request->only(['titel', 'code', 'status', 'description']);

    
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('images', 'public');
    }

    $keuzedeel->update($data);

    return to_route('Keuzedeel.edit', $Keuzedeel_id)
        ->with('success', 'Updated successfully');

    }


    public function delete(Request $request ,$Keuzedeel_id){
        
        $keuzedeel = Keuzedeel::findOrFail($Keuzedeel_id);
        if ($keuzedeel->image) {
            Storage::disk('public')->delete($keuzedeel->image);
        }
        $keuzedeel->delete();

        return to_route('dashboard.keuezedeel')->with('success','Keuezdell is Deletet ');

    }
}