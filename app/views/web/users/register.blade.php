@extends('web.components.boilerplate')

@section('title')
Register     
@stop

@section('content')

{{ Form::open(array('url'=>'users/register', 'class'=>'form-signup')) }}
<div class="container">
      <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
          <h3>
            Please Register
          </h3>
          <div class="form-group">
    {{ Form::text('username', null, array('class'=>'form-control', 'placeholder'=>'Username')) }}<br />
    {{ Form::text('email', null, array('class'=>'form-control', 'placeholder'=>'Email')) }}<br />
    {{ Form::text('invitation', null, array('class'=>'form-control', 'placeholder'=>'Invitation Code')) }}<br />
    {{ Form::password('password', array('class'=>'form-control', 'placeholder'=>'Password')) }}<br />
    {{ Form::password('password_confirmation', array('class'=>'form-control', 'placeholder'=>'Confirm Password')) }}<br />
<br /><br />
	        {{ Form::submit('Register', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}
        </div>
	</div>
        <div class="col-md-4">
        </div>
      </div>
    </div>
	
	@stop

