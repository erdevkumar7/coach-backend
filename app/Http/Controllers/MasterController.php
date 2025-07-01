<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\User;

use App\Models\Language;
use App\Models\Service;
use App\Models\Policy;
use App\Models\Subscription;
use App\Models\Professional;
use App\Models\CoachType;
use App\Models\CoachSubType;
use App\Models\CoachingCat;
use App\Models\DeliveryMode;
use App\Models\Blog;

class MasterController extends Controller
{
    public function __construct()
    {
        if (Auth::guard("admin")->user()) {
            $user = Auth::guard("admin")->user();

            if ($user->user_type != 1) {
                Auth::guard("admin")->logout();
                return redirect()->route("admin.login")->with("warning", "You are not authorized as admin.");
            }
        }
    }
    public function LanguageList()
    {
        //$languages=DB::table('master_language')->paginate(20);
        $languages = DB::table('master_language')->where('is_deleted', 0)->orderBy('language', 'asc')
            ->paginate(20);
        return view('admin.language_list', compact('languages'));
    }
    public function updateLanguageStatus(Request $request)
    {
        $user = Language::find($request->user);
        $user->is_active = $request->status;
        $user->save();
    }

    public function addLanguage(Request $request, $id = null)
    {

        $language_detail = "";
        if ($id != null) {
            $language_detail = DB::table('master_language')->where('id', $id)->first();
        }
        if ($request->isMethod('post')) {
            $language_check = DB::table('master_language')->where('language', $request->language)->where('id', '!=', $request->id)->first();
            if ($language_check) {
                return redirect()->route("admin.languageList")->with("error", "Language Already Exist.");
            } else {
                $language = Language::find($request->id);
                if (!$language) {
                    $language = new Language();
                }

                $language->language       = $request->language;



                $language->created_at     = date('Y-m-d H:i:s');
                $language->save();
                return redirect()->route("admin.languageList")->with("success", "Master language updated successfully.");
            }
        }

        return view('admin.add_language', compact('language_detail'));
    }
    public function bulkDeleteLanguage(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No language selected.');
        }

