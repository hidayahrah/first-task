<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    
    use AuthenticatesUsers;
    public $successStatus = 200;

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        //$users = User::all();
        $users = User::orderBy('created_at','desc')->paginate(10);
        return view('vimigo.home', compact('users'));
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
        return response()->json(['success' => $user], $this-> successStatus); 
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
            // return response()->json(['error'=>$validator->errors()], 401);   
             return redirect('login')->withErrors($validator)->withInput();         
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            //return response()->json(['success' => $success], $this-> successStatus);
            return redirect()->route('home')->withSuccess('Welcome home!');
        } 
        else{ 
            // return response()->json(['error'=>'Unauthorised'], 401); 
            return redirect('login')->withErrors($messages)->withInput(); 
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
            // return response()->json(['error'=>$validator->errors()], 401);   
             return redirect('/vimigo/register')->withErrors($validator)->withInput();         
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

        return redirect()->route('home')->with('Success', 'User Created!');
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
            // return response()->json(['error'=>$validator->errors()], 401);   
             return redirect()->route('update',$user->id)->withErrors($validator)->withInput();         
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

        return redirect()->route('home')->withSuccess('User Profile has been updated.');
    }

    public function delete(User $user)
    {
        return view('vimigo.delete', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('home');
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
            // return response()->json(['error'=>$validator->errors()], 401);   
             return redirect('create')->withErrors($validator)->withInput();         
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

        return redirect()->route('home')->with('Success', 'User Created!');
    }

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
