<script type="module">
    const confirmSwal = Swal.mixin({
        showConfirmButton: true,
        confirmButtonColor: 'blue',
        cancelButtonColor: 'red',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        customClass: {
            title: 'font-medium',
        },
    });

    const alertSwal = Swal.mixin({
        showConfirmButton: true,
        confirmButtonColor: 'blue',
        confirmButtonText: 'OK',
        customClass: {
            title: 'font-medium',
        },
    });

    const baseToast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
        iconColor: 'white',
        color: 'white',
        customClass: {
            title: 'font-medium',
        }
    });

    const successToast = baseToast.mixin({
        background: '#22c55e',
    });

    const errorToast = baseToast.mixin({
        background: '#ef4444',
    });

    const infoToast = baseToast.mixin({
        background: '#3b82f6',
    });

    const warningToast = baseToast.mixin({
        background: '#eab308',
    });

    @if (Session::has('status'))
        {{ Session::get('status') }}Toast.fire({
            icon: '{{ Session::get('status') }}',
            titleText: '{{ Session::get('message') }}',
        });
    @endif

    let lightThemeToggle = $('#theme-toggle-dark-icon');
    let darkThemeToggle = $('#theme-toggle-light-icon');
    let toggleBtn = $('#theme-toggle');

    function getCurrentTime() {
        let now = new Date();
        let currentDay = `${now.toLocaleString('default', {weekday: 'long'})}, ${now.getDate()} ${now.toLocaleString('default', {month: 'long'})} ${now.getFullYear()}`;
        let currentHours = now.getHours().toString().padStart(2, '0');
        let currentMinutes = now.getMinutes().toString().padStart(2, '0');
        let currentSeconds = now.getSeconds().toString().padStart(2, '0');
        $('#current-day').text(currentDay);
        $('#current-hours').text(currentHours);
        $('#current-minutes').text(currentMinutes);
        $('#current-seconds').text(currentSeconds);
        setTimeout(getCurrentTime, 1000);
    }

    function toggleDarkTheme() {
        $(document.documentElement).addClass('dark');
        localStorage.setItem('color-theme', 'dark');
    }

    function toggleLightTheme() {
        $(document.documentElement).removeClass('dark');
        localStorage.setItem('color-theme', 'light');
    }

    $(document).ready(function() {
        getCurrentTime();

        if(localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            darkThemeToggle.removeClass('hidden');
        }
        else {
            lightThemeToggle.removeClass('hidden');
        }

        toggleBtn.click(function() {
            darkThemeToggle.toggleClass('hidden');
            lightThemeToggle.toggleClass('hidden');

            if(localStorage.getItem('color-theme')) {
                if(localStorage.getItem('color-theme') === 'light') {
                    toggleDarkTheme();
                }
                else {
                    toggleLightTheme();
                }
            }
            else {
                if($(document.documentElement).contains('dark')) {
                    toggleLightTheme();
                }
                else {
                    toggleDarkTheme();
                }
            }
        });
    });
</script>
