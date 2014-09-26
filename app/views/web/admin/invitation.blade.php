@extends('web.components.boilerplate')

@section('title')
Generate Invitation Codes  
@stop

@section('content')

<div class="container">
      <div class="row">
        <div class="col-md-12">
	<h1>Invitation Codes</h1>
	    <hr />
	</div>
      <div class="col-md-12">
	    <h4>Create Code</h4>
		  {{ Form::open(array('url'=>'admin/invitations', 'class'=>'form-inline')) }}
			<div class="form-group">
			  <label class="sr-only" for="code">Invition Code</label>
			  <input class="form-control" name="code" id="code" value="danno-{{ dechex(mt_rand (0, 0xffffffffff)) }}">
			</div>

			<div class="form-group">
			  <label class="sr-only" for="note">Note</label>
			  <input class="form-control" name="note" id="note" placeholder="Enter a note...">
			</div>

		      	{{ Form::submit('Create Invitation', array('class'=>'btn btn-default'))}}
			{{ Form::close() }}
      </div>
      <div class="col-md-12">
      <hr />
	    <h4>Unclaimed Codes</h4>
		  <table class="table table-striped">
			<tbody>
			@foreach($unclaimed as $invite)
			      <tr> 
				    <td>{{ $invite->code }}</td>
				    <td>{{ $invite->note }}</td>
				    <td>Created on {{ $invite->created_at }}</td>
				    <td><a href="{{ URL::action('AdminController@getInvitationDel', array('id'=>$invite->id)) }}">Cancel Invitation</a></td>
			      </tr>
			@endforeach
			</tbody>
		  </table>
	    <h4>Claimed Codes</h4>
		  <table class="table table-striped">
			<tbody>
			@foreach($claimed as $invite)
			      <tr> 
				    <td>{{ $invite->code }}</td>
				    <td>{{ $invite->note }}</td>
				    <td>Claimed by {{ $invite->claimedemail }}</td>
				    <td>Created on {{ $invite->created_at }}</td>
				    <td>Claimed on {{ $invite->updated_at }}</td>
			      </tr>
			@endforeach
			</tbody>
		  </table>
      
      </div>
	    
        
      </div>
    </div>
      
@stop