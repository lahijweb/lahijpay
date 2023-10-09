@extends('layout.master')
@section('content')
    <main id="content" role="main">
        <div class="container text-center mt-10">
            <img class="img-fluid mb-5" height="250px" width="250px" src="{{ asset('svg/oc-error.svg') }}" alt="error" style="width: 20rem;">
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <h1 class="fs-4">{{ $error }}</h1>
                @endforeach
            @endif
            @if($message)
                <h1 class="fs-3">{{ $message['statusText'] }}</h1>
                <p class="lead">{{ $message['message'] }}</p>
                @isset($message['data'])
                    @foreach($message['data'] as $data)
                        <p>{{ $data }}</p>
                    @endforeach
                @endisset
            @endif
        </div>
        <div class="text-center">
            <a class="btn btn-soft-primary mt-5" href="{{ route('payment.index') }}">بازگشت</a>
        </div>
        @include('layout.copyright')
    </main>
@endsection
