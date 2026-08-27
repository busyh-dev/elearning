@extends(theme('auth.layouts.app'))
@section('content')
    <div class="login_wrapper">
        <div class="login_wrapper_left">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img style="width: 190px" src="{{asset(Settings('logo') )}} " alt="">
                </a>
            </div>
            <div class="login_wrapper_content">
                <h4>{{__('frontend.Welcome back. Please login')}} 
                    <!-- <br>{{__('frontend.to your account')}} </h4> -->

                <div class="socail_links">


                    @if(saasEnv('ALLOW_FACEBOOK_LOGIN')=='true')

                        <a href="{{ route('social.oauth', 'facebook') }}"
                           class="theme_btn small_btn2 text-center facebookLoginBtn">
                            <i class="fab fa-facebook-f"></i>
                            {{__('frontend.Login with Facebook')}}</a>
                    @endif

                    @if(saasEnv('ALLOW_GOOGLE_LOGIN')=='true')
                        <a href="{{ route('social.oauth', 'google') }}"
                           class="theme_btn small_btn2 text-center googleLoginBtn">
                            <i class="fab fa-google"></i>
                            {{__('frontend.Login with Google')}}</a>
                    @endif
                </div>
                @if(saasEnv('ALLOW_FACEBOOK_LOGIN')=='true' || saasEnv('ALLOW_GOOGLE_LOGIN')=='true')
                    <p class="login_text">{{__('frontend.Or')}} {{__('frontend.login with Email Address')}}</p>
                @endif

                <form action="{{route('login')}}" method="POST" id="loginForm">
                    @csrf
                    <div class="row">
                        <div class="col-12 mt_20">
                            <div class="input-group custom_group_field">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">
                                        <!-- svg -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13.328" height="10.662"
                                             viewBox="0 0 13.328 10.662">
                                            <path id="Path_44" data-name="Path 44"
                                                  d="M13.995,4H3.333A1.331,1.331,0,0,0,2.007,5.333l-.007,8a1.337,1.337,0,0,0,1.333,1.333H13.995a1.337,1.337,0,0,0,1.333-1.333v-8A1.337,1.337,0,0,0,13.995,4Zm0,9.329H3.333V6.666L8.664,10l5.331-3.332ZM8.664,8.665,3.333,5.333H13.995Z"
                                                  transform="translate(-2 -4)" fill="#687083"/>
                                        </svg>
                                        <!-- svg -->
                                    </span>
                                </div>
                                <input type="email" value="{{old('email')}}" autocomplete="off"
                                       class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       placeholder="{{__('common.Enter Email')}}" name="email" aria-label="Username"
                                       aria-describedby="basic-addon3">
                            </div>
                            @if($errors->first('email'))
                                <span class="text-danger" role="alert">{{$errors->first('email')}}</span>
                            @endif
                        </div>

                        <div class="col-12 mt_20">
                            <div class="input-group custom_group_field">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon4">
                                        <!-- svg -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10.697" height="14.039"
                                             viewBox="0 0 10.697 14.039">
                                        <path id="Path_46" data-name="Path 46"
                                              d="M9.348,11.7A1.337,1.337,0,1,0,8.011,10.36,1.341,1.341,0,0,0,9.348,11.7ZM13.36,5.68h-.669V4.343a3.343,3.343,0,0,0-6.685,0h1.27a2.072,2.072,0,0,1,4.145,0V5.68H5.337A1.341,1.341,0,0,0,4,7.017V13.7a1.341,1.341,0,0,0,1.337,1.337H13.36A1.341,1.341,0,0,0,14.7,13.7V7.017A1.341,1.341,0,0,0,13.36,5.68Zm0,8.022H5.337V7.017H13.36Z"
                                              transform="translate(-4 -1)" fill="#687083"/>
                                        </svg>
                                        <!-- svg -->
                                    </span>
                                </div>
                                <input type="password" name="password" class="form-control"
                                       autocomplete="new-password"
                                       placeholder="{{__('common.Enter Password')}}" aria-label="password"
                                       aria-describedby="basic-addon4">
                            </div>
                            @if($errors->first('password'))
                                <span class="text-danger" role="alert">{{$errors->first('password')}}</span>
                            @endif
                        </div>
                        <div class="col-12 mt_20">
                            @if(saasEnv('NOCAPTCHA_FOR_LOGIN')=='true')
                                @if(saasEnv('NOCAPTCHA_IS_INVISIBLE')=="true")
                                    {!! NoCaptcha::display(["data-size"=>"invisible"]) !!}
                                @else
                                    {!! NoCaptcha::display() !!}
                                @endif

                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="text-danger"
                                          role="alert">{{$errors->first('g-recaptcha-response')}}</span>
                                @endif
                            @endif
                        </div>
                        <div class="col-12 mt_20">
                            <div class="remember_forgot_pass d-flex justify-content-between flex-wrap row-gap-4">
                                <label class="primary_checkbox d-flex mb-0">
                                <input type="checkbox" name="remember"
                                           {{ old('remember') ? 'checked' : '' }} value="1">
                                    <span class="checkmark me-2"></span>
                                    <span class="label_name">{{__('common.Remember Me')}}</span>
                                </label>
                                @if(Settings('allow_force_logout'))
                                    <label class="primary_checkbox d-flex mb-0">
                                        <input type="checkbox" name="force"
                                               {{ old('force') ? 'checked' : '' }} value="1">
                                        <span class="checkmark me-2"></span>
                                        <span class="label_name">{{__('auth.Force login')}}</span>
                                    </label>
                                @endif
                                <a href="javascript:void(0);" id="forgot_password_btn" class="forgot_pass" data-bs-toggle="modal" data-bs-target="#resetPasswordModal" data-toggle="modal" data-target="#resetPasswordModal">{{__('common.Forgot Password ?')}}</a>
                            </div>
                        </div>
                        <div class="col-12">

                            @if(saasEnv('NOCAPTCHA_FOR_LOGIN')=='true' && saasEnv('NOCAPTCHA_IS_INVISIBLE')=="true")

                                <button type="button" class="g-recaptcha theme_btn text-center w-100"
                                        data-sitekey="{{saasEnv('NOCAPTCHA_SITEKEY')}}" data-size="invisible"
                                        data-callback="onSubmit"
                                        class="theme_btn text-center w-100"> {{__('common.Login')}}</button>
                                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                <script>
                                    function onSubmit(token) {
                                        document.getElementById("loginForm").submit();
                                    }
                                </script>
                            @else
                                <button type="submit"
                                        class="theme_btn text-center w-100"> {{__('common.Login')}}</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            @if(Settings('student_reg')==1 && saasPlanCheck('student')==false)
                <h5 class="shitch_text mb-0">{{__("frontend.Don’t have an account")}}? <a href="{{route('register')}}">
                        {{__('common.Register')}}
                    </a></h5>
            @endif
            @if(config('app.demo_mode'))
                <div class="row g-2 mt-2">
                    @foreach($roles as $role)
                        <div class="col-sm-4 mb_10">
                            <a class="theme_btn small_btn2 text-center w-100"
                               href="{{route('auto.login',$role->id)}}">{{$role->name}}</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @include('frontend.infixlmstheme.auth.login_wrapper_right')
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px; padding: 15px;">
                <div class="modal-header border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title font-weight-bold" id="resetPasswordModalLabel" style="font-size: 18px; font-weight: 600;">
                        Reimposta Password
                    </h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 24px; line-height: 1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="reset_alert_msg" class="alert d-none" role="alert"></div>

                    <!-- STEP 1: Verify Username or Email -->
                    <div id="reset_step_1">
                        <p class="text-muted mb-3" style="font-size: 14px;">
                            Inserisci il tuo Nome Utente o l'indirizzo Email associato al tuo account.
                        </p>
                        <div class="input-group custom_group_field mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13.328" height="10.662" viewBox="0 0 13.328 10.662">
                                        <path id="Path_44" data-name="Path 44" d="M13.995,4H3.333A1.331,1.331,0,0,0,2.007,5.333l-.007,8a1.337,1.337,0,0,0,1.333,1.333H13.995a1.337,1.337,0,0,0,1.333-1.333v-8A1.337,1.337,0,0,0,13.995,4Zm0,9.329H3.333V6.666L8.664,10l5.331-3.332ZM8.664,8.665,3.333,5.333H13.995Z" transform="translate(-2 -4)" fill="#687083"/>
                                    </svg>
                                </span>
                            </div>
                            <input type="text" id="reset_identity_input" class="form-control" placeholder="Nome utente o Email" autocomplete="off">
                        </div>
                        <button type="button" id="btn_verify_identity" class="theme_btn text-center w-100 mt-2">
                            Verifica Account
                        </button>
                    </div>

                    <!-- STEP 2: Enter New Password -->
                    <div id="reset_step_2" class="d-none">
                        <input type="hidden" id="reset_user_id">
                        <p class="text-muted mb-3" style="font-size: 14px;">
                            Account verificato con successo! Inserisci la nuova password.
                        </p>
                        <div class="input-group custom_group_field mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10.697" height="14.039" viewBox="0 0 10.697 14.039">
                                        <path id="Path_46" data-name="Path 46" d="M9.348,11.7A1.337,1.337,0,1,0,8.011,10.36,1.341,1.341,0,0,0,9.348,11.7ZM13.36,5.68h-.669V4.343a3.343,3.343,0,0,0-6.685,0h1.27a2.072,2.072,0,0,1,4.145,0V5.68H5.337A1.341,1.341,0,0,0,4,7.017V13.7a1.341,1.341,0,0,0,1.337,1.337H13.36A1.341,1.341,0,0,0,14.7,13.7V7.017A1.341,1.341,0,0,0,13.36,5.68Zm0,8.022H5.337V7.017H13.36Z" transform="translate(-4 -1)" fill="#687083"/>
                                    </svg>
                                </span>
                            </div>
                            <input type="password" id="reset_new_password" class="form-control" placeholder="Nuova Password (min. 8 caratteri)" autocomplete="new-password">
                        </div>
                        <div class="input-group custom_group_field mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10.697" height="14.039" viewBox="0 0 10.697 14.039">
                                        <path id="Path_46" data-name="Path 46" d="M9.348,11.7A1.337,1.337,0,1,0,8.011,10.36,1.341,1.341,0,0,0,9.348,11.7ZM13.36,5.68h-.669V4.343a3.343,3.343,0,0,0-6.685,0h1.27a2.072,2.072,0,0,1,4.145,0V5.68H5.337A1.341,1.341,0,0,0,4,7.017V13.7a1.341,1.341,0,0,0,1.337,1.337H13.36A1.341,1.341,0,0,0,14.7,13.7V7.017A1.341,1.341,0,0,0,13.36,5.68Zm0,8.022H5.337V7.017H13.36Z" transform="translate(-4 -1)" fill="#687083"/>
                                    </svg>
                                </span>
                            </div>
                            <input type="password" id="reset_confirm_password" class="form-control" placeholder="Conferma Nuova Password" autocomplete="new-password">
                        </div>
                        <button type="button" id="btn_save_new_password" class="theme_btn text-center w-100 mt-2">
                            Reimposta Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! Toastr::message() !!}
    <script>
        $('#loginForm').submit(function (e) {
            $(":submit").attr("disabled", true);
        });

        $(document).ready(function () {
            function showAlert(msg, isSuccess) {
                var $alert = $('#reset_alert_msg');
                $alert.removeClass('d-none alert-success alert-danger');
                if (isSuccess) {
                    $alert.addClass('alert-success').text(msg);
                } else {
                    $alert.addClass('alert-danger').text(msg);
                }
            }

            function hideAlert() {
                $('#reset_alert_msg').addClass('d-none').text('');
            }

            $('#resetPasswordModal').on('hidden.bs.modal', function () {
                hideAlert();
                $('#reset_identity_input').val('');
                $('#reset_new_password').val('');
                $('#reset_confirm_password').val('');
                $('#reset_user_id').val('');
                $('#reset_step_1').removeClass('d-none');
                $('#reset_step_2').addClass('d-none');
            });

            $('#btn_verify_identity').click(function () {
                var identity = $('#reset_identity_input').val().trim();
                if (!identity) {
                    showAlert('Inserisci un nome utente o un indirizzo email.', false);
                    return;
                }

                hideAlert();
                var $btn = $(this);
                $btn.prop('disabled', true).text('Verifica in corso...');

                $.ajax({
                    url: "{{ route('checkUserIdentity') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        identity: identity
                    },
                    success: function (response) {
                        $btn.prop('disabled', false).text('Verifica Account');
                        if (response.success) {
                            $('#reset_user_id').val(response.user_id);
                            showAlert(response.message, true);
                            setTimeout(function () {
                                hideAlert();
                                $('#reset_step_1').addClass('d-none');
                                $('#reset_step_2').removeClass('d-none');
                            }, 1000);
                        } else {
                            showAlert(response.message, false);
                        }
                    },
                    error: function () {
                        $btn.prop('disabled', false).text('Verifica Account');
                        showAlert('Si è verificato un errore di connessione. Riprova.', false);
                    }
                });
            });

            $('#btn_save_new_password').click(function () {
                var userId = $('#reset_user_id').val();
                var password = $('#reset_new_password').val();
                var confirmPassword = $('#reset_confirm_password').val();

                if (!password) {
                    showAlert('Inserisci la nuova password.', false);
                    return;
                }
                if (password.length < 8) {
                    showAlert('La password deve contenere almeno 8 caratteri.', false);
                    return;
                }
                if (password !== confirmPassword) {
                    showAlert('Le due password inserite non coincidono.', false);
                    return;
                }

                hideAlert();
                var $btn = $(this);
                $btn.prop('disabled', true).text('Salvataggio in corso...');

                $.ajax({
                    url: "{{ route('resetPasswordDirect') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_id: userId,
                        password: password,
                        password_confirmation: confirmPassword
                    },
                    success: function (response) {
                        $btn.prop('disabled', false).text('Reimposta Password');
                        if (response.success) {
                            showAlert(response.message, true);
                            setTimeout(function () {
                                $('#resetPasswordModal').modal('hide');
                            }, 2000);
                        } else {
                            showAlert(response.message, false);
                        }
                    },
                    error: function () {
                        $btn.prop('disabled', false).text('Reimposta Password');
                        showAlert('Si è verificato un errore durante la reimpostazione. Riprova.', false);
                    }
                });
            });
        });
    </script>
@endsection
