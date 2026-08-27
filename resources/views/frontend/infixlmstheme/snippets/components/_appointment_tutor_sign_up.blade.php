<!-- sign up for tutor:start -->
<div data-type="component-text" data-preview="{{asset('Modules/Appointment/Resources/assets/keditor/snippets/preview/affiliate/become_instructor_sing_up.png')}}" data-keditor-title="Instructor FAQ" data-keditor-categories="Instructor FAQ">
<section class="section_padding_off ins_cta become_tutor">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="ins_cta_inner">
                    <div class="row">
                        <div class="col-xxl-8 offset-xxl-2 col-lg-10 offset-lg-1">
                            <div class="ins_cta_inner_text">
                                <h3>Insegna agli studenti di <br> oltre 180 paesi</h3>
                                <p>I tutor insegnano a oltre 800.000 studenti in tutto il mondo. Unisciti a noi e avrai <br> tutto ciò di cui hai bisogno per insegnare con successo.</p>
                                <ul>
                                    <li><span><i class="fa fa-check"></i></span>Riutilizzabile e completamente personalizzabile</li>
                                    <li><span><i class="fa fa-check"></i></span>Scelto dalle app più quotate</li>
                                    <li><span><i class="fa fa-check"></i></span>Riutilizzabile e completamente personalizzabile</li>
                                </ul>
                                @if(Settings('student_reg')==1 && saasPlanCheck('student')==false)
                                    <a href="{{route('register')}}" class="theme_btn text-uppercase">{{ __('appointment.Sign up to teach') }}</a>
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- sign up for tutor:end -->
