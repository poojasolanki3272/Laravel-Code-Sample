<?php
namespace App\Http\Controllers\Auth;
namespace App\Http\Controllers\Admin;
use App\Cms;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
class CmsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        //$this->middleware('auth.admin');
        $this->adminName = $request->route()->getName();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $currentData = Cms::orderby('id', 'DESC')->get();
        $data['cms'] = $currentData;
        return View('admin.pages.cms',$data);
    }

   /* public function showform()
    {
        return View('admin.add_homeslider');
    }*/

    public function showform($id=NULL)
    {      

        if(!empty($id)){
             $cms = Cms::find($id);
             return View('admin.pages.add_cms')->with('cms', $cms);
        }else{
            return View('admin.pages.add_cms');
        }

        /*return $slider = Testimonial::where('id',$id);
        return View('admin.add_homeslider',['slider'=>$slider]);*/
    }
    
    public function store(Request $request) {

        $post =  $request->input();
        $validator = Validator::make($request->all(), [
            'page_name' => 'required',
            'page_title' => 'required',
            'description' => 'required',
            //'image' => 'required|image|max:5000|mimes:jpeg,png',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }else {

        
            Cms::create([
                'page_name' => $post['page_name'],
                'page_title' => $post['page_title'],
                'keywords' => $post['keywords'],
                'meta_title' => $post['meta_title'],
                'meta_description' => $post['meta_description'],
                'description' => $post['description'],
                //'image' => $img,
                'status' => $post['status'],
            ]);


            return redirect('admin/ManageCms')->with('message', 'Cms added successfully.');
        }
    }
    public function update(Request $request,$id){

         $request->input();
        $validator = Validator::make($request->all(), [
            'page_name' => 'required',
            'page_title' => 'required',
            'description' => 'required',
            'image' => 'image|max:5000|mimes:jpeg,png',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }else{


            $image =  $request->file('image');
            $post =  $request->input();
            $description =  str_replace("'", " ", $post['description']);
            Cms::where('id',$id)->update(['page_name' => $post['page_name'],'page_title' => $post['page_title'],'keywords' => $post['keywords'],'meta_title' => $post['meta_title'],'meta_description' => $post['meta_description'],'description' => $description,'status' => $post['status']]);

             return redirect('admin/ManageCms')->with('message', 'Cms updated successfully.');

        }
    }
    public function deleteslide($id){

        Cms::find($id)->delete();
        return response()->json([
            'success' => 'Record has been deleted successfully!'
        ]);
    }

    public function statusupadate(Request $req)
    {
        $data = Input::all();
        if(request()->ajax())
        {
            $id = Input::get('id');
            $option = Cms::where('id', $id)->first();
            $option->status = $data['status'] == 'N' ? 'Y' : 'N';
            $option->update();
            return response()->json([
                'success' => 'Record has been deleted successfully!'
            ]);
        }
    }

}
