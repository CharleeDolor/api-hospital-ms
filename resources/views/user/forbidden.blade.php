@extends('layouts.user')

@section('content')
<style>
    p.text-secondary{
        font-size: 10rem;
        line-height: 0.82;
    }
</style>
<div class="container-fluid w-100 h-100 d-flex align-items-center justify-content-center flex-column p-0">
    <div class="w-100 h-50 d-flex justify-content-center align-items-end">
        <p class="m-0 text-secondary">403</p>
    </div>
    <div class="w-100 h-50">
        <div class=" w-100 h-100 bg-secondary p-0 m-0 d-flex flex-column align-items-center">
            <p class="h1 mt-4 text-light">Sorry, this page is locked</p>
            <p class="text-center p-2 text-light">Access not granted</p>
            <div>
                <a class="btn btn-info" href="{{route('user.login')}}">Go back</a>
            </div>
            
        </div>
    </div>
    
    
</div>
