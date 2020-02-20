@include('index')
@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Delete {{ $user->name }} Profile?</h1>

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

      <div class="text-center">
            <img src="/storage/cover_images/{{ $user->cover_image }}" width="300" class="rounded-circle img-fluid">  
        </div>
        
      <form action="" method="post">
      {{ csrf_field() }}

      <div class="form-group">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" disabled/>
      </div>

      <div class="form-group">
      <label for="state">State</label>
      <input type="text" id="state" name="state" class="form-control" value="{{ $user->state }}" disabled/>
      </div>

      <div class="form-group">
      <label for="phone">Phone Number</label>
      <input type="text" id="phone" name="phone" class="form-control" value="{{ $user->phone }}" disabled/>
      </div>

      <div class="form-group">
      <label for="email">Email</label>
      <input type="text" id="email" name="email" class="form-control" value="{{ $user->email }}" disabled/>
      </div>

      <button class="btn btn-primary" type="submit">Delete</button>
      <a class="btn btn-secondary" href="/home">Back</a>
      </form>
    </main>
@include('partials.footer')