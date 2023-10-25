@extends('layout.master')
@section('content')
    <main id="content" role="main">
        <div class="container content-space-1 content-space-t-md-1">
            <div class="row justify-content-md-between">
                <div class="col-md-6 mb-7 mb-md-0">
                    <div class="card card-lg">
                        <div class="card-body">
                            <form method="post" class="form w-100" action="{{ route('product.store', $product->slug) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6 mb-4 mb-sm-0">
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
                                    <div class="col-sm-6 mb-4 mb-sm-0">
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
                                @if($product->get_address)
                                    <div class="row">
                                        <div class="col-sm-6 mb-4 mb-sm-0">
                                            <div class="mb-4">
                                                <label class="form-label">استان</label>
                                                <input type="text" class="form-control" name="province" value="{{ old('province') }}" maxlength="255" placeholder="استان" aria-label="استان">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-4">
                                                <label class="form-label">شهر</label>
                                                <input type="text" class="form-control" name="city" value="{{ old('city') }}" maxlength="255" placeholder="شهر" aria-label="شهر">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-4">
                                                <label class="form-label">آدرس</label>
                                                <input type="text" class="form-control" name="address" value="{{ old('address') }}" maxlength="255" placeholder="آدرس" aria-label="آدرس">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-4">
                                                <label class="form-label">کد پستی</label>
                                                <input type="text" class="form-control" name="zip" value="{{ old('zip') }}" maxlength="255" placeholder="کد پستی" aria-label="کد پستی">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label">درگاه</label>
                                    </div>
                                    @foreach($drivers as $driver)
                                        <div class="col-sm-30 mb-2">
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
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="row  text-center pt-md-10">
                        <div class="mx-lg-auto mb-7">
                            <h1 class="display-6">{{ $product->title }}</h1>
                            <p class="fs-6">{{ $product->description }}</p>
                        </div>
                        <div class="text-primary fw-bold">
                            <span class="display-4">{{ number_format($product->price) }}</span>
                            <span class="fs-5">ریال</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
