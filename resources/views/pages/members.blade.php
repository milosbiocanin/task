@extends('layouts.app', ['title' => __('Team')])

@section('content')
  @include('users.partials.header', [
		'title' => __('Users'),
		'class' => 'col-lg-7'
	])   

  <div class="container-fluid mt--7">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card bg-secondary shadow">
          <div class="card-header bg-white border-0">
            <div class="row align-items-center justify-content-between">
                <h3 class="mb-0">{{ __('User List') }}</h3>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#member-form">New</button>
            </div>
          </div>
          <div class="card-body">
            @if($errors->any())
            <div class="alert alert-warning">
              <p>{{$errors->first()}}</p>
            </div>
            @endif
            <div class="member-list">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Role</th>
                  <th scope="col">Last Login</th>
                </tr>
              </thead>
              <tbody>
                @foreach($members as $member)
                <tr>
                  <td>{{$member->name}}</td>
                  <td>{{$member->email}}</td>
                  <td>{{$member->lvl->name}}</td>
                  <td>{{$member->last_login}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" tabindex="-1" id="member-form">
      <div class="modal-dialog">
        <form method="post" action="{{url('/setting/members/create')}}">
        @csrf
				@method('post')
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Add a new user</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <div class="text-right form-label col-sm-4">NAME</div>
              <div class="col-sm-8">
                <input type="text" class="form-control form-control-sm" name="name" required/>
              </div>
            </div>
            <div class="form-group row">
              <div class="text-right form-label col-sm-4">EMAIL</div>
              <div class="col-sm-8">
                <input type="email" class="form-control form-control-sm" name="email" required/>
              </div>
            </div>
            <div class="form-group row">
              <div class="text-right form-label col-sm-4">PASSWORD</div>
              <div class="col-sm-8">
                <input type="password" class="form-control form-control-sm" name="password" required/>
              </div>
            </div>
            <div class="form-group row">
              <div class="text-right form-label col-sm-4">ROLE</div>
              <div class="col-sm-8">
                <select class="form-control form-control-sm" name="role">
                  @foreach ($roles as $role)
                  <option value="{{$role->id}}">{{$role->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  
@endsection