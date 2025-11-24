<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\FaqModel;
use Illuminate\Validation\Rule;

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
        $faqs = FaqModel::select('faq_model.*', 'faq_category_model.name as category_name')
            ->join('faq_category_model','faq_model.category_id','=','faq_category_model.id')
            ->orderBy('category_id', 'asc')
            ->orderBy('position', 'asc')
            ->paginate(100);

        return view('admin.faq_list',compact('faqs'));

    }

    public function addFaqs(Request $request, $id = null)
    {


         if ($request->ajax()) {
                $faq = FaqModel::find($request->faq_id);

                if (!$faq) {
                    return response()->json(['success' => false, 'message' => 'FAQ not found.']);
                }

                $faq->is_active = $request->status;
                $faq->save();

                return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        $audiance= DB::table('faq_category_model')->select('id', 'name')->get();

        $faqs=null;
        if($id!=null){
           $faqs = DB::table('faq_model')->select('id','category_id','title', 'description','is_active','position')->find($id);
        }


        if($request->isMethod('post')){

             $getFaq=FaqModel::find($request->faq_id);

             if(!$getFaq){
                 $getFaq= new FaqModel();
             }
             if(!$request->status){
                $request->validate([
                    'faq_title' => 'required',
                    'faq_content' => 'required',
                    'faq_category_id' => 'required',
                    'status' => 'required',
                        'position' => [
                        'required',
                        'integer',
                        'min:1',
                        Rule::unique('faq_model')->where(function ($query) use ($request) {
                            return $query->where('category_id', $request->faq_category_id)
                                        ->where('id', '!=', $request->faq_id ?? 0);
                        }),
                    ],
                ]);
             }

            $getFaq->category_id =$request->faq_category_id;
            $getFaq->title =$request->faq_title;
            $getFaq->description =$request->faq_content;
            $getFaq->is_active =$request->status;
            $getFaq->position =$request->position;
            $getFaq->save();

            return redirect()->route('admin.faqs.index')->with("success", "FAQs updated successfully.");
        }


        return view('admin.add_faqs',compact('audiance','faqs'));
    }


    public function destroy(Request $request)
    {
        $faq = FaqModel::find($request->id);

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

    public function updatePosition(Request $request)
    {
        if (!$request->order || !is_array($request->order)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid order data'
            ]);
        }

        $grouped = [];
        foreach ($request->order as $item) {
            $grouped[$item['category_id']][] = $item;
        }

        foreach ($grouped as $categoryId => $items) {

            usort($items, function ($a, $b) {
                return $a['position'] <=> $b['position'];
            });

            foreach ($items as $index => $row) {
                FaqModel::where('id', $row['id'])
                    ->where('category_id', $categoryId)
                    ->update([
                        'position' => $index + 1    
                    ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'FAQ positions updated successfully (category-wise).'
        ]);
    }



}
