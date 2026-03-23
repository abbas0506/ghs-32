@extends('layouts.basic')

@section('header')
    <x-headers.user></x-headers.user>
@endsection

@section('style')
    <style>
        .swal2-popup {
            font-size: 12px !important;
        }

        .swal2-title {
            font-size: 24px !important;
        }

        .swal2-confirm {
            font-size: 12px !important;
            /* text-sm */
        }

        .swal2-cancel {
            font-size: 12px !important;
        }
    </style>
@endsection

@section('sidebar')
    <x-sidebar></x-sidebar>
@endsection

@section('body')
    <div class="responsive-container">
        @yield('page-content')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById('menu');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');
            const responsiveContainer = document.querySelector('.responsive-container');

            // Handle menu button click
            if (menuBtn) {
                menuBtn.addEventListener('click', function() {
                    if (window.openSidebar) {
                        window.openSidebar();
                    }
                });
            }

            // Close sidebar when clicking on responsive container
            if (responsiveContainer) {
                responsiveContainer.addEventListener('click', function(event) {
                    if (window.innerWidth < 1024 && event.target === responsiveContainer) {
                        if (window.closeSidebar) {
                            window.closeSidebar();
                        }
                    }
                });
            }

            // Close sidebar when clicking on backdrop
            if (sidebarBackdrop) {
                sidebarBackdrop.addEventListener('click', function() {
                    if (window.closeSidebar) {
                        window.closeSidebar();
                    }
                });
            }
        });
    </script>
@endsection
