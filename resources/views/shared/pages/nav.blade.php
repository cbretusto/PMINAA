@php
    $department = $_SESSION['rapidx_department_id'];
@endphp
<aside class="app-sidebar sidebar-expand-lg sidebar-collaps bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="" class="brand-link text-center">
            {{-- <img src="{{ asset('storage/app/public/Jim/jim_lang.png') }}"
                class="brand-image elevation-3"
                style="opacity: .8; width:40px; height:40px; border-radius:50%; object-fit:cover;"> --}}
            <span class="brand-text font-weight-light font-size">
                <h5>PMI Network <br> Account Activation v2</h5>
            </span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="{{ url('../RapidX') }}" class="nav-link">
                        <i class="nav-icon fa-solid fa-right-to-bracket fa-flip-horizontal fa-lg"></i>&nbsp;
                        <span>Return to RapidX</span>
                    </a>
                </li><br>

                <li class="nav-header"><strong>PMINAA v2</strong></li>
                @if(isset($department))
                    @if($department == 1 || $department == 2)
                        <li class="nav-item">
                            <a href="{{ route('user_management') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-users-gear"></i>&nbsp;
                                <span>User Management</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('access') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-gears"></i>&nbsp;
                                <span>Setting</span>
                            </a>
                        </li>
                    @endif
                @endif

                <li class="nav-item">
                    <a href="{{ route('pminaa_request') }}" class="nav-link">
                        <i class="nav-icon fa-solid fa-book-bookmark"></i>&nbsp;
                        <span>PMI Network Account Activation v2</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>



