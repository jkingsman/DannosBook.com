@extends('web.components.boilerplate')

@section('title')
Users Config
@stop

@section('content')

<div class="container">
      <div class="row">
        <div class="col-md-12">
	    <h1>Users</h1>
	    <hr />
	</div>
	    <div class="col-md-12">
		  <table class="table table-striped">
			<tbody>
			      @foreach($users as $user)
				    <tr> 
					  <td>{{ $user->username }}</td>
					  <td>{{ $user->email }}</td>
					  <td>{{ $user->pb64 }}</td>
					  <td>Last login at {{ $user->updated_at }}</td>
					  <td><a href="/admin/userdel/{{ $user->id }}">Delete User</a></td>
				    </tr>
			      @endforeach
			</tbody>
		  </table>
	    </div>
      </div>
</div>
      
@stop