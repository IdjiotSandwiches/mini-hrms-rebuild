@include('components.cdn')
@include('components.custom-swal')

@if (Session::has('status'))
    <script>
        {{ Session::get('status') }}Toast.fire({
            icon: '{{ Session::get('status') }}',
            titleText: '{{ Session::get('message') }}',
        });
    </script>
@endif

<script>
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
