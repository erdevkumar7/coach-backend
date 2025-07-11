<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqAndSupportController extends Controller
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = DB::table('faqs')
            ->join('faq_category_model', 'faqs.faq_category_id', '=', 'faq_category_model.id')
            ->select('faqs.id', 'faqs.title', 'faqs.content', 'faqs.status', 'faq_category_model.name as category_name')
            ->get();

        return view('admin.faq_list',compact('faqs'));

    }

    public function addFaqs(Request $request, $id = null)
    {

         if ($request->ajax()) {
                $faq = Faq::find($request->faq_id);

                if (!$faq) {
                    return response()->json(['success' => false, 'message' => 'FAQ not found.']);
                }

                $faq->status = $request->status;
                $faq->save();

                return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        $audiance= DB::table('faq_category_model')->select('id', 'name')->get();

        $faqs=null;
        if($id!=null){
           $faqs = DB::table('faqs')->select('id','faq_category_id','title', 'content','status')->find($id);
        }


        if($request->isMethod('post')){

             $getFaq=Faq::find($request->faq_id);

             if(!$getFaq){
                 $getFaq= new Faq();
             }
             if(!$request->status){
                $request->validate([
                    'faq_title' => 'required',
                    'faq_content' => 'required',
                    'faq_category_id' => 'required',
                    'status' => 'required',
                ]);
             }

            $getFaq->faq_category_id =$request->faq_category_id;
            $getFaq->title =$request->faq_title;
            $getFaq->content =$request->faq_content;
            $getFaq->status =$request->status;
            $getFaq->save();

            return redirect()->route('admin.faqs.index')->with("success", "FAQs updated successfully.");
        }


        return view('admin.add_faqs',compact('audiance','faqs'));
    }

    // public function show()
    // {
    //     $faqs = DB::table('faqs')
    //         ->join('faq_category_model', 'faqs.faq_category_id', '=', 'faq_category_model.id')
    //         ->select('faqs.id', 'faqs.title', 'faqs.content', 'faqs.status', 'faq_category_model.name as category_name')
    //         ->where('faqs.status', 1)
    //         ->get();

    //     return view('admin.view_faq', compact('faqs'));
    // }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $faq = Faq::find($request->id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ not found.'
            ]);
        }

        $faq->delete();

        return response()->json([
            'success' => true,
            'message' => 'FAQ deleted successfully.'
        ]);
    }

}
