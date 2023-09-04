@extends('layouts.landing.app-v2')
@section('cancellation_policy','active')
@section('title', translate('messages.cancellation_policy'))

@section('content')

        <!-- Page Header Gap -->
        <div class="h-148px"></div>
        <!-- Page Header Gap -->

        <!-- ======= Privacy Section ======= -->
        <section class="privacy-section">
            <div class="container">
                <div class="section-wrapper">
                    <div class="section-wrapper-inner">
                        <div class="section-header mw-100">
                            <h2 class="title"> <span class="text-base">{{translate('messages.cancellation_policy')}}</span></h2>
                        </div>
                        <div class="about--content">
                            {!! $data['data'] !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ======= Privacy Section ======= -->
@endsection
