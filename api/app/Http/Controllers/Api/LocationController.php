<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

           //  $status = explode(',', $status );

        $page = $request->page;
        $per_page= $request->per_page;
        $filter= $request->filter;
        $order_by = $request->order_by;
        $order_id = $request->order_id;
        $category = $request->category;
        $status=$request->status;
        
  



      if (empty($filter)) {
        return Location::select('locations.id','locations.title','locations.image','locations.edited_by','locations.updated_at','locations.view')
        -> orderBy($order_id, $order_by)
        -> paginate($per_page);
    }


            if (!empty($filter)) {
              return Location::select('locations.id','locations.title','locations.image','locations.edited_by')
                -> where('title', 'LIKE', "%{$filter}%")
                -> orWhere('id', 'LIKE', "%{$filter}%")
                -> orderBy($order_id, $order_by)
                -> paginate($per_page);
            }



      /*
       if (empty($filter)) {
            return Location::whereIn('view',[0,1])
              -> where('category',$category) 
              -> orderBy($order_id, $order_by)
              -> paginate($per_page);
          }
          */
      //return response()->json($view, 200);

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $location =  Location::create($request->all());
    //  DB::table('locations')->insert($request);
/*
        $heading = array(
          "en" => $request->title,"fr" => $request->title,
        );
        $content = array(
          "en" => $request->content,"fr" =>  $request->content,
          );

        $fields = array(
          'app_id' => "6348c229-5adb-4720-9923-3c412abc09cf",
          'headings' => $heading,
          'contents' => $content,
        //   'include_player_ids' => ["$idpushuser"],
          'included_segments' => array('All'),
          'data' => array("foo" => "demande")
        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                               'Authorization: Basic MzU3NzA1YWMtZWRiNS00MjllLThlMDYtMTkxYWJkMmFiZTUx'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_Location, TRUE);
        curl_setopt($ch, CURLOPT_LocationFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
*/


        return response()->json($location, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $location = DB::table('locations')
      ->join('users', 'locations.edited_by', '=', 'users.id')
      ->where('locations.id', $id)
      ->select('*')
      ->get();

      $tags= DB::table('tags_location')
      ->join('tags', 'tags_location.tag_id', '=', 'tags.id')
      ->where('tags_location.location_id', $id)
      ->select('tags.tag_fr','tags.tag_en','tags.tag_de')
      ->get();
      
      return response()->json(['location'=>$location,'tags'=>$tags],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $post = Location::findOrFail($id);
        $post->update($request->all());
        return response()->json($post, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Location::findOrFail($id);
        if($post)
           $post->delete();
        else
            return response()->json(error);
        return response()->json('post delete', 200);
    }

    public function postsByUser($id, Request $request)
    {
        $page = $request->page;
        $per_page = $request->per_page;
        $order_by = $request->order_by;
        $order_id = $request->order_id;
        $filter = $request->filter;

        if($filter){
            return Location::where('edited_by', $id)
            ->where('content', 'LIKE', "%{$filter}%")
            ->orWhere('title', 'LIKE', "%{$filter}%")
            ->orderBy($order_id, $order_by)
            ->paginate($per_page);
        }else{
            return Location::where('edited_by', $id)
            ->orderBy($order_id, $order_by)
            ->paginate($per_page);
        }
    }

    public function postsByUserShort($id, Request $request)
    {
        $page = $request->page;
        $per_page = 10;
        $order_by = 'desc';
        $order_id = 'id';

        return Location::where('edited_by', $id)
        ->orderBy($order_id, $order_by)
        ->paginate($per_page);
    }



    public function public_location(Request $request)
    {

      $page = $request->page;
      $status=1;
      $per_page = 10;
      $order_id = 'id';
      $category = 'location';
      $status=1;
      $lat= $request->lat;
      $lng= $request->lng;
      $dmin= $request->dmin;
      $dmax= $request->dmax;
      $tags = $request->tags;
     // $tags = collect($request->tags);

      //$tags = collect([1,2,3,4]);
      $location = DB::table('locations')
      ->join('tags_location', 'tags_location.location_id', '=', 'locations.id')
      ->whereIn('tags_location.tag_id', $tags)
      ->where('view', 1)
      ->select('locations.id','locations.title','locations.lat','locations.lng','locations.city','locations.image',
      \DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
      * cos(radians(locations.lat)) 
      * cos(radians(locations.lng) - radians(" . $lng . ")) 
      + sin(radians(" .$lat. ")) 
      * sin(radians(locations.lat))) AS distance"))
     
  
       /* \DB::raw("6371 * acos(cos(radians(" .$lat. "))
      * cos(radians(locations.lat)) 
      * cos(radians(locations.lng) - radians(" .$lat. ")) 
      + sin(radians(" .$lng. ")) */
      ->distinct()
      ->having('distance', '<', $dmax)
      ->having('distance', '>', $dmin)
      ->orderBy('distance', 'asc')
      ->paginate($per_page);

      return response()->json(  $location, 200);


    }


    public function public_location_map(Request $request)
    {

      $page = $request->page;
      $status=1;
      $per_page = 25;
      $order_id = 'id';
      $category = 'location';
      $status=1;
      $lat= $request->lat;
      $lng= $request->lng;
      $dmin= $request->dmin;
      $dmax= $request->dmax;
      $tags = $request->tags;
     // $tags = collect($request->tags);

      //$tags = collect([1,2,3,4]);
      $location = DB::table('locations')
      ->join('tags_location', 'tags_location.location_id', '=', 'locations.id')
      ->whereIn('tags_location.tag_id', $tags)
      ->where('view', 1)
      ->select('locations.id','locations.title','locations.lat','locations.lng',
      \DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
      * cos(radians(locations.lat)) 
      * cos(radians(locations.lng) - radians(" . $lng . ")) 
      + sin(radians(" .$lat. ")) 
      * sin(radians(locations.lat))) AS distance"))
     
  
       /* \DB::raw("6371 * acos(cos(radians(" .$lat. "))
      * cos(radians(locations.lat)) 
      * cos(radians(locations.lng) - radians(" .$lat. ")) 
      + sin(radians(" .$lng. ")) */
      ->distinct()
      ->having('distance', '<', $dmax)
      ->having('distance', '>', $dmin)
      ->orderBy('distance', 'asc')
      ->paginate($per_page);

      return response()->json(  $location, 200);


    }
/*
    public function public_location(Request $request)
    {

      $page = $request->page;
      $status=1;
      $per_page = 10;
      $order_id = 'id';
      $category = 'location';
      $status=1;
      $lat= $request->lat;
      $lng= $request->lng;
      $dmin= $request->dmin;
      $dmax= $request->dmax;
      $tags = $request->tags;
     // $tags = collect($request->tags);

      //$tags = collect([1,2,3,4]);
      $location = DB::table('locations')
      ->join('tags_location', 'tags_location.location_id', '=', 'locations.id')
      ->whereIn('tags_location.tag_id', $tags)
      ->where('view', 1)
      ->select('locations.id','locations.title','locations.lat','locations.lng','locations.city','locations.image',\DB::raw("6371 * acos(cos(radians(" .$lat. "))
      * cos(radians(locations.lat)) 
      * cos(radians(locations.lng) - radians(" .$lat. ")) 
      + sin(radians(" .$lng. ")) 
      * sin(radians(locations.lat))*1.609344) AS distance"))
      ->distinct()
      ->having('distance', '<', $dmax)
      ->having('distance', '>', $dmin)
    
      ->orderBy('distance', 'asc')
  
      ->paginate($per_page);

      return response()->json(  $location, 200);

    }
    */

    function arrondi_distance($distance_metre) {
      $resultat = round($distance_metre);
      $len = strlen($resultat) - 2;
      if ($len > 0) {
          $resultat = round($distance_metre / pow(10, $len), 0) * pow(10, $len
);
          if ($resultat >= 10000) {
              return number_format(($resultat / 1000), 0, ',', '') . ' km';
          } elseif ($resultat >= 1000) {
              return preg_replace('/,0$/', '', number_format(($resultat / 1000
), 1 , ',', '')) . ' km';
          } else {
              return $resultat . ' m';
          }
      } else {
          return $resultat . ' m';
      }
  }

    public function public_location_detail($id)
    {

      
      $location = DB::table('locations')
      ->where('locations.id', $id)
      ->select('*')
      ->get();

      $tags= DB::table('tags_location')
      ->join('tags', 'tags_location.tag_id', '=', 'tags.id')
      ->where('tags_location.location_id', $id)
      ->select('tags.tag_fr','tags.tag_en','tags.tag_de')
      ->get();
      
      return response()->json(['location'=>$location,'tags'=>$tags],200);

      
    }



    public function public_locations_short()
    {
      
        $per_page = 5;
        $order_by = 'desc';
        $order_id = 'id';
        $location = DB::table('locations')
    //    ->join('tags_location', 'tags_location.location_id', '=', 'locations.id')
        ->where('view', 1)
        ->orderBy($order_id, $order_by)
       // ->distinct('locations.location_id')
        ->paginate($per_page);

        return response()->json(  $location, 200);

    }



    public function tags_bylocation($id)
    {
      $tags= DB::table('tags_location')
      ->join('tags', 'tags_location.tag_id', '=', 'tags.id')
      ->where('tags_location.location_id', $id)
      ->select('tags.id','tags_location.pivot_id','tags.tag_fr','tags.tag_en','tags.tag_de')
      ->get();


      $tagsout=  DB::table('tags')
      ->select('tags.id','tags.tag_fr','tags.tag_en','tags.tag_de')
      ->distinct()
      ->get();


      return response()->json(['tagsin'=>$tags, 'tagsout'=> $tagsout],200);

      
    }


  


}



    /*

              return response()->json(Location::find($id), 200);
      */

