<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder ms-2">{!! $logo !!}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    {{-- <div class="menu-inner-shadow"></div> --}}

    <ul class="menu-inner py-1 mt-2">
        <!-- Dashboard -->
        <li class="menu-item {{ menuAktif('dashboard') }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-pie-chart"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        @permission('peminjaman-read', 'pengembalian-read')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Aktivitas</span></li>

            <li class="menu-item {{ menuAktif('peminjaman') }}">
                <a href="{{ route('peminjaman.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-up-arrow-alt"></i>
                    <div data-i18n="Analytics">Peminjaman</div>
                </a>
            </li>
            <li class="menu-item {{ menuAktif('pengembalian') }}">
                <a href="{{ route('pengembalian.index')}}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-down-arrow-alt"></i>
                    <div data-i18n="Analytics">Pengembalian</div>
                </a>
            </li>
            <li class="menu-item {{ menuAktif('history') }}">
                <a href="{{ route('history.index')}}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-history"></i>
                    <div data-i18n="Analytics">History</div>
                </a>
            </li>
        @endpermission

        @permission('buku-read', 'kategori-read', 'penerbit-read')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Data Buku</span></li>

            <li class="menu-item {{ menuAktif('buku') }}">
                <a href="{{ route('buku.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-book"></i>
                    <div data-i18n="Analytics">Buku</div>
                </a>
            </li>
            <li class="menu-item {{ menuAktif('kategori') }}">
                <a href="{{ route('kategori.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-category-alt"></i>
                    <div data-i18n="Analytics">Kategori</div>
                </a>
            </li>
            <li class="menu-item {{ menuAktif('penerbit') }}">
                <a href="{{ route('penerbit.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-area"></i>
                    <div data-i18n="Analytics">Penerbit</div>
                </a>
            </li>
        @endpermission

        @permission('siswa-read', 'guru-read', 'kelas-read')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Data Anggota</span></li>

            <li class="menu-item {{ menuAktif('siswa') }}">
                <a href="{{ route('siswa.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div data-i18n="Analytics">Siswa</div>
                </a>
            </li>
            <li class="menu-item {{ menuAktif('guru') }}">
                <a href="{{ route('guru.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-male-female"></i>
                    <div data-i18n="Analytics">Guru</div>
                </a>
            </li>
            <li class="menu-item {{ menuAktif('kelas') }}">
                <a href="{{ route('kelas.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-arch"></i>
                    <div data-i18n="Analytics">Kelas</div>
                </a>
            </li>
        @endpermission

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Pengaturan</span></li>

        @permission('user-read', 'role-read', 'permission-read')
            <li class="menu-item {{ menuAktif(['user', 'role', 'permission']) }}">
                <a href="#" class="menu-link menu-toggle">
                    <i class='menu-icon tf-icons bx bx-user-circle'></i>
                    <div data-i18n="Form Layouts">Userweb</div>
                </a>
                <ul class="menu-sub">
                    @permission('user-read')
                        <li class="menu-item {{ menuAktif('user') }}">
                            <a href="{{ route('user.index') }} " class="menu-link">
                                <div data-i18n="Vertical Form">User</div>
                            </a>
                        </li>
                    @endpermission
                    @permission('role-read')
                        <li class="menu-item {{ menuAktif('role') }}">
                            <a href="{{ route('role.index') }}" class="menu-link">
                                <div data-i18n="Horizontal Form">Role</div>
                            </a>
                        </li>
                    @endpermission
                    @permission('permission-read')
                        <li class="menu-item {{ menuAktif('permission') }}">
                            <a href="{{ route('permission.index') }}" class="menu-link">
                                <div data-i18n="Horizontal Form">Permission</div>
                            </a>
                        </li>
                    @endpermission
                </ul>
            </li>
        @endpermission


        @permission('umum-read', 'banner-read')
            <li class="menu-item {{ menuAktif('banner') }}">
                <a href="{{ route('banner.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-image"></i>
                    <div data-i18n="Analytics">Banner</div>
                </a>
            </li>
            <li class="menu-item {{ menuAktif('umum') }}">
                <a href="{{ route('umum.show') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div data-i18n="Analytics">Pengaturan</div>
                </a>
            </li>
        @endpermission


    </ul>
</aside>
