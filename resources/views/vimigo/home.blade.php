@include('partials.header')

<main role="main" class="flex-shrink-0">
<div class="container">
<br>
    <img src="/storage/cover_images/{{ Auth::user()->cover_image }}" height="100" width="100" class="rounded-circle img-fluid float-right">  
<br>
<h3>Welcome, {{Auth::user()->name}}.</h3>
<div class="table-responsive">
      <a class="btn btn-primary" href="/vimigo/register">Add New User</a>
      <br><br>
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>State</th>
              <th>Email</th>
              <th>Contact Number</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          @foreach($users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->state }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->phone }}</td>
              <td>
              <a href="/vimigo/{{ $user->id }}" class="btn btn-primary">Update</a>
              <a href="/vimigo/delete/{{ $user->id }}" class="btn btn-danger">Delete</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
</div>

  <div class = "row">
    <div class = "col-12 d-flex justify-content-center">
      {{ $users->links() }}
    </div>
  </div>

</div>
</main>

@include('partials.footer')