        Language::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected Language deleted successfully.');
    }

    public function ServiceList()
    {
        $services = DB::table('master_service')->where('is_deleted', 0)->orderBy('service', 'asc')
            ->paginate(20);
        return view('admin.service_list', compact('services'));
    }
    public function updateServiceStatus(Request $request)
    {
        $user = Service::find($request->user);
        $user->is_active = $request->status;
        $user->save();
    }

    public function addService(Request $request, $id = null)
    {

        $service_detail = "";
        if ($id != null) {
            $service_detail = DB::table('master_service')->where('id', $id)->first();
        }
        if ($request->isMethod('post')) {
            $service_check = DB::table('master_service')->where('service', $request->service)->where('id', '!=', $request->id)->first();
            if ($service_check) {
                return redirect()->route("admin.serviceList")->with("error", "Service Already Exist.");
            } else {
                $service = Service::find($request->id);
                if (!$service) {
                    $service = new Service();
                }

                $service->service       = $request->service;
                $service->created_at       = date('Y-m-d H:i:s');
                $service->save();
                return redirect()->route("admin.serviceList")->with("success", "Master service updated successfully.");
            }
        }

        return view('admin.add_service', compact('service_detail'));
    }
    public function bulkDeleteService(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No blogs selected.');
        }

        Service::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected blogs deleted successfully.');
    }

    function subscriptionList()
    {
        $subscription_plan = DB::table('subscription_plan')->where('is_deleted', 0)->paginate(20);
        return view('admin.subscription_list', compact('subscription_plan'));
    }
    function addSubscription(Request $request, $id = null)
    {

        $subscription_detail = "";
        if ($id != null) {
            $subscription_detail = DB::table('subscription_plan')->where('id', $id)->first();
        }
        if ($request->isMethod('post')) {
            $subscription_check = DB::table('subscription_plan')->where('plan_name', $request->plan_name)->where('is_deleted', 0)->where('id', '!=', $request->id)
                ->first();
            if ($subscription_check) {
                return redirect()->route("admin.subscriptionList")->with("error", "Subscription Plan Name Already Exist.");
            } else {
                $Subscription = Subscription::find($request->id);
                if (!$Subscription) {
                    $Subscription = new Subscription();
                }

                $Subscription->plan_name       = $request->plan_name;

                $Subscription->plan_content    = (!empty($request->plan_content) && $request->plan_content != '') ? $request->plan_content : '';
                $Subscription->plan_amount     = $request->plan_amount;
                $Subscription->plan_duration   = $request->plan_duration;
                $Subscription->duration_unit   = $request->duration_unit;

                $Subscription->created_at       = date('Y-m-d H:i:s');
                $Subscription->save();
                return redirect()->route("admin.subscriptionList")->with("success", "Master Subscription Plan Added/updated successfully.");
            }
        }

        return view('admin.add_subscription', compact('subscription_detail'));
    }
    public function bulkDeletePlan(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No Subscription Plan selected.');
        }

        Subscription::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected Subscription Plan deleted successfully.');
    }

    public function addPolicy(Request $request, $id = null)
    {
        //This function is for add / update policy
        $policies = '';
        if ($id != null) {
            $policies = DB::table('policy')->where('id', $id)->first();
        }
        if ($request->isMethod('post')) {
            $type_check = DB::table('policy')->where('policy_type', $request->policy_type)->where('is_deleted', 0)->where('id', '!=', $request->policy_id)->first();
            if ($type_check) {
                return redirect()->route("admin.policyList")->with("error", "Policy Type Already Exist.");
            } else {
                $policy_type = $request->policy_type;
                $policy_content = $request->policy_content;
                $policy_id = $request->policy_id;

                $policy = Policy::where('id', $policy_id)->first();

                if (!$policy) {
                    $policy = new Policy();
                }
                $policy->policy_type = $policy_type;
                $policy->policy_content = $policy_content;
                $policy->policy_name = $request->policy_name;
                $policy->save();

                return redirect()->route("admin.policyList")->with("success", "Policy content added successfully.");
            }
        }
        return view('admin.add_policy', compact('policies'));
    }

    public function policyList()
    {
        //This function is for show policy list
        $policy = DB::table('policy')->where('is_deleted', 0)->paginate(20);
        return view('admin.policy_list', compact('policy'));
    }
    public function viewPolicy($id = null)
    {
        $policy = DB::table('policy')->where('id', $id)->first();
        return view('admin.policy_view', compact('policy'));
    }

    public function deletePolicy(Request $request)
    {
        //This function is for delete the policy
        $policy = Policy::find($request->policy_id);
        $policy->is_deleted = 1;
        $policy->save();
    }
    public function bulkDeletePolicy(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No policies selected.');
        }

        Policy::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected policies deleted successfully.');
    }

    public function update_subscri_status(Request $request)
    {
        $subscription_plan = Subscription::find($request->user);
        $subscription_plan->is_active = $request->status;
        $subscription_plan->save();
    }
    public function addCoachType(Request $request, $id = null)
    {
        //This function is for add / update coach type 
        $coach_type = '';
        if ($id != null) {
            $coach_type = DB::table('coach_type')->where('id', $id)->first();
        }

        if ($request->isMethod('post')) {
            $type_check = DB::table('coach_type')->where('type_name', $request->type_name)->where('id', '!=', $request->id)->first();
            if ($type_check) {
                return redirect()->route("admin.coachTypeList")->with("error", "Coach Type Already Exist.");
            } else {
                $type = CoachType::find($request->id);
                if (!$type) {
                    $type = new CoachType();
                }

                $type->type_name     = $request->type_name;
                $type->created_at    = date('Y-m-d H:i:s');
                $type->save();
                return redirect()->route("admin.coachTypeList")->with("success", "Coach Type updated successfully.");
            }
        }
        return view('admin.add_coach_type', compact('coach_type'));
    }
    public function coachTypeList()
    {
        //This function is for show list 
        $type = DB::table('coach_type')->where('is_deleted', 0)->paginate(20);
        return view('admin.coach_type_list', compact('type'));
    }
    public function bulkDeleteCoachCat(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No coach category selected.');
        }

        CoachType::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected coach category deleted successfully.');
    }
    public function updateTypeStatus(Request $request)
    {
        $user = CoachType::find($request->user);
        $user->is_active = $request->status;
        $user->save();
    }
    public function addCoachSubType(Request $request, $id = null)
    {
        //This function is for add / update coach type 
        $coach_type = DB::table('coach_type')->where('is_active', 1)->get();
        $coach_subtype = '';
        if ($id != null) {
            $coach_subtype = DB::table('coach_subtype')->where('id', $id)->first();
        }

        if ($request->isMethod('post')) {
            $type_check = DB::table('coach_subtype')->where('subtype_name', $request->subtype_name)->where('id', '!=', $request->id)->first();
            if ($type_check) {
                return redirect()->route("admin.coachSubTypeList")->with("error", "Coach Sub Type Already Exist.");
            } else {
                $type = CoachSubType::find($request->id);
                if (!$type) {
                    $type = new CoachSubType();
                    $type->coach_type_id = $request->coach_type_id;
                }

                $type->subtype_name     = $request->subtype_name;
                $type->created_at       = date('Y-m-d H:i:s');
                $type->save();
                return redirect()->route("admin.coachSubTypeList")->with("success", "Coach SubType updated successfully.");
            }
        }
        return view('admin.add_coach_subtype', compact('coach_type', 'coach_subtype'));
    }
    public function coachSubTypeList($id = null)
    {
        //This function is for show list 
        $type = DB::table('coach_subtype')
            ->join('coach_type', 'coach_type.id', '=', 'coach_subtype.coach_type_id')
            ->where('coach_type.is_deleted', 0)
            ->where('coach_subtype.is_deleted', 0)
            ->select('coach_subtype.*', 'coach_type.type_name')
            ->paginate(20);
        if ($id != null) {
            $type = DB::table('coach_subtype')
                ->where('coach_subtype.coach_type_id', $id)
                ->join('coach_type', 'coach_type.id', '=', 'coach_subtype.coach_type_id')
                ->where('coach_type.is_deleted', 0)
                ->where('coach_subtype.is_deleted', 0)
                ->select('coach_subtype.*', 'coach_type.type_name')
                ->paginate(20);
        }

        return view('admin.coach_subtype_list', compact('type'));
    }
    public function bulkDeleteCoachSubCat(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No coach Subcategory selected.');
        }

        CoachType::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected coach Subcategory deleted successfully.');
    }
    public function updateSubTypeStatus(Request $request)
    {
        $user = CoachSubType::find($request->user);
        $user->is_active = $request->status;
        $user->save();
    }

    public function addCoachingCategory(Request $request, $id = null)
    {
        //This function is for add/update coaching category
        $category = '';
        if ($id != null) {
            $category = DB::table('coaching_cat')->where('id', $id)->first();
        }

        if ($request->isMethod('post')) {
            $cat_check = DB::table('coaching_cat')->where('category_name', $request->category_name)->where('id', '!=', $request->id)->first();
            if ($cat_check) {
                return redirect()->route("admin.coachingCategoryList")->with("error", "Coaching Category Already Exist.");
            } else {
                $type = CoachingCat::find($request->id);
                if (!$type) {
                    $type = new CoachingCat();
                }

                $type->category_name     = $request->category_name;
                $type->created_at       = date('Y-m-d H:i:s');
                $type->save();
                return redirect()->route("admin.coachingCategoryList")->with("success", "Coaching Category updated successfully.");
            }
        }
        return view('admin.add_coaching_category', compact('category'));
    }
    public function coachingCategoryList()
    {
        //This function is for show list of coaching category
        $category = DB::table('coaching_cat')->where('is_deleted', 0)->paginate(20);
        return view('admin.coaching_category_list', compact('category'));
    }
    public function bulkDeletecat(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No Coaching Category selected.');
        }

        CoachingCat::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected Coaching Category deleted successfully.');
    }
    public function updateCategoryStatus(Request $request)
    {
        //This will update the coaching category status
        $user = CoachingCat::find($request->user);
        $user->is_active = $request->status;
        $user->save();
    }

    public function addDeliveryMode(Request $request, $id = null)
    {
        //This function is for add/update Delivery Mode
        $delivery = '';
        if ($id != null) {
            $delivery = DB::table('delivery_mode')->where('id', $id)->first();
        }

        if ($request->isMethod('post')) {
            $cat_check = DB::table('delivery_mode')->where('mode_name', $request->mode_name)->where('id', '!=', $request->id)->first();
            if ($cat_check) {
                return redirect()->route("admin.deliveryModeList")->with("error", "Delivery Mode Already Exist.");
            } else {
                $type = DeliveryMode::find($request->id);
                if (!$type) {
                    $type = new DeliveryMode();
                }

                $type->mode_name     = $request->mode_name;
                $type->created_at    = date('Y-m-d H:i:s');
                $type->save();
                return redirect()->route("admin.deliveryModeList")->with("success", "Delivery Mode updated successfully.");
            }
        }
        return view('admin.add_delivery_mode', compact('delivery'));
    }
    public function deliveryModeList()
    {
        $delivery = DB::table('delivery_mode')->where('is_deleted', 0)->paginate(20);
        return view('admin.delivery_mode_list', compact('delivery'));
    }
    public function bulkDeleteMode(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No Delivery Mode selected.');
        }

        DeliveryMode::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected Delivery Mode deleted successfully.');
    }
    public function updateModeStatus(Request $request)
    {
        //This will update the delivery mode status
        $user = DeliveryMode::find($request->user);
        $user->is_active = $request->status;
        $user->save();
    }

    public function blogList()
    {
        $blogs = DB::table('master_blogs')->where('is_deleted', 0)->orderBy('id', 'DESC')->paginate(20);
        return view('admin.blog_list', compact('blogs'));
    }
    public function addBlog(Request $request, $id = null)
    {
        $blog_detail = "";
        if ($id != null) {
            $blog_detail = DB::table('master_blogs')->where('id', $id)->first();
        }
        if ($request->isMethod('post')) {
            $blog = Blog::find($request->id);
            if (!$blog) {
                $blog = new Blog();
            }

            if ($request->hasFile('blog_image')) {
                $image = $request->file('blog_image');
                $imageName = "blog" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/blog_files'), $imageName);
                $blog->blog_image = $imageName;
            }

            if ($request->video_url != '') {
                $blog->blog_video = $request->video_url;
            }

            if ($request->hasFile('video_file')) {
                $image = $request->file('video_file');
                $imageName = "blg" . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads/blog_files'), $imageName);
                $blog->blog_video = $imageName;
            }

            $blog->blog_name       = $request->blog_name;
            $blog->blog_content     = $request->blog_content;
            $blog->video_type       = $request->video_type;

            $blog->created_at     = date('Y-m-d H:i:s');
            $blog->save();
            return redirect()->route("admin.blogList")->with("success", "Master blog updated successfully.");
        }

        return view('admin.add_blog', compact('blog_detail'));
    }
    public function updateBlogStatus(Request $request)
    {
        $user = Blog::find($request->user);
        $user->is_active = $request->status;
        $user->save();
    }
    public function bulkDeleteBlog(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'No blogs selected.');
        }

        Blog::whereIn('id', $ids)->update(['is_deleted' => 1]);

        return redirect()->back()->with('success', 'Selected blogs
         deleted successfully.');
    }
    public function addEmailTemplate()
    {
        return view('admin.add_template');
    }

    public function enquiryList()
    {
        // $enquiry = DB::table('enquiry')->where('is_deleted',0)->orderBy('id', 'DESC')->paginate(20);
        $enquiry = DB::table('enquiry')
            ->join('users as user', 'user.id', '=', 'enquiry.user_id')
            ->join('users as coach', 'coach.id', '=', 'enquiry.coach_id')
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
                'enquiry.id',
                'enquiry.enquiry_title',
                'enquiry.enquiry_detail'
            )
            ->orderBy('enquiry.id', 'DESC') // specify the table name here
            ->paginate(20);

        //dd($enquiry);
        return view('admin.enquiry_list', compact('enquiry'));
    }

    public function viewEnquiry(Request $request)
    {

        $id = $request->id;

        if ($id != null) {

            $user_detail = DB::table('enquiry')
                ->join('users as user', 'user.id', '=', 'enquiry.user_id')
                ->join('users as coach', 'coach.id', '=', 'enquiry.coach_id')
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
                    'enquiry.id',
                    'enquiry.enquiry_title',
                    'enquiry.enquiry_detail'
                )
                ->where('enquiry.id', $id)
                ->where('user.user_type', 2)
                ->where('user.user_status', 1)
                ->first();

            $coach_detail = DB::table('enquiry')
                ->join('users as coach', 'coach.id', '=', 'enquiry.coach_id')
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
                    'enquiry.id',
                    'enquiry.enquiry_title',
                    'enquiry.enquiry_detail'
                )
                ->where('enquiry.id', $id)
                ->where('coach.user_type', 3)
                ->where('coach.user_status', 1)
                ->first();

            $enquiry_detail = DB::table('enquiry')
                ->join('users', 'users.id', '=', 'enquiry.user_id')
                ->select('users.*', 'enquiry.enquiry_title', 'enquiry.enquiry_detail')
                ->where('enquiry.id', $id)
                ->first();
        }
        return view('admin.view_enquiry_profile', compact('coach_detail', 'user_detail', 'enquiry_detail'));
    }
}
