@extends('frontend.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
@endpush

@section('content')
    <!-- Service Detail Hero Section -->
    <section class="ai-pages service-hero-section py-10">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="service-title">Artificial Intelligence (AI) and Advanced Tech</h1>
                    <p class="service-description">
                        AI represents a transformative technology with the potential to revolutionize
                        organizations services and operations.</br>
                        By leveraging AI, organizations can enhance efficiency, improve decision-making
                        and deliver superior to public.</br>
                        By investing in AI education, training and projects, organizations can better meet
                        the needs of their people, drive innovation and ensure sustainable development.
                    </p>
                </div>
            </div>
            <!-- Service Details -->
            <div class="service-details py-5">
                <div class="row">
                    <div class="col-md-6">
                        <img src="{{ asset('images/04_AI/01.jpg') }}" alt="AI Technologies" class="img-fluid">
                    </div>
                    <div class="right-text col-md-6">
                        <h2>We Customize Transformation Projects</h2>
                        <p>
                            JADCO and partners support to harness the power of AI
                            and digital technologies to help improve business operations
                            and organization thrive.
                            We help to explore ways to leverage new advances in digital-tech
                            to re-invent how things get done and boost your organization
                            positioning in its sector.
                            We analyze the existing structure, navigate challenges and evaluate
                            ways that technology can affect factors in your organization and
                            identify new business models enabled by AI and explore
                            opportunities presented by AI.
                            Learn how to shape your AI business strategy, organizational
                            dynamics, products, services innovation and evolving workforce
                            skills and discover practical solutions to build business advantage.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/services.js') }}?v={{ rand() }}"></script>
@endpush
