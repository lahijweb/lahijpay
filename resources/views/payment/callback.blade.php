@extends('layout.master')
@section('content')
    <main id="content" role="main">
        <div class="container text-center mt-10">
            <img class="img-fluid mb-5" height="250px" width="250px" src="{{ asset('svg/oc-money-profits.svg') }}" alt="Money" style="width: 15rem;">
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <h1 class="fs-4">{{ $error }}</h1>
                @endforeach
            @endif
            @if(session()->has('message'))
                <h1 class="fs-3">{{ session('message')['statusText'] }}</h1>
                <p class="lead">{{ session('message')['message'] }}</p>
                @if(session('message')['status'] == 402)
                    <p>{{ session('message')['error'] }}</p>
                @endif
                <p>کد رهگیری: {{ session('message')['uuid'] }}</p>
                <p>شناسه تراکنش: {{ session('message')['transactionId'] }}</p>
                @if(session('message')['status'] == 200)
                    <p>شناسه پرداخت: {{ session('message')['referenceId'] }}</p>
                @endif
            @endif
        </div>
        <div class="text-center">
            <a class="btn btn-soft-primary mt-5" href="{{ route('payment.index') }}">بازگشت</a>
        </div>
        @include('layout.copyright')
    </main>
@endsection
