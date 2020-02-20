<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class APIController extends Controller
{
    use AuthenticatesUsers;

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken; 
            return response()->json(['success' => $success], 200);
        } else { 
                    return response()->json(['error'=>'Unauthorised'], 401); 
               } 
    }

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function logout()
    {
        Auth::logout();
        return response()->json('logged out');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'state' => 'nullable|string', 
            'phone' => 'nullable|string',
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        
        if($request->hasFile('cover_image'))
        {
            //Get filename with extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore); 
        } else  {
                    $fileNameToStore = 'noimage.jpg';
                }

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $input['cover_image'] = $fileNameToStore;
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken; 
        $success['name'] =  $user->name;

        return response()->json(['success' => $success], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'state' => 'nullable|string', 
            'phone' => 'nullable|string',
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        if($request->hasFile('cover_image'))
        {
            //Get filename with extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore); 
        } else  {
                    $fileNameToStore = 'noimage.jpg';
                }

        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $input['cover_image'] = $fileNameToStore;
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken; 
        $success['name'] =  $user->name;

        return response()->json(['success' => $success], 201);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->name;
        if($search)
        {
            $searchFields = ['name','state','phone','email'];
            $users = User::where(function($q) use ($search, $searchFields) 
            {
                foreach($searchFields as $sf) {
                  $q->orWhere($sf, 'like', "%$search%", 'created_at desc');
                }
            })->get();
        } else {
            $users = User::orderBy('created_at','desc')->paginate(10);
        }

        return response()->json($users, 200); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        $user = User::find($user);
        if($user)
        {
            return response()->json($user, 200); 

        } else {
            return response()->json('User not found.', 404); 
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'state' => 'nullable|string', 
            'phone' => 'nullable|string',
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        if($request->hasFile('cover_image'))
        {
            // Get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
            // Delete file if exists
            Storage::delete('public/cover_images/'.$user->cover_image);
        }

        if($request->hasFile('cover_image'))
        {
            $user->cover_image = $fileNameToStore;
        }

        $user->name = request('name');
        $user->state = request('state');
        $user->phone = request('phone');
        $user->email = request('email');
        $user->password = bcrypt($request->get('password'));
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([], 200);
    }

    public function import() 
    {
        $import = new UsersImport(); 
        Excel::import($import,request()->file('file'));
           
        return response()->json($import->info, 200);
    }

}
