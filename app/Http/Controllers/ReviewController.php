<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $review_list = DB::table('review')
                        ->join('users as user', 'user.id', '=', 'review.user_id')
                        ->join('users as coach', 'coach.id', '=', 'review.coach_id')
                        ->select(
                        'user.id as user_id',
                        'user.first_name as user_first_name',
                        'user.last_name as user_last_name',
                        'user.email as user_email',
                        'user.contact_number as user_contact_number',
                        'coach.first_name as coach_first_name',
                        'coach.last_name as coach_last_name',
                        'coach.email as coach_email',
                        'coach.contact_number as coach_contact_number',
                        'review.id',
                        'review.review_text',
                        'review.status',
                         )
                        ->orderBy('review.id', 'DESC') 
                        ->paginate(20);

                       // dd($review_list);
         return view('admin.review_list',compact('review_list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function viewReview($id)
    {
         //$id = $request->id;
       
        if($id!=null)
        {
                $user_detail = DB::table('review')
                              ->join('users as user', 'user.id', '=', 'review.user_id')
                              ->leftJoin('master_country', 'user.country_id', '=', 'master_country.country_id')
                              ->leftJoin('master_state', 'user.state_id', '=', 'master_state.state_id')
                              ->leftJoin('master_city', 'user.city_id', '=', 'master_city.city_id')
                              ->select(
                                'user.id as user_id',
                                'user.first_name as user_first_name',
                                'user.last_name as user_last_name',
                                'user.email as user_email',
                                'user.contact_number as user_contact_number',
                                'user.professional_title as user_professional_title',
                                'user.short_bio as user_short_bio',
                                'user.gender as user_gender',
                                'user.detailed_bio as user_detailed_bio',
                                'user.profile_image as user_profile_image',
                                'master_country.country_name',
                                'master_state.state_name',
                                'master_city.city_name',
                                'review.id',
                               
                            )
                            ->where('review.id', $id)
                             ->where('user.user_type', 2)
                            ->where('user.user_status', 1)
                            ->first();

              $coach_detail = DB::table('review')
                            ->join('users as coach', 'coach.id', '=', 'review.coach_id')
                            ->leftJoin('master_country', 'coach.country_id', '=', 'master_country.country_id')
                            ->leftJoin('master_state', 'coach.state_id', '=', 'master_state.state_id')
                            ->leftJoin('master_city', 'coach.city_id', '=', 'master_city.city_id')
                            ->select(
                                'coach.id as coach_id',
                                'coach.first_name as coach_first_name',
                                'coach.last_name as coach_last_name',
                                'coach.email as coach_email',
                                'coach.contact_number as coach_contact_number',
                                'coach.professional_title as coach_professional_title',
                                'coach.short_bio as coach_short_bio',
                                'coach.gender as coach_gender',
                                'coach.detailed_bio as coach_detailed_bio',
                                'coach.profile_image as coach_profile_image',
                                'master_country.country_name',
                                'master_state.state_name',
                                'master_city.city_name',
                                'review.id',
                                
                            )
                            ->where('review.id', $id)
                             ->where('coach.user_type', 3)
                            ->where('coach.user_status', 1)
                            ->first();

            $review_detail = DB::table('review')
                             ->join('users', 'users.id', '=', 'review.user_id')
                             ->select('users.*', 'review.review_text', 'review.rating')
                             ->where('review.id', $id)
                             ->first();

        }
        return view('admin.view_review_profile',compact('coach_detail','user_detail','review_detail'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function status(Request $request)
    {
        $user = Review::find($request->user);
        $user->status=$request->status;
        $user->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
