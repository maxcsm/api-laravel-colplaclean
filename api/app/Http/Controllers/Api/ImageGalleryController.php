<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\User;
use App\Models\Imagegallery;


class ImageGalleryController extends Controller
{


    /**
     * Listing Of images gallery
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$images = Imagegallery::get();
    	return view('image-gallery',compact('images'));
    }


    /**
     * Upload image function
     *
     * @return \Illuminate\Http\Response
     */

    
    public function upload(Request $request)
    {
    	$this->validate($request, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
        ]);


        $input['image'] = time().'.'.$request->image->getClientOriginalExtension();
        $request->image->move(public_path('images'), $input['image']);


       // $input['title'] = $request->title;
       // $input['postid'] = $request->postid;
      // Imagegallery::create($input);

       return response()->json($input['image'], 200);
    
    }


    public function getimagepython(Request $request) {

    $urlimage = $request->urlimage;
    $pixel = $request->pixel;
    $url ="http://maxime74.pythonanywhere.com/sendimage?url=$urlimage";
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);

    return response()->json($response, 200);
    }

    public function getimagepythonpixel2(Request $request) {

      $urlimage = $request->urlimage;
   
      $url ="http://maxime74.pythonanywhere.com/sendimagepixel2?url=$urlimage";
      
      $curl = curl_init();
  
      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);
  
      return response()->json($response, 200);
      }
        
    public function uploadOLD(Request $request)
    {


      ////Coplaclean API 
    	$this->validate($request, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $input['image'] = time().'.'.$request->image->getClientOriginalExtension();
        $request->image->move(public_path('images'), $input['image']);


        $input['title'] = $request->title;
        $input['postid'] = $request->postid;
        Imagegallery::create($input);

       return response()->json($input['postid'], 200);
    
    }
/*
     * Remove Image function
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	Imagegallery::find($id)->delete();
    	return back()
    		->with('success','Image removed successfully.');	
    }



    public function imagesByGallery($id, Request $request)
    {
        $page = $request->page;
        $per_page = $request->per_page;
        $order_by = $request->order_by;
        $order_id = $request->order_id;
        $filter = $request->filter;

       
            return Post::where('postid', $id)
            ->orderBy($order_id, $order_by)
            ->paginate($per_page);
        
    }
}