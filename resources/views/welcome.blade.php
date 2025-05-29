<!DOCTYPE html>
<html lang="en">
    <head>
        <title>CoachSparkle</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="icon" type="image/png" href="./imges/favicon.png" />
        <!-- Bootstrap 5.3.3 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />

        <!-- Font Awesome 6.5.0 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

        <!-- Owl Carousel CSS -->
        <link href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
        <link href="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/assets/owl.theme.default.min.css" rel="stylesheet" />

        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ url('/public') }}/assets/style.css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@5.3.0/css/swiper.min.css" />

        <!-- jQuery (required for Owl Carousel) -->
        <script src="https://owlcarousel2.github.io/OwlCarousel2/assets/vendors/jquery.min.js"></script>

        <!-- Owl Carousel JS -->
        <script src="https://owlcarousel2.github.io/OwlCarousel2/assets/owlcarousel/owl.carousel.min.js"></script>

        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/swiper@5.3.0/js/swiper.min.js"></script>
    </head>

    <body>
        <!-- header-navbar -->
        <nav class="navbar navbar-expand-lg coach-top-navber-add">
            <div class="container">
                <a class="navbar-logo-add" href="#"><img src="{{ url('/public') }}/assets/imges/logo.png" alt="Logo" /></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <ul class="navbar-nav list-show">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Browse Coaches
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Coaches 1</a></li>
                                <li><a class="dropdown-item" href="#">Coaches 2</a></li>
                                <li><a class="dropdown-item" href="#">Coaches 3</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Get Match
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Match 1</a></li>
                                <li><a class="dropdown-item" href="#">Match 2</a></li>
                                <li><a class="dropdown-item" href="#">Match 3</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                For Corporate
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Corporate 1</a></li>
                                <li><a class="dropdown-item" href="#">Corporate 2</a></li>
                                <li><a class="dropdown-item" href="#">Corporate 3</a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">Events</a>
                        </li>
                    </ul>

                    <div class="register-login">
                        <div class="register-content">
                            <a href="#" class="Login-navbar">Login</a>
                            <a href="#" class="sign-up-navbar">Sign Up</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- First-section-start -->
        <div class="smarter-matching py-5">
            <div class="container">
                <div class="row smarter-matching-inner align-items-center">
                    <div class="col-md-7 smarter-matching-left">
                        <h1 class="display-5 fw-bold">
                            Smarter Matching.<br />
                            Human Connections. <br />
                            Better Outcomes.
                        </h1>
                        <p class="lead">Find your fit. Build your path. Achieve more.</p>
                        <div class="search-container">
                            <input type="text" class="form-control search-input" placeholder="Enter Name, Keywords..." />
                            <i class="fas fa-search search-icon"></i>
                        </div>

                        <div class="counters-content">
                            <div class="row counters-inner-content">
                                <div class="four col-md-4">
                                    <div class="counter-box">
                                        <span class="counter" data-count="680">0</span>
                                        <p>Available Coaches</p>
                                    </div>
                                </div>
                                <div class="four col-md-4">
                                    <div class="counter-box">
                                        <span class="counter" data-count="8000">0</span>
                                        <p>Matches made</p>
                                    </div>
                                </div>
                                <div class="four col-md-4">
                                    <div class="counter-box">
                                        <span class="counter" data-count="100">0</span>
                                        <p>Countries represented</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 smarter-matching-right">
                        <marquee direction="up" height="628px" id="coachMarquee">
                            <div class="card p-2 d-flex flex-row align-items-center">
                                <div class="coach-img-left-side me-3">
                                    <img src="{{ url('/public') }}/assets/imges/ellipse-one.png" alt="Coach Image" />
                                </div>

                                <div class="coach-name-right-side">
                                    <h5 class="mb-1">Coach Name Will Go Here</h5>
                                    <p class="mb-1">Staff Software Engineer at eBay</p>
                                    <div class="coach-software-name">
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-2 d-flex flex-row align-items-center">
                                <div class="coach-img-left-side me-3">
                                    <img src="{{ url('/public') }}/assets/imges/ellipse-two.png" alt="Coach Image" />
                                </div>

                                <div class="coach-name-right-side">
                                    <h5 class="mb-1">Coach Name Will Go Here</h5>
                                    <p class="mb-1">Staff Software Engineer at eBay</p>
                                    <div class="coach-software-name">
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-2 d-flex flex-row align-items-center">
                                <div class="coach-img-left-side me-3">
                                    <img src="{{ url('/public') }}/assets/imges/ellipse-three.png" alt="Coach Image" />
                                </div>

                                <div class="coach-name-right-side">
                                    <h5 class="mb-1">Coach Name Will Go Here</h5>
                                    <p class="mb-1">Staff Software Engineer at eBay</p>
                                    <div class="coach-software-name">
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-2 d-flex flex-row align-items-center">
                                <div class="coach-img-left-side me-3">
                                    <img src="{{ url('/public') }}/assets/imges/ellipse-two.png" alt="Coach Image" />
                                </div>

                                <div class="coach-name-right-side">
                                    <h5 class="mb-1">Coach Name Will Go Here</h5>
                                    <p class="mb-1">Staff Software Engineer at eBay</p>
                                    <div class="coach-software-name">
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-2 d-flex flex-row align-items-center">
                                <div class="coach-img-left-side me-3">
                                    <img src="{{ url('/public') }}/assets/imges/ellipse-three.png" alt="Coach Image" />
                                </div>

                                <div class="coach-name-right-side">
                                    <h5 class="mb-1">Coach Name Will Go Here</h5>
                                    <p class="mb-1">Staff Software Engineer at eBay</p>
                                    <div class="coach-software-name">
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-2 d-flex flex-row align-items-center">
                                <div class="coach-img-left-side me-3">
                                    <img src="{{ url('/public') }}/assets/imges/ellipse-one.png" alt="Coach Image" />
                                </div>

                                <div class="coach-name-right-side">
                                    <h5 class="mb-1">Coach Name Will Go Here</h5>
                                    <p class="mb-1">Staff Software Engineer at eBay</p>
                                    <div class="coach-software-name">
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-2 d-flex flex-row align-items-center">
                                <div class="coach-img-left-side me-3">
                                    <img src="{{ url('/public') }}/assets/imges/ellipse-three.png" alt="Coach Image" />
                                </div>

                                <div class="coach-name-right-side">
                                    <h5 class="mb-1">Coach Name Will Go Here</h5>
                                    <p class="mb-1">Staff Software Engineer at eBay</p>
                                    <div class="coach-software-name">
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-2 d-flex flex-row align-items-center">
                                <div class="coach-img-left-side me-3">
                                    <img src="{{ url('/public') }}/assets/imges/ellipse-three.png" alt="Coach Image" />
                                </div>

                                <div class="coach-name-right-side">
                                    <h5 class="mb-1">Coach Name Will Go Here</h5>
                                    <p class="mb-1">Staff Software Engineer at eBay</p>
                                    <div class="coach-software-name">
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card p-2 d-flex flex-row align-items-center">
                                <div class="coach-img-left-side me-3">
                                    <img src="{{ url('/public') }}/assets/imges/ellipse-two.png" alt="Coach Image" />
                                </div>

                                <div class="coach-name-right-side">
                                    <h5 class="mb-1">Coach Name Will Go Here</h5>
                                    <p class="mb-1">Staff Software Engineer at eBay</p>
                                    <div class="coach-software-name">
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                        <a href="#" class="me-2">Software</a>
                                    </div>
                                </div>
                            </div>
                        </marquee>
                    </div>
                </div>
            </div>
        </div>

        <!-- second-section-start -->

        <div class="global-companies">
            <div class="container">
                <h1 class="text-center">Trusted by 1000+ global companies</h1>

                <div class="owl-carousel owl-theme owl-loaded owl-drag">
                    <div class="owl-stage-outer">
                        <div class="owl-stage" style="transform: translate3d(-1527px, 0px, 0px); transition: all 0.25s ease 0s; width: 3334px;">
                            <div class="owl-item cloned" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-one.png" />
                                </div>
                            </div>

                            <div class="owl-item cloned" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-two.png" />
                                </div>
                            </div>

                            <div class="owl-item cloned" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-three.png" />
                                </div>
                            </div>

                            <div class="owl-item cloned" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-four.png" />
                                </div>
                            </div>

                            <div class="owl-item cloned" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-five.png" />
                                </div>
                            </div>

                            <div class="owl-item cloned" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-six.png" />
                                </div>
                            </div>

                            <div class="owl-item cloned" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-one.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-two.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-three.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-four.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-five.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-five.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-four.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-one.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-two.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-six.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-four.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-one.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-three.png" />
                                </div>
                            </div>

                            <div class="owl-item" style="width: 128.906px; margin-right: 10px;">
                                <div class="item">
                                    <img src="{{ url('/public') }}/assets/imges/global-img-two.png" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-nav disabled"></div>
        </div>

        <!-- third-section-start -->
        <div class="dedicated-career-coach">
            <div class="container">
                <div class="row dedicated-career-coach-inner">
                    <!-- Left Section -->
                    <div class="col-md-6 mb-4 mb-md-0 dedicated-career-coach-left">
                        <h1 class="mb-3">At your fingertips: a dedicated career coach</h1>
                        <p>
                            Work one-on-one with a dedicated coach who understands your goals and challenges. Whether you're exploring new paths, preparing for a big interview, or looking to advance in your current role, your coach is here
                            to provide personalized advice, actionable strategies, and consistent support—right when you need it.
                        </p>
                        <p>
                            With your dedicated career coach just a message away, you'll get clear direction, honest feedback, and motivation tailored to your goals. From polishing your resume to practicing interviews or pivoting
                            careers—we’ve got your back.
                        </p>
                        <a href="#" class="learn-more-btn-add">LEARN MORE <i class="bi bi-arrow-right"></i></a>
                    </div>

                    <!-- Right Section -->
                    <div class="col-md-6 dedicated-career-coach-right">
                        <img src="{{ url('/public') }}/assets/imges/career-coach-img.png" alt="Career Coach" class="img-fluid" />
                    </div>
                </div>

                <div class="row coaching-approach-inner">
                    <!-- Left Section -->
                    <div class="col-md-6 coaching-approach-right">
                        <img src="{{ url('/public') }}/assets/imges/coaching-approach-img.png" alt="coaching approach" class="img-fluid" />
                    </div>

                    <!-- Right Section -->
                    <div class="col-md-6 mb-4 mb-md-0 coaching-approach-left">
                        <h1 class="mb-3">
                            Pick the coaching approach<br class="mobile-add-br" />
                            that fits
                        </h1>
                        <div class="clear-informative">
                            <div class="informative-text">
                                <img src="{{ url('/public') }}/assets/imges/informative-icons.png" />
                                <div>
                                    <h5>Clear and Informative</h5>
                                    <p>Whether you prefer structured sessions, flexible chat-based support, or on-demand advice, we offer coaching styles to match your personality.</p>
                                </div>
                            </div>
                        </div>

                        <div class="clear-informative">
                            <div class="informative-text">
                                <img src="{{ url('/public') }}/assets/imges/informative-icons.png" />
                                <div>
                                    <h5>Friendly and Empowering</h5>
                                    <p>Whether you prefer structured sessions, flexible chat-based support, or on-demand advice, we offer coaching styles to match your personality.</p>
                                </div>
                            </div>
                        </div>

                        <div class="clear-informative">
                            <div class="informative-text">
                                <img src="{{ url('/public') }}/assets/imges/informative-icons.png" />
                                <div>
                                    <h5>Guided with Choices</h5>
                                    <p>Whether you prefer structured sessions, flexible chat-based support, or on-demand advice, we offer coaching styles to match your personality.</p>
                                </div>
                            </div>
                        </div>

                        <div class="clear-informative">
                            <div class="informative-text">
                                <img src="{{ url('/public') }}/assets/imges/informative-icons.png" />
                                <div>
                                    <h5>Personalized and Reassuring</h5>
                                    <p>Whether you prefer structured sessions, flexible chat-based support, or on-demand advice, we offer coaching styles to match your personality.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- forth-section-start -->
        <div class="explore-coaches">
            <div class="container">
                <div class="explore-coaches-section">
                    <div class="row explore-coaches-inner-content">
                        <div class="col-md-12 adipiscing-text">
                            <h1>Explore 6,000+ available coaches</h1>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        </div>
                        <div class="professional-top">
                            <a href="#" class="view-all-add-btn text-right">VIEW ALL<i class="bi bi-arrow-right"></i></a>
                            <div class="professional-cards">
                                <div class="card">
                                    <img src="{{ url('/public') }}/assets/imges/explore-ellipse-one.png" alt="Career & Professional" class="img-fluid" />
                                    <h5>Career & Professional Coaches</h5>
                                    <ul>
                                        <li><i class="bi bi-check-lg"></i>Career Coach</li>
                                        <li><i class="bi bi-check-lg"></i>Executive Coach</li>
                                        <li><i class="bi bi-check-lg"></i>Leadership Coach</li>
                                    </ul>
                                </div>

                                <div class="card">
                                    <img src="{{ url('/public') }}/assets/imges/explore-ellipse-one.png" alt="Personal Development" class="img-fluid" />
                                    <h5>Personal Development & Life Coaches</h5>
                                    <ul>
                                        <li><i class="bi bi-check-lg"></i>Life Coach</li>
                                        <li><i class="bi bi-check-lg"></i>Confidence Coach</li>
                                        <li><i class="bi bi-check-lg"></i>Mindset Coach</li>
                                    </ul>
                                </div>

                                <div class="card">
                                    <img src="{{ url('/public') }}/assets/imges/explore-ellipse-one.png" alt="Wellness & Health" class="img-fluid" />
                                    <h5>
                                        Wellness & Health <br />
                                        Coaches
                                    </h5>
                                    <ul>
                                        <li><i class="bi bi-check-lg"></i>Health Coach</li>
                                        <li><i class="bi bi-check-lg"></i>Fitness Coach</li>
                                        <li><i class="bi bi-check-lg"></i>Nutrition Coach</li>
                                    </ul>
                                </div>

                                <div class="card">
                                    <img src="{{ url('/public') }}/assets/imges/explore-ellipse-one.png" alt="Family & Youth" class="img-fluid" />
                                    <h5>Family, Relationship & Youth Coaches</h5>
                                    <ul>
                                        <li><i class="bi bi-check-lg"></i>Academic Coach</li>
                                        <li><i class="bi bi-check-lg"></i>Learning Specialist</li>
                                        <li><i class="bi bi-check-lg"></i>Language Coach</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- five-section-start -->
        <div class="view-all-coaches-list">
            <div class="container">
                <div class="search-container">
                    <div class="search-input">
                        <input type="text" class="form-control search-input" placeholder="Search Coaches..." />
                        <i class="fas fa-search search-icon"></i>
                    </div>
                    <div class="view-all-btn">
                        <a href="#">View All Coaches <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="row view-all-coaches-view">
                    <div class="col-12 col-sm-6 col-md-3 coaches-view-cards">
                        <div class="card h-100">
                            <img src="{{ url('/public') }}/assets/imges/coaches-img-two.png" class="card-img-top" alt="Coach Image" />
                            <div class="card-body">
                                <h5 class="card-title"><a href="#">Coach Name Will Go Here</a></h5>
                                <p class="card-text">Staff Software Engineer at eBay</p>
                                <div class="software-engineer-list">
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 coaches-view-cards">
                        <div class="card h-100">
                            <img src="{{ url('/public') }}/assets/imges/coaches-img-two.png" class="card-img-top" alt="Coach Image" />
                            <div class="card-body">
                                 <h5 class="card-title"><a href="#">Coach Name Will Go Here</a></h5>
                                <p class="card-text">Staff Software Engineer at eBay</p>
                                <div class="software-engineer-list">
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 coaches-view-cards">
                        <div class="card h-100">
                            <img src="{{ url('/public') }}/assets/imges/coaches-img-one.png" class="card-img-top" alt="Coach Image" />
                            <div class="card-body">
                                 <h5 class="card-title"><a href="#">Coach Name Will Go Here</a></h5>
                                <p class="card-text">Staff Software Engineer at eBay</p>
                                <div class="software-engineer-list">
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 coaches-view-cards">
                        <div class="card h-100">
                            <img src="{{ url('/public') }}/assets/imges/coaches-img-two.png" class="card-img-top" alt="Coach Image" />
                            <div class="card-body">
                                 <h5 class="card-title"><a href="#">Coach Name Will Go Here</a></h5>
                                <p class="card-text">Staff Software Engineer at eBay</p>
                                <div class="software-engineer-list">
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row view-all-coaches-view">
                    <div class="col-12 col-sm-6 col-md-3 coaches-view-cards">
                        <div class="card h-100">
                            <img src="{{ url('/public') }}/assets/imges/coaches-img-two.png" class="card-img-top" alt="Coach Image" />
                            <div class="card-body">
                                 <h5 class="card-title"><a href="#">Coach Name Will Go Here</a></h5>
                                <p class="card-text">Staff Software Engineer at eBay</p>
                                <div class="software-engineer-list">
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 coaches-view-cards">
                        <div class="card h-100">
                            <img src="{{ url('/public') }}/assets/imges/coaches-img-two.png" class="card-img-top" alt="Coach Image" />
                            <div class="card-body">
                                 <h5 class="card-title"><a href="#">Coach Name Will Go Here</a></h5>
                                <p class="card-text">Staff Software Engineer at eBay</p>
                                <div class="software-engineer-list">
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 coaches-view-cards">
                        <div class="card h-100">
                            <img src="{{ url('/public') }}/assets/imges/coaches-img-one.png" class="card-img-top" alt="Coach Image" />
                            <div class="card-body">
                                 <h5 class="card-title"><a href="#">Coach Name Will Go Here</a></h5>
                                <p class="card-text">Staff Software Engineer at eBay</p>
                                <div class="software-engineer-list">
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3 coaches-view-cards">
                        <div class="card h-100">
                            <img src="{{ url('/public') }}/assets/imges/coaches-img-two.png" class="card-img-top" alt="Coach Image" />
                            <div class="card-body">
                                 <h5 class="card-title"><a href="#">Coach Name Will Go Here</a></h5>
                                <p class="card-text">Staff Software Engineer at eBay</p>
                                <div class="software-engineer-list">
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                    <a href="#">Software</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- six-section-start -->
        <div class="how-it-work">
            <div class="container">
                <p class="text-center">PROCESS OVERVIEW</p>
                <h1 class="text-center">How it Works</h1>
                <div class="row how-it-work-inner-part">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h1>01</h1>
                                <h5 class="card-title">Search Your Coach</h5>
                                <p class="card-text">Browse our network of certified career coaches based on your goals, industry, and preferred coaching style.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card two">
                            <div class="card-body">
                                <h1>02</h1>
                                <h5 class="card-title">Match</h5>
                                <p class="card-text">Get smart recommendations tailored to your needs—or pick the coach that feels right for you.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h1>03</h1>
                                <h5 class="card-title">Connect</h5>
                                <p class="card-text">Start your journey with one-on-one sessions, personalized guidance, and ongoing support, right when you need it.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- seven-section-start -->
        <div class="people-love-living">
            <div class="container">
                <h1>People Love Living with Coach Sparkle</h1>
                <p>Aliquam lacinia diam quis lacus euismod</p>

                <div class="swiper-container slide-container">
                    <div class="swiper-wrapper">
                        <!-- Each slide -->
                        <div class="swiper-slide">
                            <div class="card-content">
                                <h2 class="name">Great Work</h2>
                                <h5 class="description">“At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et”</h5>
                                <img src="https://votivephp.in/realstate/resources/assets/img/star-symbol.png" />
                                <div class="good-jobs-content">
                                    <img src="https://votivephp.in/realstate/resources/assets/img/people-one.png" />
                                    <div>
                                        <h4>Ali Tufan</h4>
                                        <h5>Marketing</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="card-content">
                                <h2 class="name">Good Job</h2>
                                <h5 class="description">“Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae”</h5>
                                <img src="https://votivephp.in/realstate/resources/assets/img/star-symbol.png" />
                                <div class="good-jobs-content">
                                    <img src="https://votivephp.in/realstate/resources/assets/img/people-two.png" />
                                    <div>
                                        <h4>Albert Flores</h4>
                                        <h5>Designer</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="card-content">
                                <h2 class="name">Perfect</h2>
                                <h5 class="description">“Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo”</h5>
                                <img src="https://votivephp.in/realstate/resources/assets/img/star-symbol.png" />
                                <div class="good-jobs-content">
                                    <img src="https://votivephp.in/realstate/resources/assets/img/people-three.png" />
                                    <div>
                                        <h4>Robert Fox</h4>
                                        <h5>Developer</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- more slides -->
                    </div>

                    <!-- Navigation buttons -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>

                    <!-- Pagination bullets -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>

        <!-- eight-section-start -->
        <div class="latest-articles-explore">
            <div class="container">
                <h1>Latest articles</h1>
                <p>Explore our Free Acticles</p>
                <div class="row latest-articles-inner">
                    <div class="articles-btn-top">
                        <a href="#" class="articles-btn-add">All articles</a>
                    </div>
                    <div class="latest-articles-cards-content">
                        <div class="col-12 col-sm-6 col-md-4 latest-articles-cards">
                            <div class="card h-100">
                                <img src="{{ url('/public') }}/assets/imges/articles-img-one.png" class="card-img-top" alt="Coach Image" />
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">Article Heading</h5>
                                    <h6><i class="bi bi-calendar"></i> Apr 30, 2025</h6>
                                    <p class="card-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem...</p>
                                    <a href="#" class="read-more-btn">Read More..</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 latest-articles-cards">
                            <div class="card h-100">
                                <img src="{{ url('/public') }}/assets/imges/articles-img-two.png" class="card-img-top" alt="Coach Image" />
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">Article Heading</h5>
                                    <h6><i class="bi bi-calendar"></i> Apr 30, 2025</h6>
                                    <p class="card-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem...</p>
                                    <a href="#" class="read-more-btn">Read More..</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 latest-articles-cards">
                            <div class="card h-100">
                                <img src="{{ url('/public') }}/assets/imges/articles-img-three.png" class="card-img-top" alt="Coach Image" />
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">Article Heading</h5>
                                    <h6><i class="bi bi-calendar"></i> Apr 30, 2025</h6>
                                    <p class="card-text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem...</p>
                                    <a href="#" class="read-more-btn">Read More..</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- nine-section-start -->
        <div class="choose-plan-you">
            <div class="container">
                <h1 class="text-center">
                    Choose Plan <br />
                    That’s Right For You
                </h1>
                <p class="text-center">Choose plan that works best for you, feel free to contact us</p>
                <div class="row">
                    <div class="pricing">
                        <div class="col-md-4">
                            <div class="card">
                                <h3>Introductory Call</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur</p>
                                <h2>$<span class="number-add">0</span></h2>
                                <div class="user-list-plan">
                                    <ul>
                                        <li><i class="bi bi-check"></i>Features</li>
                                        <li><i class="bi bi-check"></i>Features</li>
                                        <li><i class="bi bi-check"></i> Features</li>
                                        <li><i class="bi bi-check"></i>Features</li>
                                        <li><i class="bi bi-check"></i>Features</li>
                                    </ul>
                                    <button>Signup</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card featured">
                                <h3>Study Plan</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur</p>
                                <h2>$<span class="number-add">8</span></h2>
                                <span class="save">Save <span> $50 </span> a year</span>
                                <div class="user-list-plan">
                                    <ul>
                                        <li><i class="bi bi-check"></i>Features</li>
                                        <li><i class="bi bi-check"></i>Features</li>
                                        <li><i class="bi bi-check"></i> Features</li>
                                        <li><i class="bi bi-check"></i>Features</li>
                                        <li><i class="bi bi-check"></i>Features</li>
                                    </ul>
                                    <button>Signup</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <h3>Premium</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur</p>
                                <h2>$<span class="number-add">16</span></h2>
                                <div class="user-list-plan">
                                    <ul>
                                        <li><i class="bi bi-check"></i>Features</li>
                                        <li><i class="bi bi-check"></i>Features</li>
                                        <li><i class="bi bi-check"></i> Features</li>
                                        <li><i class="bi bi-check"></i>Features</li>
                                        <li><i class="bi bi-check"></i>Features</li>
                                    </ul>
                                    <button>Signup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ten-section-start -->
        <div class="your-organization-coach">
            <div class="container">
                <div class="row organization-coach">
                    <h1 class="text-center">Transform your organization with Coach Sparkle today</h1>
                    <p class="text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <div class="register-add">
                        <a href="#" class="register-now-btn">REGISTER NOW</a>
                    </div>
                </div>
            </div>
        </div>
        <footer class="coach-footer-section text-white py-5">
            <div class="container">
                <div class="row coach-footer-inner">
                    <div class="col-md-3 coach-footer-one">
                        <img src="{{ url('/public') }}/assets/imges/logo.png" alt="Logo" />
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
                    </div>

                    <div class="col-md-2 coach-footer-two">
                        <h5>Informational Links</h5>
                        <ul class="list-unstyled">
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#" class="text-decoration-none">About Us</a></li>
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#" class="text-decoration-none">Contact Us</a></li>
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#" class="text-decoration-none">FAQ</a></li>
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#" class="text-decoration-none">Terms of Services</a></li>
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#" class="text-decoration-none">Privacy Policy</a></li>
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#" class="text-decoration-none">Cookie Policy</a></li>
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#" class="text-decoration-none">Site Map</a></li>
                        </ul>
                    </div>

                    <div class="col-md-2 coach-footer-three">
                        <h5>Platform</h5>
                        <ul class="list-unstyled">
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#" class="text-decoration-none">Browse Coaches</a></li>
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#" class="text-decoration-none">Get Match</a></li>
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#" class="text-decoration-none">List As Coach</a></li>
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#" class="text-decoration-none">For Corporate</a></li>
                            <li><i class="fa fa-angle-right" aria-hidden="true"></i> <a href="#" class="text-decoration-none">Events</a></li>
                        </ul>
                    </div>

                    <div class="col-md-2 coach-footer-three">
                        <h5>Social Media</h5>
                        <ul class="list-unstyled">
                            <li><i class="fa fa-facebook" aria-hidden="true"></i> <a href="#" class="text-decoration-none">Facebook</a></li>
                            <li><i class="fa fa-instagram" aria-hidden="true"></i> <a href="#" class="text-decoration-none">Instagram</a></li>
                            <li><i class="fa fa-linkedin" aria-hidden="true"></i> <a href="#" class="text-decoration-none">LinkedIn</a></li>
                            <li><i class="bi bi-youtube"></i> <a href="#" class="text-decoration-none">YouTube</a></li>
                            <li><i class="fa-brands fa-x-twitter" aria-hidden="true"></i> <a href="#" class="text-decoration-none">Twitter</a></li>
                        </ul>
                    </div>

                    <div class="col-md-3 coach-footer-four">
                        <h5>Newsletter</h5>
                        <form class="">
                            <p>Sign up to receive the latest articles</p>
                            <div class="mb-2">
                                <input type="email" class="form-control" placeholder="Your email address" />
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Sign Up</button>

                            <label class="form-check-box">
                                <input type="checkbox" name="terms" required />
                                I have read and agree to the terms & conditions
                            </label>
                        </form>
                    </div>
                </div>
            </div>
        </footer>

        <div class="rights-reserved">
            <p>© 2025 Coach Sparkle. All rights reserved.</p>
        </div>
    </body>

    <!-- js-add -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const marquee = document.getElementById("coachMarquee");

        marquee.addEventListener("mouseover", function () {
            marquee.stop();
        });

        marquee.addEventListener("mouseout", function () {
            marquee.start();
        });
    </script>

    <script>
        var owl = $(".owl-carousel");
        owl.owlCarousel({
            loop: true,
            margin: 10,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 3, // Show 1 items for mobile devices (width 0px and up)
                },
                768: {
                    items: 4, // Show 4 items for tablets and up
                },
                1024: {
                    items: 6, // Show 6 items for desktops and up
                },
            },
        });
    </script>

    <script>
        var swiper = new Swiper(".swiper-container", {
            slidesPerView: 3,
            spaceBetween: 25,
            loop: true,
            grabCursor: true,
            centeredSlides: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                dynamicBullets: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    </script>

    <!-- Counter Script -->
    <script>
        $(document).ready(function () {
            $(".counter").each(function () {
                let $this = $(this);
                let countTo = parseInt($this.attr("data-count"), 10);

                $({ countNum: 0 }).animate(
                    { countNum: countTo },
                    {
                        duration: 2000,
                        easing: "swing",
                        step: function () {
                            $this.text(Math.floor(this.countNum));
                        },
                        complete: function () {
                            // Format number
                            if (countTo >= 1000) {
                                let kValue = Math.round(countTo / 1000); // 8000 -> 8
                                $this.text(kValue + "K+");
                            } else {
                                $this.text(countTo);
                            }
                        },
                    }
                );
            });
        });
    </script>
</html>
