<div class="startbar d-print-none">
    <!--start brand-->
    <div class="brand">
        <a href="index.html" class="logo">
            <span>
                 <img src="{{ asset('assets/logo-small.webp') }}" alt="logo-expanded" class="logo" height="40" width="78">
            </span>
            <span class="">
                
            </span>
        </a>
    </div>
    <!--end brand-->
    <!--start startbar-menu-->
    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <!-- Navigation -->
                <ul class="navbar-nav mb-auto w-100">

                    <li class="menu-label pt-0 mt-0">
                        <span>MENU</span>
                    </li>
                    @can('inicio.index')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('inicio') }}" role="button" aria-expanded="false"
                                aria-controls="sidebarDashboards">
                                <i class="iconoir-home-simple menu-icon"></i>
                                <span>INICIO</span>
                            </a>
                        </li><!--end nav-item-->
                    @endcan


                    @can('admin.index')
                        <li class="nav-item">
                            <a class="nav-link" href="#usuarios" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="usuarios">
                                <i class="iconoir-fingerprint-lock-circle menu-icon"></i>
                                <span>ADMIN USUARIOS</span>
                            </a>
                            <div class="collapse " id="usuarios">
                                <ul class="nav flex-column">
                                    @can('admin.usuario.inicio')
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('usuarios.index') }}">Usuarios</a>
                                        </li><!--end nav-item-->
                                    @endcan

                                    @can('admin.rol.inicio')
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('roles.index') }}">Roles</a>
                                        </li><!--end nav-item-->
                                    @endcan

                                    @can('admin.permiso.inicio')
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('permisos.index') }}">Permisos</a>
                                        </li><!--end nav-item-->
                                    @endcan

                                </ul><!--end nav-->
                            </div><!--end startbarApplications-->
                        </li><!--end nav-item-->
                    @endcan

                    <li class="menu-label pt-0 mt-0">
                        <span>ACTIVIDADES</span>
                    </li>

                    @can('afiliado.index')
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('afiliado.index')}}" role="button" aria-expanded="false"
                                aria-controls="sidebarDashboards">
                                <i class="fas fa-users menu-icon"></i>
                                <span>AFILIADOS</span>
                            </a>
                        </li>
                    @endcan

                    @can('encuestas.index')
                        <li class="nav-item">
                            <a class="nav-link" href="#encuestas" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="encuestas">
                                <i class="far fa-list-alt menu-icon"></i>
                                <span>ENCUESTAS</span>
                            </a>
                            <div class="collapse " id="encuestas">
                                <ul class="nav flex-column">

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('encuestas.index')}}">Encuentas</a>
                                    </li><!--end nav-item-->

                                </ul>
                            </div>
                        </li>
                    @endcan

                    @can('formulario.index')
                        <li class="nav-item">
                            <a class="nav-link" href="#formulario" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="formulario">
                                <i class="fas fa-clipboard-list menu-icon"></i>
                                <span>FORMULARIO</span>
                            </a>
                            <div class="collapse " id="formulario">
                                <ul class="nav flex-column">

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('formulario.index')}}">Formularios</a>
                                    </li><!--end nav-item-->

                                </ul>
                            </div>
                        </li>
                    @endcan


                    @can('distrito_comunidad.index')
                        <li class="menu-label pt-0 mt-0">
                            <span>LOCALIDAD</span>
                        </li>
                    @endcan

                    @can('distrito_comunidad.index')
                        <li class="nav-item">
                            <a class="nav-link" href="#localidad" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="formulario">
                                <i class="fas fa-map-marker-alt menu-icon"></i>
                                <span>DISTRITO Y COMUNIDAD</span>
                            </a>
                            <div class="collapse " id="localidad">
                                <ul class="nav flex-column">

                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('distrito.index')}}">Distrito y Comunidad</a>
                                    </li><!--end nav-item-->

                                </ul>
                            </div>
                        </li>
                    @endcan                                                           
                </ul>
            </div>
        </div><!--end startbar-collapse-->
    </div><!--end startbar-menu-->
</div>
<div class="startbar-overlay d-print-none"></div>
