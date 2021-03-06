@extends('frontend.layouts.master')


@section('content')



<!-- Content -->

<div class="page-content bg-white">
    <!-- inner page banner -->
    <div class="page-banner ovbl-dark" style="background-image:url({{ asset('images/banner/banner2.jpg')}});">
        <div class="container">
            <div class="page-banner-entry">
              <br/>
              <br/>
                <h1 class="text-white">Courses Details</h1>
     </div>
        </div>
    </div>
<!-- Breadcrumb row -->
<div class="breadcrumb-row">
  <div class="container">
    <ul class="list-inline">
      <li><a href="{{route('home')}}">Home</a></li>
      <li>Courses Details</li>
    </ul>
  </div>
</div>
<!-- Breadcrumb row END -->
    <!-- inner page banner END -->
    <?php
    $courses = App\Models\Course::all();

    ?>


    <?php
    $course_categories= App\Models\CourseCategory::all();
    $main_categories= App\Models\MainCategory::all();








     ?>





<div class="content-block">
        <!-- About Us -->
  <div class="section-area section-sp1">
            <div class="container">
       <div class="row d-flex flex-row-reverse">
        <div class="col-lg-3 col-md-4 col-sm-12 m-b30">
          <div class="course-detail-bx">
            <div class="course-price">
              <del>{{$course->regular_price}}৳</del>
              <h4 class="price">{{$course->sale_price}}৳</h4>
            </div>
            <div class="course-buy-now text-center">




              <a href="/home/course_details/view/{{$course->id}}" class="btn radius-xl text-uppercase">Enroll this Course</a>



            </div>
            <br>
              <div class="course-buy-now text-center">
                <form class="hidden" action="{{route('add-carts')}}" method="post">
                  @csrf
                  <input type="hidden" name="course_id" value="{{$course->id}}">

                  <button  class="btn">Buy Now</button>
                </form>
                  </div>
            <br>
            <div class="course-buy-now text-center">
            <form class="hidden" action="{{route('add-carts')}}" method="post">
              @csrf
              <input type="hidden" name="course_id" value="{{$course->id}}">

              <button  class="btn">Add to Cart</button>
            </form>
          </div>
            <div class="teacher-bx">
              <div class="teacher-info">
              <!--  <div class="teacher-thumb">
                  <img src="{{ asset('images/testimonials/pic1.jpg')}}" alt=""/>
                </div>-->
                <!--<div class="teacher-name">
                  <h5>Hinata Hyuga</h5>
                  <span>Science Teacher</span>
                </div>-->
              </div>
            </div>
            <div class="cours-more-info">
              <div class="review">
                <span>Review</span>
                <ul class="cours-star">
                  <li class="active"><i class="fa fa-star"></i></li>
                  <li class="active"><i class="fa fa-star"></i></li>
                  <li class="active"><i class="fa fa-star"></i></li>
                  <li class="active"><i class="fa fa-star"></i></li>
                  <li class="active"><i class="fa fa-star"></i></li>

                </ul>
              </div>
              <div class="price categories">
                <span>Categories</span>
                <h5 class="text-primary">{{$course->course_category->mcategory_title}}</h5>
              </div>
            </div>
            <div class="course-info-list scroll-page">
              <ul class="navbar">
                <li><a class="nav-link" href="#overview"><i class="ti-zip"></i>Overview</a></li>
                <li><a class="nav-link" href="#curriculum"><i class="ti-bookmark-alt"></i>Curriculum</a></li>
              <!--  <li><a class="nav-link" href="#instructor"><i class="ti-user"></i>Instructor</a></li>
                <li><a class="nav-link" href="#reviews"><i class="ti-comments"></i>Reviews</a></li>-->
              </ul>
            </div>
          </div>
        </div>

        <div class="col-lg-9 col-md-8 col-sm-12">
          <div class="courses-post">
            <div class="ttr-post-media media-effect">
              <a href="#"><img src="{{ asset('images/blog/default/thum1.jpg')}}" alt=""></a>
            </div>
            <div class="ttr-post-info">
              <div class="ttr-post-title ">
                <h2 class="post-title">{{$course->course_title}}</h2>
              </div>
              <div class="ttr-post-text">
                <p>{{$course->course_details->short_description}}</p>
              </div>
            </div>
          </div>
          <div class="courese-overview" id="overview">
            <h4>Overview</h4>
            <div class="row">
              <div class="col-md-12 col-lg-4">
                <ul class="course-features">
                  <li><i class="ti-book"></i> <span class="label">Lectures</span> <span class="value">8</span></li>
                  <li><i class="ti-help-alt"></i> <span class="label">Quizzes</span> <span class="value">{{$course->course_details->quiz}}</span></li>
                  <li><i class="ti-time"></i> <span class="label">Duration</span> <span class="value">60 hours</span></li>
                  <li><i class="ti-stats-up"></i> <span class="label">Skill level</span> <span class="value">{{$course->course_details->skill}}</span></li>
                  <li><i class="ti-smallcap"></i> <span class="label">Language</span> <span class="value">{{$course->course_details->language}}</span></li>
                  <li><i class="ti-user"></i> <span class="label">Students</span> <span class="value">32</span></li>
                  <li><i class="ti-check-box"></i> <span class="label">Assessments</span> <span class="value">Yes</span></li>
                </ul>
              </div>
              <div class="col-md-12 col-lg-8">
                <h5 class="m-b5">Course Description</h5>
                <p>{{$course->course_details->course_description}}</p>
                <h5 class="m-b5">Certification</h5>
                <p>{{$course->course_details->certification}}</p>
                <h5 class="m-b5">Learning Outcomes</h5>
                <ul class="list-checked primary">
                  <li>{{$course->course_details->learning_outcomes}}</li>

                </ul>
              </div>
            </div>
          </div>
          <div class="m-b30" id="curriculum">
            <h4>Curriculum</h4>

              <section id="accordion-hover">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card collapse-icon">

                      <div class="card-body">
                        @if(count($course->sections) > 0)
                        @foreach($course->sections as $section)
                        <div class="accordion" id="accordionExample{{$section->id}}" data-toggle-hover="true">
                          <div class="collapse-default">

                            <div class="card">
                              <div
                                class="card-header"
                                id="heading{{$section->id}}"
                                data-toggle="collapse"
                                role="button"
                                data-target="#collapse{{$section->id}}"
                                aria-expanded="true"
                                aria-controls="collapse{{$section->id}}"
                              >
                                <h6 class="curriculum-list" style="color:#ca2128; text-transform:uppercase;" >{{$section->section_name}}</h6>
                              </div>

                              <div
                                id="collapse{{$section->id}}"
                                class="collapse show"
                                aria-labelledby="heading{{$section->id}}"
                                data-parent="#accordionExample{{$section->id}}"
                              >
                                <div class="card-body">
                                  <ul>
                                    @if(count($section->lessons) > 0)
                                    @foreach($section->lessons as $lesson)




                                      <div class="curriculum-list-box">
                                        <div class="row">

                                      <div class="col">


                                         <strong>{{$lesson->lesson_title}}</strong>
                                        </div>
                                    <div class="col">


                                      <span><strong>120 minutes</strong></span>
                                    </div>
                                        </div>
                                        </div>

                                    @endforeach
                                      @endif



                                  </ul>


                                </div>
                              </div>
                            </div>


                          </div>
                        </div>
                        @endforeach
                        @endif

                      </div>
                    </div>
                  </div>
                </div>
              </section>
















          </div>
        <!--  <div class="" id="instructor">
            <h4>Instructor</h4>
            <div class="instructor-bx">
              <div class="instructor-author">
                <img src="{{ asset('images/testimonials/pic1.jpg')}}" alt="">
              </div>
              <div class="instructor-info">
                <h6>{{$course->course_details->instructor_id}}</h6>
                <span>Professor</span>
                <ul class="list-inline m-tb10">
                  <li><a href="#" class="btn sharp-sm facebook"><i class="fa fa-facebook"></i></a></li>
                  <li><a href="#" class="btn sharp-sm twitter"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="#" class="btn sharp-sm linkedin"><i class="fa fa-linkedin"></i></a></li>
                  <li><a href="#" class="btn sharp-sm google-plus"><i class="fa fa-google-plus"></i></a></li>
                </ul>
                <p class="m-b0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries</p>
              </div>
            </div>
            <div class="instructor-bx">
              <div class="instructor-author">
                <img src="{{ asset('images/testimonials/pic2.jpg')}}" alt="">
              </div>
              <div class="instructor-info">
                <h6>Keny White </h6>
                <span>Professor</span>
                <ul class="list-inline m-tb10">
                  <li><a href="#" class="btn sharp-sm facebook"><i class="fa fa-facebook"></i></a></li>
                  <li><a href="#" class="btn sharp-sm twitter"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="#" class="btn sharp-sm linkedin"><i class="fa fa-linkedin"></i></a></li>
                  <li><a href="#" class="btn sharp-sm google-plus"><i class="fa fa-google-plus"></i></a></li>
                </ul>
                <p class="m-b0">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries</p>
              </div>
            </div>
          </div>
          <div class="" id="reviews">
            <h4>Reviews</h4>

            <div class="review-bx">
              <div class="all-review">
                <h2 class="rating-type">3</h2>
                <ul class="cours-star">
                  <li class="active"><i class="fa fa-star"></i></li>
                  <li class="active"><i class="fa fa-star"></i></li>
                  <li class="active"><i class="fa fa-star"></i></li>
                  <li><i class="fa fa-star"></i></li>
                  <li><i class="fa fa-star"></i></li>
                </ul>
                <span>3 Rating</span>
              </div>
              <div class="review-bar">
                <div class="bar-bx">
                  <div class="side">
                    <div>5 star</div>
                  </div>
                  <div class="middle">
                    <div class="bar-container">
                      <div class="bar-5" style="width:90%;"></div>
                    </div>
                  </div>
                  <div class="side right">
                    <div>150</div>
                  </div>
                </div>
                <div class="bar-bx">
                  <div class="side">
                    <div>4 star</div>
                  </div>
                  <div class="middle">
                    <div class="bar-container">
                      <div class="bar-5" style="width:70%;"></div>
                    </div>
                  </div>
                  <div class="side right">
                    <div>140</div>
                  </div>
                </div>
                <div class="bar-bx">
                  <div class="side">
                    <div>3 star</div>
                  </div>
                  <div class="middle">
                    <div class="bar-container">
                      <div class="bar-5" style="width:50%;"></div>
                    </div>
                  </div>
                  <div class="side right">
                    <div>120</div>
                  </div>
                </div>
                <div class="bar-bx">
                  <div class="side">
                    <div>2 star</div>
                  </div>
                  <div class="middle">
                    <div class="bar-container">
                      <div class="bar-5" style="width:40%;"></div>
                    </div>
                  </div>
                  <div class="side right">
                    <div>110</div>
                  </div>
                </div>
                <div class="bar-bx">
                  <div class="side">
                    <div>1 star</div>
                  </div>
                  <div class="middle">
                    <div class="bar-container">
                      <div class="bar-5" style="width:20%;"></div>
                    </div>
                  </div>
                  <div class="side right">
                    <div>80</div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->

        </div>

      </div>
    </div>
        </div>
    </div>






<!-- contact area END -->

</div>
<!-- Content END-->




@endsection
