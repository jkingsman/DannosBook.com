@extends('web.components.boilerplate')

@section('title')
Login     
@stop

@section('content')

{{ Form::open(array('url'=>'/users/login', 'class'=>'form-signin')) }}
<div class="container">
      <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
          <h3>
            Please Login
          </h3>
	        <ul>
		    @foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		    @endforeach
		</ul>
          <div class="form-group">
    {{ Form::text('username', null, array('class'=>'form-control', 'placeholder'=>'Username')) }}<br />
    {{ Form::password('password', array('class'=>'form-control', 'placeholder'=>'Password')) }}<br />
    <center><em>"Book 'em, Danno."</em><br /> Detective Lieutenant Steve McGarrett, Hawaii Five-O</center><br />
	        {{ Form::submit('Login', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}
        </div>
	</div>
        <div class="col-md-4">
        </div>
      </div>
    </div>
      
@stop