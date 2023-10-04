@extends('layout.master')
@section('content')
    <main id="content" role="main">
        <div class="container text-center mt-10">
            <img class="img-fluid mb-5" height="250px" width="250px" src="{{ asset('svg/oc-money-profits.svg') }}" alt="Money" style="width: 20rem;">
            <h1 class="fs-3">
                @if(!$errors->any() && @session('message')['status'] == 'success')
                    پرداخت موفق
                @else
                    خطا
                @endif
            </h1>
            <p class="lead">
                @if(!$errors->any() && @session('message')['status'] == 'success')
                    {{ session('message')['message'] }}
                @else
                    {{ $errors->first() }}
                @endif
            </p>
            <a class="btn btn-soft-primary mt-5" href="{{ route('payment.index') }}">بازگشت</a>
        </div>
        @include('layout.copyright')
    </main>
@endsection
