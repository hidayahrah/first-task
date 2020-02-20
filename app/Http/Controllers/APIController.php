<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class APIController extends Controller
{
    
    use AuthenticatesUsers;
    public $successStatus = 200;

    public function index()
    {
        //$users = User::all();
        $users = User::orderBy('created_at','desc')->paginate(10);
        return response()->json(['success' => $users], $this->successStatus); 
    }
    
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register() 
    { 
        return view('vimigo.register');
    }

    public function showLogin() 
    { 
        return view('vimigo.login');
    }

    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this->successStatus); 
    }

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(Request $request)
    { 
        $messages = [
            'email' => 'Invalid email address.',
        ];
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email', 
            'password' => 'required', 
        ]);
        if ($validator->fails()) 
        { 
            return response()->json(['error'=>$validator->errors()], 401);      
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken; 
            return response()->json(['success' => $success], $this->successStatus);
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'state' => 'required', 
            'phone' => 'required',
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
            'cover_image'  => 'required|image|max:2048',
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
        }else
        {
            $fileNameToStore = 'noimage.jpg';
        }
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $input['cover_image'] = $fileNameToStore;
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken; 
        $success['name'] =  $user->name;

        return response()->json(['success' => $success], $this->successStatus);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'state' => 'required', 
            'phone' => 'required',
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

        return response()->json(['updated' => $user], $this->successStatus);
    }

    public function delete(User $user)
    {
        return view('vimigo.delete', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['removed' => $user], $this->successStatus);
    }

    public function show(User $user)
    {
        return view('vimigo.show', compact('user'));
    }

    public function showCreate()
    {
        return view('vimigo.create');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'state' => 'required', 
            'phone' => 'required',
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
            'cover_image'  => 'required|image|max:2048',
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
        }else
        {
            $fileNameToStore = 'noimage.jpg';
        }
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $input['cover_image'] = $fileNameToStore;
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken; 
        $success['name'] =  $user->name;

        return response()->json(['success' => $success], $this->successStatus);
    }

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['logged out' => $success], $this->successStatus);
    }
}
