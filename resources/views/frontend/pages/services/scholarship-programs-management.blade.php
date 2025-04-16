@extends('frontend.layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/services.css') }}">
@endpush

@section('content')
<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="section-title mb-4">Scholarship Programs Management</h1>
            
            <div class="service-image mb-4">
                <img src="{{ asset('images/services/scholarship-programs.jpg') }}" alt="Scholarship Programs Management" class="img-fluid rounded">
            </div>
            
            <div class="service-content">
                <p class="lead">
                    With more than 20 years in managing scholarship programs with several Saudi governmental 
                    sponsors, we are experts of providing full and comprehensive plans and services to meet 
                    the sponsor's vision and targets.
                </p>
                
                <h3 class="mt-5 mb-3">Our Scholarship Management Services Include:</h3>
                
                <ul class="service-features">
                    <li>
                        <h5>Scholarship Program Design</h5>
                        <p>We develop customized scholarship program frameworks that align with your organizational objectives and educational goals.</p>
                    </li>
                    
                    <li>
                        <h5>Student Selection & Placement</h5>
                        <p>Comprehensive assessment and selection processes to identify the most qualified candidates, followed by placement in appropriate educational institutions.</p>
                    </li>
                    
                    <li>
                        <h5>Academic Monitoring & Support</h5>
                        <p>Continuous tracking of academic progress with intervention strategies to ensure scholar success and program completion.</p>
                    </li>
                    
                    <li>
                        <h5>Financial Management</h5>
                        <p>Efficient handling of stipends, tuition payments, and other financial aspects of scholarship programs with transparent reporting.</p>
                    </li>
                    
                    <li>
                        <h5>Cultural & Social Integration</h5>
                        <p>Programs to help scholars integrate into new educational and cultural environments while maintaining cultural identity.</p>
                    </li>
                    
                    <li>
                        <h5>Career Development & Placement</h5>
                        <p>Post-graduation support to ensure scholars transition successfully into the workforce and contribute to their communities.</p>
                    </li>
                </ul>
                
                <div class="cta-section text-center mt-5 py-4">
                    <h4>Interested in our Scholarship Programs Management services?</h4>
                    <p>Contact us today to discuss how we can help you develop and manage effective scholarship programs.</p>
                    <a href="{{ url('/#contact') }}" class="btn btn-primary mt-3">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/services.js') }}?v={{ rand() }}"></script>
@endpush 