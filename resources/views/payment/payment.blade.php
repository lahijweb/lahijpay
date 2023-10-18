@extends('layout.master')
@section('content')
    <main id="content" role="main">
        <div class="container content-space-1 content-space-t-md-1">
            <div class="mx-auto" style="max-width: 40rem;">
                @if ($errors->any())
                    <div class="alert alert-warning" role="alert">
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span>
                        @endforeach
                    </div>
                @endif
                <div class="card card-lg card-bordered">
                    <div class="card-header border text-center">
                        <h1 class="card-title fs-4">پرداخت آنلاین</h1>
                        <p class="card-text">پرداخت آنلاین لاهیج پی</p>
                    </div>
                    <div class="card-body">
                        <form method="post" class="form w-100" action="{{route('payment.store')}}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-4">
                                        <label class="form-label">نام</label>
                                        <input type="text" class="form-control" value="{{ old('first_name') }}" maxlength="255" name="first_name" placeholder="نام" aria-label="نام" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-4">
                                        <label class="form-label">نام خانوادگی</label>
                                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" maxlength="255" placeholder="نام خانوادگی" aria-label="نام خانوادگی">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-4">
                                        <label class="form-label">ایمیل</label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" maxlength="255" placeholder="ایمیل" aria-label="ایمیل">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-4">
                                        <label class="form-label">موبایل</label>
                                        <input type="text" class="form-control" name="mobile" value="{{ old('mobile') }}" maxlength="255" placeholder="موبایل" aria-label="موبایل">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label">مبلغ</label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="number" pattern="[0-9]*" step="1" inputmode="numeric" min="10000" name="amount" class="form-control" placeholder="مبلغ به ریال" value="{{ old('amount') }}" aria-label="مبلغ" required>
                                    <span class="input-group-text">ریال</span>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label">درگاه</label>
                                </div>
                                @foreach($drivers as $driver)
                                <div class="col-sm mb-2">
                                    <label class="form-control" for="{{$driver->driver}}">
                                      <span class="form-check">
                                        <input type="radio" class="form-check-input" name="driver" value="{{ $driver->driver }}" id="{{ $driver->driver }}" @checked(old('driver', $driver->is_default))>
                                        <span class="form-check-label">{{ $driver->name }}</span>
                                      </span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg">پرداخت</button>
                            </div>
                        </form>
                    </div>
                </div>
                @include('layout.copyright')
            </div>
        </div>
    </main>
@endsection
