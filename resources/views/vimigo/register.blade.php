@include('index')
@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Register Account</h1>

        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <!-- <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button> -->
          </div>
          <!-- <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
          </button> -->
        </div>
      </div>

  <div class="container">
      <h5>Upload Picture</h5>
      <!-- @if (count($errors) > 0)
        <div class="alert alert-danger">
        Upload Validation Error<br><br>
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
        </div>
      @endif
      @if ($message = Session::get('success'))
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
              <strong>{{ $message }}</strong>
      </div>
      
      <img src="/storage/cover_images/{{ $user->cover_image }}" width="300" />
      @endif -->

   <form action="" method="post" action="{{url('/home')}}" enctype="multipart/form-data">
      {{ csrf_field() }}
    <div class="form-group">
     <table class="table">
      <tr>
       <!-- <td width="40%" align="right"><label>Select File for Upload</label></td> -->
       <td width="30"><input type="file" name="cover_image" class="form-control {{ $errors->has('cover_image') ? 'is-invalid' : ''}}"/></td>
       <!-- <td width="30%" align="left"></td> -->
      </tr>
      <tr>
       <!-- <td width="40%" align="right"></td> -->
       <td width="30"><span class="text-muted">Image size must be less than 2MB.</span></td>
       <!-- <td width="30%" align="left"></td> -->
      </tr>
     </table>
     @if($errors->has('cover_image'))
        <span class="help-block">
        <strong>{{ $errors->first('cover_image') }}</strong>
        </span>
      @endif
    </div>
   <br />
  </div>

      <div class="form-group">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : ''}}"/>
      
      @if($errors->has('name'))
        <span class="help-block">
        <strong>{{ $errors->first('name') }}</strong>
        </span>
      @endif
      </div>

      <div class="form-group">
      <label for="state">State</label>
      <input type="text" id="state" name="state" class="form-control {{ $errors->has('state') ? 'is-invalid' : ''}}"/>
      
      @if($errors->has('state'))
        <span class="help-block">
        <strong>{{ $errors->first('state') }}</strong>
        </span>
      @endif
      </div>

      <div class="form-group">
      <label for="phone">Phone Number</label>
      <input type="text" id="phone" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : ''}}"/>
      
      @if($errors->has('phone'))
        <span class="help-block">
        <strong>{{ $errors->first('phone') }}</strong>
        </span>
      @endif
      </div>

      <div class="form-group">
      <label for="email">Email</label>
      <input type="text" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : ''}}"/>
      
      @if($errors->has('email'))
        <span class="help-block">
        <strong>{{ $errors->first('email') }}</strong>
        </span>
      @endif
      </div>

      <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : ''}}"/>
      
      @if($errors->has('password'))
        <span class="help-block">
        <strong>{{ $errors->first('password') }}</strong>
        </span>
      @endif
      </div>

      <div class="form-group">
      <label for="c_password">Confirm Password</label>
      <input type="password" id="c_password" name="c_password" class="form-control {{ $errors->has('c_password') ? 'is-invalid' : ''}}"/>
      
      @if($errors->has('c_password'))
        <span class="help-block">
        <strong>{{ $errors->first('c_password') }}</strong>
        </span>
      @endif
      </div>

      <button class="btn btn-primary" type="submit">Register</button>
      <a class="btn btn-secondary" href="/home">Back</a>
      </form>
    </main>
@include('partials.footer')