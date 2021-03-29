@extends('layouts.app')
@section('title', ' - '. $student->fullName)

@section('content')
    @php  $email = $student->getEmailAddress(); @endphp
    @if($student->status == 0)
        <div class="row">
            <div class="col-lg-12">
                <h2>{{$student->fullName}}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox collapsed">
                    <div class="ibox-title">
                        <h5>連絡追加フォーム</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                        </div>
                    </div>
                    <div class="ibox-content" style="display: none;">
                        <div class="row">
                            <div class="col-lg-12">
                                <form method="POST" action="{{ route('contact.store') }}">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">お名前</label>
                                        <div class="col-lg-10">
                                            <select class="form-control" name="customer_id" required="">
                                                <option value="{{$student->id}}">{{$student->lastname_kanji}} {{$student->firstname_kanji}}</option>
                                            </select>​
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">連絡方法</label>
                                        <div class="col-lg-10">
                                            <label class="radio-inline"><input type="radio" name="type" value="denwa" checked=""> 電話</label>
                                            <label class="radio-inline"><input type="radio" name="type" value="line"> ライン</label>
                                            <label class="radio-inline"><input type="radio" name="type" value="direct"> 直接</label>
                                            <label class="radio-inline"><input type="radio" name="type" value="mail"> メール</label>
                                            <label class="radio-inline"><input type="radio" name="type" value="gmail"> gmail</label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">連絡内容</label>
                                        <div class="col-lg-10">
                                            <textarea name="message" rows="5" placeholder="連絡内容を書いてください" class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}" required="">{{old('message')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-2"></div>
                                        <div class="col-lg-10">
                                            <button type="submit" class="btn btn-success btn-block" name="add"><span class="fa fa-pencil"></span> 記録</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4"><h2>問い合わせ：</h2></div>
            <div class="col-lg-8"><h2>{{$student->toiawase_date}}</h2></div>
        </div>
        <div class="row">
            <div class="col-lg-4"><h2>問い合わせ受けた人：</h2></div>
            <div class="col-lg-8"><h2>{{$student->toiawase_getter}}</h2></div>
        </div>
        <div class="row">
            <div class="col-lg-4"><h2>問い合わせ方法：</h2></div>
            <div class="col-lg-8"><h2>{{$student->toiawase_houhou}}</h2></div>
        </div>
        <div class="row">
            <div class="col-lg-4"><h2>紹介者：</h2></div>
            <div class="col-lg-8"><h2>{{$student->toiawase_referral}}</h2></div>
        </div>
        @if($email)
            <div class="row">
                <div class="col-lg-4"><h2>Eメール：</h2></div>
                <div class="col-lg-8"><h2>{{ $email }}</h2></div>
                <div class="col-lg-4"></div>
            </div>
        @endif
        @if(!empty($student->home_phone))
            <div class="row">
                <div class="col-lg-4"><h2>固定電話：</h2></div>
                <div class="col-lg-8"><h2>{{$student->home_phone}}</h2></div>
                <div class="col-lg-4"></div>
            </div>
        @endif
        @if(!empty($student->mobile_phone))
            <div class="row">
                <div class="col-lg-4"><h2>携帯電話：</h2></div>
                <div class="col-lg-8"><h2>{{$student->mobile_phone}}</h2></div>
                <div class="col-lg-4"></div>
            </div>
        @endif
        @if(!empty($student->toiawase_memo))
            <div class="row">
                <div class="col-lg-4"><h2>問い合わせメモ：</h2></div>
                <div class="col-lg-8"><h2>{{$student->toiawase_memo}}</h2></div>
                <div class="col-lg-4"></div>
            </div>
        @endif
        @can('student-edit')
        <div class="row">
            <div class="col-lg-12">
                <a href="{{ url('/student/'.$student->id.'/edit') }}" class="btn btn-lg btn-success">情報編集</a>
            </div>
        </div>
        @endcan
        @if(!$yoteis->isEmpty())
            <div class="row mt-3">
                <div class="col-lg-12">
                    <h1>レベルチェック情報</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4"><h2>レベルチェック日付：</h2></div>
                <div class="col-lg-8"><h2>{{$yoteis[0]->date}}</h2></div>
                <div class="col-lg-4"></div>
            </div>
            <div class="row">
                <div class="col-lg-4"><h2>レベルチェック時間：</h2></div>
                <div class="col-lg-8"><h2>{{$yoteis[0]->start_time}} {{$yoteis[0]->end_time}}</h2></div>
                <div class="col-lg-4"></div>
            </div>
            <div class="row">
                <div class="col-lg-4"><h2>レベルチェック担当先生：</h2></div>
                <div class="col-lg-8"><h2>{{$yoteis[0]->name}}</h2></div>
                <div class="col-lg-4"></div>
            </div>
            @if($yoteis[0]->status == 0)
                <div class="row">
                    <div class="col-lg-12">
                        <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#levelcheckfinished">レベルチェック終了</button>
                    </div>
                </div>
            @endif
        @else
            <div class="row mt-3">
                <div class="col-lg-12">
                    <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#levelcheck">レベルチェック追加</button>
                </div>
            </div>
        @endif

        @if(!$yoyakus->isEmpty())
            @foreach($yoyakus as $yoyaku)
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <h1>体験情報</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4"><h2>体験日付：</h2></div>
                    <div class="col-lg-8"><h2>{{$yoyaku->start_time}}-{{$yoyaku->end_time}}</h2></div>
                    <div class="col-lg-4"></div>
                </div>
                <div class="row">
                    <div class="col-lg-4"><h2>体験クラス：</h2></div>
                    <div class="col-lg-8"><h2>{{$yoyaku->title}}</h2></div>
                    <div class="col-lg-4"></div>
                </div>
                <div class="row">
                    <div class="col-lg-4"><h2>体験担当先生：</h2></div>
                    <div class="col-lg-8"><h2>{{$yoyaku->nickname}}</h2></div>
                    <div class="col-lg-4"></div>
                </div>
            @endforeach
        @endif
    @else
        <div class="row">
            <div class="col-lg-12">
                @if(session()->get('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div><br/>
                @endif
                @if(session()->get('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div><br/>
                @endif
                <div class="border-bottom">
                    <div class="pull-left" style="max-width:50%">
                        <div class="align-middle d-inline-block">
                            <h2>{{ $student->fullName }}</h2>
                        </div>
                        @can('view-student-tags')
                            @php $enable_edit = \Auth::user()->hasPermissionTo('edit-student-tags') ? 'true' : 'false'; @endphp
                            <div id="vue-app" class="align-middle d-inline-block">
                                <app-student-tags
                                    :student_id="{{ $student->id }}"
                                    :student_tags="{{ json_encode($student->getTags()) }}"
                                    :enable_edit="{{ $enable_edit }}"
                                ></app-student-tags>
                            </div>
                        @endcan
                    </div>
                    <div class="pull-right">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                @can('student-add-payment')
                                @if($use_points[0]->value == 'true')
                                    <a href="{{ url('/accounting/add/'.$student->id) }}" class="dropdown-item">{{ __('messages.addpayment')}}</a>
                                @endif
                                @endcan
                                @can('student-add-montly-payment')
                                @if($use_monthly_payments[0]->value == 'true')
                                    <a href="JavaScript:Void(0);" id="add-payment-btn" class="dropdown-item">{{ __('messages.add-payment')}}</a>
                                    <div id="add-payment-app">
                                        <app-add-payment v-if="display"
                                            :plans="{{ json_encode($plans) }}"
                                            :discounts="{{ json_encode($discounts) }}"
                                            :payment_methods="{{ json_encode($payment_methods) }}"
                                            :customer_id="{{ $student->id }}"
                                            :payment_settings="{{ json_encode($paymentSettings) }}"
                                            :payment_breakdown_records="{{ json_encode($payment_breakdown_records) }}"
                                            period="{{ $period }}"
                                            :payment_categories="{{ json_encode($payment_categories) }}"
                                            :use_stripe_subscription="{{ $student->use_stripe_subscription }}"
                                            @modal-close="modalClose"
                                            @payment-added="paymentAdded"
                                        ></app-add-payment>
                                    </div>
                                @endif
                                @endcan
                                @can('student-add-contact')
                                <a class="dropdown-item" data-toggle="modal" data-target="#add_contact_modal"
                                    data-modal_title="{{ $student->fullname}}"
                                    data-student_id="{{ $student->id }}" href="javascript:void(0);"
                                    > {{ __('messages.addcontact') }}
                                </a>
                                @endcan
                                @can('student-edit')
                                    <a href="{{ url('/student/'.$student->id.'/edit') }}" class="dropdown-item">{{ __('messages.edit')}}</a>
                                    @if(!$student->isArchived())
                                        <a class="dropdown-item btn_archive_student" href="javascript:void(0);" data-student_id="{{ $student->id }}">{{ __('messages.archive') }}</a>
                                    @endif
                                @endcan
                                @can('student-delete')
                                    <form class="mb-0" method="POST" action="{{ route('student.destroy', $student->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <a class="dropdown-item delete-student" href="javascript:void(0);">{{ __('messages.delete')}}</a>
                                    </form>
                                @endcan
                                @can('student-impersonate')
                                    <a class="dropdown-item" href="{{ route('student.start_impersonate', $student->user_id) }}">{{ __('messages.impersonate') }}</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
                $nav = Request::query('nav');
                $student_todo_Count = $student->todo_alert_count($date);
            ?>
            <div class="col-lg-12 sticky_tabs_container">
                <ul class="nav nav-tabs">
                    @can('student-info')
                    <li class="nav-item"><a class="nav-link {{(!isset($nav)) || $nav == 'home' ? 'active' : ''}}" data-toggle="tab" href="#home">{{ __('messages.personalinformation')}}</a></li>
                    @endcan
                    @can('student-contact')
                    @if(!$contacts->isEmpty())
                        <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'renraku') ? 'active' : ''}}" data-toggle="tab" href="#renraku">{{ __('messages.contact')}}</a></li>
                    @endif
                    @endcan
                    @can('student-edit')
                        @can('student-payment-settings')
                        <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'payment-settings') ? 'active' : ''}}" data-toggle="tab" href="#payment-settings">{{ __('messages.payment-settings') }}</a></li>
                        @endcan
                        @can('student-course')
                        <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'course-settings') ? 'active' : ''}}" data-toggle="tab" href="#course-settings">{{ __('messages.course-settings') }}</a></li>
                        @endcan
                    @endcan
                    
                    @if($use_points[0]->value == 'true')
                        @can('student-payment')
                            <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'payment') ? 'active' : ''}}" data-toggle="tab" href="#payment">{{ __('messages.payment')}}</a></li>
                        @endcan
                    @endif
                    @if($use_monthly_payments[0]->value == 'true')
                        @if($monthly_payment_records)
                            @can('student-monthly-payment')
                            <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'monthlypayment') ? 'active' : ''}}" data-toggle="tab" href="#monthlypayment">{{ __('messages.monthlypayment')}}</a></li>
                            @endcan
                        @endif
                        @if($other_payment_records)
                            @can('student-other-payment')
                            <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'otherpayments') ? 'active' : ''}}" data-toggle="tab" href="#otherpayments">{{ __('messages.other-payments')}}</a></li>
                            @endcan
                        @endif
                    @endif
                    @if($events)
                        @can('student-event-payment')
                            <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'eventpayment') ? 'active' : ''}}" data-toggle="tab" href="#eventpayment">{{ __('messages.eventpayment')}}</a></li>
                        @endcan
                    @endif
                    @if($book_students->count() > 0)
                        @can('student-books')
                        <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'bookcheckout') ? 'active' : ''}}" data-toggle="tab" href="#bookcheckout">{{ __('messages.book-checkout-history')}}</a></li>
                        @endcan
                    @endif
                    @if($student_tests->count() > 0)
                        @can('student-test-result')
                        <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'onlinetestresult') ? 'active' : ''}}" data-toggle="tab" href="#onlinetestresult">{{ __('messages.onlinetestresults')}}</a></li>
                        @endcan
                    @endif
                    @if($paper_tests->count() > 0)
                        @can('student-paper-result')
                        <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'papertestresult') ? 'active' : ''}}" data-toggle="tab" href="#papertestresult">{{ __('messages.papertestresults')}}</a></li>
                        @endcan
                    @endif
                    @if($assessment_users->count() > 0)
                        @can('student-assessment-result')
                        <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'assessmentresult') ? 'active' : ''}}" data-toggle="tab" href="#assessmentresult">{{ __('messages.assessmentresults')}}</a></li>
                        @endcan
                    @endif
                    @if($todoAccessList->count() > 0)
                        @can('student-todo')
                        <li class="nav-item">
                            <a class="nav-link {{(isset($nav) && $nav == 'todo') ? 'active' : ''}}" data-toggle="tab" href="#todo">
                                {{ __('messages.todos')}} <span class="badge badge-danger tab_student_todo_count" style="font-size:12px;margin-left: 2px;{{ $student_todo_Count == 0 ? 'display:none;' : ''}}">{{ $student_todo_Count }}</span>
                            </a>
                        </li>
                        @endcan
                    @endif
                    @can('student-class-usage')
                    <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'classusage') ? 'active' : ''}}" data-toggle="tab" href="#classusage">{{ __('messages.class-usage')}}</a></li>
                    @endcan
                    @can('student-docs')
                        <li class="nav-item"><a class="nav-link {{(isset($nav) && $nav == 'docs') ? 'active' : ''}}" data-toggle="tab" href="#docs">{{ __('messages.docs')}}</a></li>
                    @endcan
                    
                </ul>
            </div>
            <div class="col-lg-12 tab-content-txt">
                <div class="tab-content">
                    @can('student-info')
                        <div id="home" class="tab-pane fade {{ (!isset($nav)) || $nav == 'home' ? 'active show' : ''}}">
                            <h3>{{ __('messages.personalinformation')}}</h3>
                            <div class="row">
                                <div class="table-responsive col-md-8">
                                    <table class="table table-hover">
                                        <tr>
                                            <td>{{ __('messages.role')}}:</td>
                                            <td>
                                                {{ isset($student->user->getRoleNames()[0]) ? $student->user->getRoleNames()[0] : ''}}
                                            </td>
                                        </tr>
                                        @if($student->image)
                                        <tr>
                                            <td>{{ __('messages.profile-picture')}}:</td>
                                            <td>
                                                    <img src="{{ $student->getImageUrl() }}" style="max-width:300px;" class="img-responsive">
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td>{{ __('messages.lastnameromaji')}}:</td>
                                            <td>{{$student->lastname}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('messages.firstnameromaji')}}:</td>
                                            <td>{{$student->firstname}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('messages.lastnamekanji')}}:</td>
                                            <td>{{$student->lastname_kanji}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('messages.firstnamekanji')}}:</td>
                                            <td>{{$student->firstname_kanji}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('messages.lastnamekatakana')}}:</td>
                                            <td>{{$student->lastname_furigana}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('messages.firstnamekatakana')}}:</td>
                                            <td>{{$student->firstname_furigana}}</td>
                                        </tr>
                                        @if(isset($teacher->nickname))
                                        <tr>
                                            <td>{{ __('messages.advisor')}}:</td>
                                            <td>
                                                    {{$teacher->nickname}}
                                            </td>
                                        </tr>
                                        @endif
                                        @if(!empty($student->join_date))
                                        <tr>
                                            <td>{{ __('messages.joindate')}}:</td>
                                            <td>{{$student->join_date}}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($student->birthday))
                                        <tr>
                                            <td>{{ __('messages.birthday')}}:</td>
                                            <td>{{$student->birthday}}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($student->address))
                                        <tr>
                                            <td>{{ __('messages.address')}}:</td>
                                            <td>{{$student->address}}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($student->home_phone))
                                        <tr>
                                            <td>{{ __('messages.homephone')}}:</td>
                                            <td>{{$student->home_phone}}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($student->mobile_phone))
                                        <tr>
                                            <td>{{ __('messages.cellphone')}}:</td>
                                            <td>{{$student->mobile_phone}}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($email))
                                        <tr>
                                            <td>{{ __('messages.email')}}:</td>
                                            <td>
                                                    @if($student->willUseParentEmail())
                                                        <p>{{ __('messages.parent-email') }} <em>{{ $student->getEmailAddress() }}</em> {{ __('messages.will-be-used-for-all-communications') }}</p>
                                                    @endif
                                                    <form action="{{url('student/reconfirm/'.$student->user_id)}}" method="post" class="mb-0">
                                                        <button type="button" class="btn btn-info btn" data-toggle="modal" data-target="#mail">{{$email}}</button>
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn">{{__('messages.resend')}}</button>
                                                        <button type="button" class="btn btn-primary btn" onclick="copyToClipboard('{{ $email }}', this)">{{ __('messages.copy-to-clipboard') }}</button>
                                                    </form>
                                            </td>
                                        </tr>
                                        @endif
                                        @if(!empty($student->levels))
                                        <tr>
                                            <td>{{ __('messages.levels') }}:</td>
                                            <td>{{ implode(", ",explode(",",$student->levels)) }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($student->toiawase_referral))
                                        <tr>
                                            <td>{{ __('messages.referrer')}}:</td>
                                            <td>{{ $student->toiawase_referral }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($student->toiawase_houhou))
                                        <tr>
                                            <td>{{ __('messages.firstcontacttype')}}:</td>
                                            <td>
                                                @php
                                                    switch ($student->toiawase_houhou) {
                                                        case 'Eメール':
                                                            $contact_type = __('messages.email');
                                                            break;

                                                        case '電話':
                                                            $contact_type = __('messages.telephone');
                                                            break;

                                                        case '直接':
                                                            $contact_type = __('messages.direct');
                                                            break;

                                                        case 'LINE':
                                                            $contact_type = __('messages.line');
                                                            break;

                                                    default:
                                                        $contact_type = "";
                                                        break;
                                                }
                                            @endphp
                                            {{ $contact_type }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->toiawase_getter))
                                    <tr>
                                        <td>{{ __('messages.firstcontactgetter')}}:</td>
                                        <td>{{$student->toiawase_getter}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->toiawase_date))
                                    <tr>
                                        <td>{{ __('messages.firstcontactdate')}}:</td>
                                        <td>{{ $student->toiawase_date}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->toiawase_memo))
                                    <tr>
                                        <td>{{ __('messages.memo')}}:</td>
                                        <td>{!! nl2br($student->toiawase_memo) !!}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->rfid_token))
                                    <tr>
                                        <td>{{ __('messages.rfidtoken')}}:</td>
                                        <td>{{ $student->rfid_token }}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->office_name))
                                    <tr>
                                        <td>{{ __('messages.office-name')}}:</td>
                                        <td>{{$student->office_name}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->office_address))
                                    <tr>
                                        <td>{{ __('messages.office-address')}}:</td>
                                        <td>{{$student->office_address}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->office_phone))
                                    <tr>
                                        <td>{{ __('messages.office-phone')}}:</td>
                                        <td>{{$student->office_phone}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->school_name))
                                    <tr>
                                        <td>{{ __('messages.school-name')}}:</td>
                                        <td>{{$student->school_name}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->school_address))
                                    <tr>
                                        <td>{{ __('messages.school-address')}}:</td>
                                        <td>{{$student->school_address}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->school_phone))
                                    <tr>
                                        <td>{{ __('messages.school-phone')}}:</td>
                                        <td>{{$student->school_phone}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->guardian1_name))
                                    <tr>
                                        <td>{{ __('messages.guardian1-name')}}:</td>
                                        <td>{{$student->guardian1_name}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->guardian1_address))
                                    <tr>
                                        <td>{{ __('messages.guardian1-address')}}:</td>
                                        <td>{{$student->guardian1_address}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->guardian1_phone))
                                    <tr>
                                        <td>{{ __('messages.guardian1-phone')}}:</td>
                                        <td>{{$student->guardian1_phone}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->guardian2_name))
                                    <tr>
                                        <td>{{ __('messages.guardian2-name')}}:</td>
                                        <td>{{$student->guardian2_name}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->guardian2_address))
                                    <tr>
                                        <td>{{ __('messages.guardian2-address')}}:</td>
                                        <td>{{$student->guardian2_address}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($student->guardian2_phone))
                                    <tr>
                                        <td>{{ __('messages.guardian2-phone')}}:</td>
                                        <td>{{$student->guardian2_phone}}</td>
                                    </tr>
                                    @endif
                                    @if (count($custom_fields) > 0)
			                            @foreach ($custom_fields as $custom_field) 
                                            @php 
                                                $custom_value = '';
                                                $value = $custom_field->custom_field_values->where('model_id', $student->id)->first(); 
                                                if (!empty($value)) {
                                                    $custom_value = $value->field_value;
                                                }
                                            @endphp
                                            @if(!empty($custom_value))
                                            <tr>
                                                <td>{{ \App::getLocale() == 'en' ? $custom_field->field_label_en : $custom_field->field_label_ja }}:</td>
                                                <td>
                                                    @if($custom_field->field_type == 'link')
                                                    <a href="{!! \App\Helpers\CommonHelper::addhttp($custom_value) !!}" target="_blank">
                                                            {!! $custom_value !!}
                                                    </a>
                                                    @elseif($custom_field->field_type == 'link-button')
                                                        <a href="{!! \App\Helpers\CommonHelper::addhttp($custom_value) !!}" class="btn btn-primary" target="_blank">
                                                            {!! $custom_value !!}
                                                        </a>
                                                        @elseif($custom_field->field_type == 'link-button')
                                                            <a href="{!! \App\Helpers\CommonHelper::addhttp($custom_value) !!}" class="btn btn-primary" target="_blank">
                                                                {!! $custom_value !!}
                                                            </a>
                                                        @else
                                                            {!! $custom_value !!}
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
                            
                                <div class="textarea-box col-md-4">
                                    <form method="post" action="{{ route('student.comment',['id' => $student->id]) }}" class="ajax" >
                                        <input type="hidden" value="0" id="notes_changed">
                                        <textarea class="form-control student-notes" id="text" rows="4" cols="50" name="comment" required>{!! ($student->comment) !!}</textarea>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endcan
                    @can('student-contact')
                        @if(!$contacts->isEmpty())
                            <div id="renraku" class="tab-pane fade {{(isset($nav) && $nav == 'renraku') ? 'active show' : ''}}">
                                <h3>{{ __('messages.contact')}}</h3>
                                <table class="table table-hover">
                                    <tr>
                                        <th>{{ __('messages.contacttype')}}</th>
                                        <th>{{ __('messages.date')}}</th>
                                        <th>{{ __('messages.staff')}}</th>
                                        <th>{{ __('messages.memo')}}</th>
                                    </tr>
                                        @foreach($contacts as $contact)
                                            <tr>
                                                <td>{{ $contact->type }}</td>
                                                <td>{{ $contact->getLocalDate()  }}</td>
                                                <td>{{ isset($contact->createdBy->name) ? $contact->createdBy->name : '' }}</td>
                                                <td>{{ $contact->message }}</td>
                                            </tr>
                                        @endforeach
                                </table>
                            </div>
                        @endif
                    @endcan
                    @can('student-edit')
                        @can('student-payment-settings')
                            <div id="payment-settings" class="tab-pane fade {{(isset($nav) && $nav == 'payment-settings') ? 'active show' : ''}}">
                                <div class="col-12" id="app-payment-settings">
                                    <app-payment-settings
                                        user_id="{{ $student->user->id  }}"
                                        :plans="{{ json_encode($plans) }}"
                                        :discounts="{{ json_encode($discounts) }}"
                                        :stripe_subscription_records="stripe_subscription_records"

                                        :payment_methods="{{ json_encode($payment_methods) }}"
                                        :student="{{ $student }}"
                                        :payment_settings="{{ json_encode($paymentSettings) }}"
                                        :payment_breakdown_records="{{ json_encode($payment_breakdown_records) }}"
                                        :stripe_subscription_permissions="{{ json_encode($stripe_subscription_permissions) }}"
                                    >
                                    <template v-slot:title>
                                        <h3>{{ __('messages.payment-settings') }}</h3>
                                    </template>
                                    </app-payment-settings>
                                </div>
                            </div>
                        @endcan
                        @can('student-course')
                            <div id="course-settings" class="tab-pane fade {{(isset($nav) && $nav == 'course-settings') ? 'active show' : ''}}">
                                <h3>{{ __('messages.course-settings') }}</h3>
                                <form method="POST" action="{{ route('student.course-settings.save', $student->id) }}">
                                    @csrf
                                    <input id="exist_courses" value="{{ $courses }}" type="hidden">
                                    <div class="form-group row form-section">
                                        <label class="col-lg-2 col-form-label">{{ __('messages.courses') }}:</label>
                                        <div class="col-lg-10">
                                            <input type="button" value="{{ __('messages.add') }}" class="btn btn-primary btn-sm m-1 add_courses">
                                            <div class="course_container">
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label"></label>
                                        <div class="col-lg-10">
                                            <input name="add" type="submit" value="{{ __('messages.save') }}" class="form-control btn-success">
                                        </div>
                                    </div>
                                </form>
                                <div class="hide" id="all_courses">
                                    <select name="courses[]" id="courses" class="form-control" {{ $errors->has('courses') ? ' is-invalid' : '' }}>
                                        @if($all_courses)
                                            <option value="">{{ __('messages.choose-course') }}</option>
                                            @foreach($all_courses as $course)
                                                <option value="{{ ($course->id) }}">{{ $course->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                        
                                </div>
                            </div>
                        @endcan
                    @endcan
                    @can('student-payment')
                    <div id="payment" class="tab-pane fade {{(isset($nav) && $nav == 'payment') ? 'active show' : ''}}">
                        <h3>{{ __('messages.paymenthistory')}}</h3>
                        <table class='table table-hover'>
                            @if(!$payments->isEmpty())
                                @foreach($payments as $payment)
                                    <tr>
                                        <th>{{ __('messages.paymentdate')}}</th>
                                        <th>{{ __('messages.paymentamount')}}</th>
                                        <th>{{ __('messages.numberofpoints')}}</th>
                                        <th>{{ __('messages.expirationdate')}}</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td>{{$payment->date}}</td>
                                        <td>{{$payment->price}}</td>
                                        <td>{{$payment->points}}</td>
                                        <td>{{$payment->expiration_date}}</td>
                                        <td>
                                            @can('payment-delete')
                                            <form class="delete" method="POST" action="{{ route('payment.destroy', [$payment->id, $student->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger" type="submit">{{ __('messages.delete')}}</button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                    @if(!empty($attendance_payments[$payment->id]))
                                        <tr>
                                            <td>{{ __('messages.attendancedate')}}</td>
                                            <td>{{ __('messages.numberofpoints')}}</td>
                                            <td>{{ __('messages.remainingpoints')}}</td>
                                        </tr>
                                        @foreach($attendance_payments[$payment->id] as $attendance)
                                            @if($attendance->points != 0)
                                                <tr class="<?php if($attendance->cancel_policy_id != NULL) echo 'badge-danger'; ?>">
                                                    <td>{{$attendance->date}}</td>
                                                    <td>{{$attendance->points}}</td>
                                                    <td>{{$attendance->remaining_points}}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if(!empty($expiration_points[$payment->id]) && $expiration_points[$payment->id] != 0 && $payment->expiration_date < $date)
                                        <tr>
                                            <td>{{$payment->expiration_date}} <strong class="text-danger">-{{$expiration_points[$payment->id]}} points due to expiration</strong></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            @if(!empty($attendance_payments[9999]))
                                <tr>
                                    <td><h3><strong>{{ __('messages.paymentpending')}}</strong></h3></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>{{ __('messages.attendancedate')}}</td>
                                    <td>{{ __('messages.numberofpoints')}}</td>
                                    <td>{{ __('messages.remainingpoints')}}</td>
                                </tr>
                                @foreach($attendance_payments[9999] as $attendance)
                                    @if($attendance->points != 0)
                                        <tr class="<?php if($attendance->cancel_policy_id != NULL) echo 'badge-danger'; ?>">
                                            <td>{{$attendance->date}}</td>
                                            <td>{{$attendance->points}}</td>
                                            <td>{{$attendance->remaining_points}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </table>
                    </div>
                    @endcan
                    @can('student-monthly-payment')
                        @if($monthly_payment_records)
                            <div id="monthlypayment" class="tab-pane fade {{(isset($nav) && $nav == 'monthlypayment') ? 'active show' : ''}}">
                                <h3>{{ __('messages.paymenthistory')}}</h3>
                                <div id="monthly-payments-app">
                                    <app-monthly-payments
                                        :records="monthly_payment_records"    
                                        :plans="{{ json_encode($plans) }}"
                                        :discounts="{{ json_encode($discounts) }}"                                        
                                        :payment_methods="{{ json_encode($payment_methods) }}"
                                        :payment_categories="{{ json_encode($payment_categories) }}"
                                        from_page="admin_facing_student_details_monthly"
                                    ></app-monthly-payments>
                                </div>
                            </div>
                        @endif
                    @endcan
                    @can('student-other-payment')
                        @if($other_payment_records)
                            <div id="otherpayments" class="tab-pane fade {{(isset($nav) && $nav == 'otherpayments') ? 'active show' : ''}}">
                                <h3>{{ __('messages.paymenthistory')}}</h3>
                                <div id="other-payments-app">
                                    <app-monthly-payments
                                        :records="other_payment_records"    
                                        :plans="{{ json_encode($plans) }}"
                                        :payment_methods="{{ json_encode($payment_methods) }}"
                                        :payment_categories="{{ json_encode($payment_categories) }}"
                                        from_page="admin_facing_student_details_other"
                                    ></app-monthly-payments>
                                </div>
                            </div>
                        @endif
                    @endcan
                    @can('student-event-payment')
                    @if($events)
                    <div id="eventpayment" class="tab-pane fade {{(isset($nav) && $nav == 'eventpayment') ? 'active show' : ''}}">
                        <h3>{{ __('messages.eventpayment')}}</h3>
                        <table class='table table-hover'>
                            <tr>
                                <th>{{ __('messages.eventname')}}</th>
                                <th>{{ __('messages.paymentamount')}}</th>
                                <th>{{ __('messages.date')}}</th>
                            </tr>
                                @foreach($events as $event)
                                    <tr>
                                        <td>{{ $event['event']->title }}</td>
                                        <td>{{ $event['event']->cost }}</td>
                                        <td>{{ $event['schedule']->date }}</td>
                                    </tr>
                                @endforeach
                        </table>
                    </div>
                    @endif
                    @endcan
                    @can('student-books')
                    @if($book_students->count() > 0)
                    <div id="bookcheckout" class="tab-pane fade {{(isset($nav) && $nav == 'bookcheckout') ? 'active show' : ''}}">
                        <h3>{{ __('messages.book-checkout-history')}}</h3>
                        <table class='table table-hover table-bordered table-striped'>
                            <thead>
                                <tr>
                                    @foreach(App\BookStudents::get_history_columns('student') as $column)
                                        <th>{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($book_students as $book_student)
                                    <tr>
                                        @foreach($book_student->get_history_column_values('student') as $column_value)
                                            <td>{{ $column_value }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    @endcan
                    @can('student-test-result')
                    @if($student_tests->count() > 0)
                    <div id="onlinetestresult" class="tab-pane fade {{(isset($nav) && $nav == 'onlinetestresult') ? 'active show' : ''}} table-responsive">
                        <h3>{{ __('messages.onlinetestresults')}}</h3>
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.test') }}</th>
                                    <th>{{ __('messages.class') }}</th>
                                    <th>{{ __('messages.classdate') }}</th>
                                    <th>{{ __('messages.complete') }}</th>
                                    <th>{{ __('messages.score') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($student_tests as $student_test)
                                    @php
                                        $test = $student_test->test;
                                        $schedule = $student_test->schedule;
                                    @endphp
                                    <tr>
                                        <td><a href="{{ route('test.show', $test->id) }}">{{ $test->name }}</a></td>
                                        <td><a href="{{ route('schedule.show', $schedule->id) }}">{{ $schedule->class->title }}</a></td>
                                        <td><a href="{{ route('schedule.show', $schedule->id) }}">{{ $schedule->get_date() }}</a></td>
                                        <td>{!! $student_test->is_complete() ? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-times-circle"></i>' !!}</td>
                                        <td>
                                            @if($student_test->is_complete())
                                                {{ $student_test->score.'/'.$student_test->total_score }}
                                            @endif
                                        </td>
                                        <td>
                                            <form class="delete" method="POST" action="{{ route('student-test.destroy', $student_test->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger" type="submit">{{ __('messages.delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    @endcan
                    @can('student-paper-result')
                    @if($paper_tests->count() > 0)
                    <div id="papertestresult" class="tab-pane fade {{(isset($nav) && $nav == 'papertestresult') ? 'active show' : ''}} table-responsive">
                        <h3>{{ __('messages.papertestresults')}}</h3>
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>{{ __('messages.test') }}</th>
                                    <th>{{ __('messages.class') }}</th>
                                    <th>{{ __('messages.classdate') }}</th>
                                    <th>{{ __('messages.score') }}</th>
                                    <th>{{ __('messages.testdate') }}</th>
                                    <th>{{ __('messages.commenten') }}</th>
                                    <th>{{ __('messages.commentja') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paper_tests as $paper_test)
                                    @php $schedule = $paper_test->schedule;
                                    @endphp
                                    <tr>
                                        <td>{{ $paper_test->paper_test->name }}</a></td>
                                        <td><a href="{{ route('schedule.show', $schedule->id) }}">{{ $schedule->class->title }}</a></td>
                                        <td><a href="{{ route('schedule.show', $schedule->id) }}">{{ $schedule->get_date() }}</a></td>
                                        <td>{{ $paper_test->get_score() }}</td>
                                        <td>{{ $paper_test->date }}</td>
                                        <td><pre>{!! $paper_test->comment_en !!}</pre></td>
                                        <td><pre>{!! $paper_test->comment_ja !!}</pre></td>
                                        <td>
                                            <form class="delete" method="POST" action="{{ route('student.paper_test.destroy', ['schedule_id' => $schedule->id, 'student_paper_test_id' => $paper_test->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger" type="submit">{{ __('messages.delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                    @endif
                    @endcan
                    @can('student-assessment-result')
                    @if($assessment_users->count() > 0)
                    <div id="assessmentresult" class="tab-pane fade {{(isset($nav) && $nav == 'assessmentresult') ? 'active show' : ''}} table-responsive">
                        <h3>{{ __('messages.assessment')}}</h3>
                            <table class="table table-bordered table-hover ">
                                <thead>
                                <tr>
                                    <th>{{ __('messages.assessment') }}</th>
                                    <th>{{ __('messages.complete') }}</th>
                                    <th>{{ __('messages.type') }}</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($assessment_users as $assessment_user)
                                        <tr>
                                            <td><a href="{{ route('assessment.show', $assessment_user->assessment->id) }}">{{ $assessment_user->assessment->name }}</a></td>
                                            <td>{!! $assessment_user->is_complete() ? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-times-circle"></i>' !!}</td>
                                            <td>{{ $assessment_user->assessment->type }}</td>
                                            <td>
                                                @if($assessment_user->is_complete())
                                                    <a class="btn btn-success" href="{{ route('assessment_user.show', $assessment_user->id ) }}">
                                                        {{ __('messages.seedetails') }}
                                                    </a>
                                            @endif
                                            <td>
                                                <form class="delete" method="POST" action="{{ route('assessment_user.destroy', $assessment_user->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger" type="submit">{{ __('messages.delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                    @endif
                    @endcan
                    @can('student-todo')
                    @if($todoAccessList->count() > 0)
                    <div id="todo" class="tab-pane fade {{(isset($nav) && $nav == 'todo') ? 'active show' : ''}} table-responsive">
                        @include('todo.list-todo')
                    </div>
                    @endif
                    @endcan
                    @can('student-class-usage')
                    <div id="classusage" class="tab-pane fade {{(isset($nav) && $nav == 'classusage') ? 'active show' : ''}} table-responsive">
                        @include('student.class_usage_tab')
                    </div>
                    @endcan
                    @can('student-docs')
                    <div id="docs" class="tab-pane fade {{(isset($nav) && $nav == 'docs') ? 'active show' : ''}}">
                        <h3>{{ __('messages.docs')}}</h3>
                        <div class="row">
                            <div class="col-md-6 student_docs">
                            {!! $student->the_docs_url() !!}
                            </div>
                            @can('student-docs-upload')
                            <div class="col-md-6">
                                <div class="dropzone student_files" data-student_id="{{ $student->id }}" id="student_files"></div>
                           </div>
                           @endcan

                        </div>
                    </div>
                    @endcan
                   
                   
                </div>

            </div>
        </div>
    @endif
    @include('course.unit.lesson.file-name')
@endsection

@push('modals')
    @if($student->status == 0)
        @if(!$yoteis->isEmpty())
            <!-- Modal -->
            <div id="levelcheckfinished" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">レベルチェック終了</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" method="POST" action="{{ route('yotei.update') }}">
                                @csrf
                                @if(empty($student->lastname_kanji))
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">苗字（漢字）:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="lastname_kanji" required="">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="lastname_kanji" value="{{$student->lastname_kanji}}">
                                @endif

                                @if(empty($student->firstname_kanji))
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">名前（漢字）:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="firstname_kanji" required="">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="firstname_kanji" value="{{$student->firstname_kanji}}">
                                @endif

                                @if(empty($student->lastname_furigana))
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">苗字（フリガナ）:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="lastname_furigana" required="">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="lastname_furigana" value="{{$student->lastname_furigana}}">
                                @endif

                                @if(empty($student->firstname_furigana))
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">名前（フリガナ）:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="firstname_furigana" required="">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="firstname_furigana" value="{{$student->firstname_furigana}}">
                                @endif

                                @if(empty($student->lastname))
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">苗字（ローマ字）:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="lastname" required="">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="lastname" value="{{$student->lastname}}">
                                @endif

                                @if(empty($student->firstname))
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">名前（ローマ字）:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="firstname" required="">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="firstname" value="{{$student->firstname}}">
                                @endif

                                @if(!$email)
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Eメール:</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" name="email" required="">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="email" value="{{$student->email}}">
                                @endif

                                @if(empty($student->home_phone))
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">固定電話:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="home_phone" required="">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="home_phone" value="{{$student->home_phone}}">
                                @endif

                                @if(empty($student->mobile_phone))
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">携帯電話:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="mobile_phone" required="">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="mobile_phone" value="{{$student->mobile_phone}}">
                                @endif

                                @if(empty($student->toiawase_referral))
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">紹介者:</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="toiawase_referral" required="">
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="toiawase_referral" value="{{$student->toiawase_referral}}">
                                @endif
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="hidden" name="guest" value="{{$student->id}}">
                                        <input type="hidden" name="yotei_id" value="{{$yoteis[0]->id}}">
                                        <button type="submit" class="btn btn-light">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal -->
        <div id="levelcheck" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">レベルチェック追加</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" method="POST" action="{{ route('yotei.store') }}">
                            @csrf
                            <div class="form-group">
                                <label class="control-label col-sm-2">日付:</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="date" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">開始時間:</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="start_time" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">終了時間:</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="end_time" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">担当先生:</label>
                                    <div class="col-sm-10">
                                        <select name="teacher_id" class="form-control" required="">
                                            <option value="">担当先生</option>
                                            @if(!$teachers->isEmpty())
                                                @foreach($teachers as $teacher)
                                                    <option value="{{$teacher->id}}" <?php if($teacher->id == $student->teacher_id) echo 'selected'; ?>>{{$teacher->nickname}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="hidden" name="guest" value="{{$student->id}}">
                                    <button type="submit" class="btn btn-light">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Modal -->
    <div id="mail" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4>{{ __('messages.sendmail')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('mail.send', $student->id) }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-lg-2">{{ __('messages.subject')}}</label>
                            <div class="col-lg-10">
                                <input type="subject" class="form-control" name="subject" required="">
                                <input type="hidden" value="{{ $email }}" name="email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2">{{ __('messages.message')}}</label>
                            <div class="col-lg-10">
                                <textarea name="message" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2"></label>
                            <div class="col-lg-10">
                                <input type="submit" name="submit" value="Submit" class="btn btn-info form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('messages.close')}}</button>
                </div>
            </div>
        </div>
    </div>

    @can('student-add-contact')
    <div class="modal fade" id="add_contact_modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="padding:15px 15px;">
                    <h4><span class="fa fa-pencil"></span> <span class="modal-title">{{ __('messages.addcontact') }}</span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="padding:40px 50px;">
                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">{{ __('messages.name')}}</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="customer_id" required="">
                                    <option value="{{$student->id}}">{{$student->fullName}}</option>
                                </select>​
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">{{ __('messages.contacttype')}}</label>
                            <div class="col-lg-10">
                                <label class="radio-inline"><input type="radio" name="type" value="denwa" checked=""> {{ __('messages.telephone')}}</label>
                                <label class="radio-inline"><input type="radio" name="type" value="line"> {{ __('messages.line')}}</label>
                                <label class="radio-inline"><input type="radio" name="type" value="direct"> {{ __('messages.direct')}}</label>
                                <label class="radio-inline"><input type="radio" name="type" value="mail"> {{ __('messages.email')}}</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">{{ __('messages.contents')}}</label>
                            <div class="col-lg-10">
                                <textarea name="message" rows="5" placeholder="{{ __('messages.pleasewritecontentshere') }}" class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}" required="">{{old('message')}}</textarea>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-success" name="add"><span class="fa fa-pencil"></span> {{ __('messages.record')}}</button>

                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal" aria-label="Close"> Cancel</button>
                </form>

                </div>
            </div>
        </div>
    </div>
    @endcan
    
    @can('student-edit')
    <form id="archive_student_form" method="post">
        @csrf
    </form>
    @endcan

@endpush

@push('scripts')
<script src="{{ asset(mix('js/page/filename.js')) }}"></script>
<script>
    window.uploadStudentFileUrl = "{{ route('studentdocs.upload',['student_id' => $student->id]) }}";
    window.deleteStudentFileUrl = "{{ route('studentdocs.delete',['']) }}";
    var course_detail_url = "{{ url('/student/course/') }}";
    var monthly_payment_records = <?php echo json_encode($monthly_payment_records) ?>;
    var other_payment_records = <?php echo json_encode($other_payment_records) ?>;
    var stripe_subscription_records = <?php echo json_encode($stripeSubscriptions) ?>;
</script>
<script src="{{ mix('js/page/student/details.js') }}"></script>
@endpush
