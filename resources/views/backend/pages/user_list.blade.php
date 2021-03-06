@extends('admin.admin_master')


@section('admin_dashboard_content')






<div class="container-fluid">
  <div class="db-breadcrumb">
    <h4 class="breadcrumb-title">Users</h4>
    <ul class="db-breadcrumb-list">
      <li><a href="{{route('admin.home')}}"><i class="fa fa-home"></i>Home</a></li>
      <li>User</li>
    </ul>
  </div>
  @if(Session::has('user_deleted'))
  <div class="alert alert-danger" role="alert">

    <div class="alert-body">
      {{Session::get('user_deleted')}}
    </div>
  </div>
  @endif
  <!-- Card -->

  <div class="row" id="basic-table">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Users</h4>
       <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#">Add</a>
        </div>


        <!-- Modal -->

        <div class="table table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Id</th>
                <th>User Name</th>

                <th>Email</th>
                <th>Phone Number</th>
                <th>Role</th>
                <th>Enrollment</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $users = App\Models\User::all();

               ?>
              @foreach ($users as $row)

              <tr>
                <td>{{$row->id}}</td>
                <td>
                    {{$row->name}}

                </td>

                <td>
                    {{$row->email}}
                </td>
                <td>
                    {{$row->phone}}
                </td>
                <td>
                  @if($row->is_admin==1)
                  Admin
                  @else
                  User
                  @endif


                </td>

                <td><span class="badge badge-pill badge-light-primary mr-1"></span></td>
                <td>
                  <a href=""><i class="fas fa-edit"></i></a>
                  <a href="/admin/home/users/delete/{{$row->id}}"><i class="fas fa-trash"></i></a>

                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>


<script>
        function previewImage(input){
          var file=$("input[type=file]").get(0).files[0];
          if(file)
          {
            var reader = new FileReader();
            reader.onload =function()
            {
              $('#image').attr("src",reader.result);
            }
            reader.readAsDataURL(file);
          }
        }

</script>



@endsection
