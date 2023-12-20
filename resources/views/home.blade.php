@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        <p>Welcome, {{ auth()->user()->name }}!</p>
                        <p>Your email: {{ auth()->user()->email }}</p>
                        <p>Login Type: {{ auth()->user()->google_id ? 'Google' : 'Traditional' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
