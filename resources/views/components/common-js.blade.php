<script type="module">
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

    function toggleDarkMode() {
        $('html').addClass('dark');
        $('#light-mode').show();
        $('#dark-mode').hide();
    }

    function toggleLightMode() {
        $('html').removeClass('dark');
        $('#light-mode').hide();
        $('#dark-mode').show();
    }

    $(document).ready(function() {
        getCurrentTime();
        let isDarkMode = localStorage.getItem('isDarkMode');

        if (isDarkMode === 'true') {
            toggleDarkMode();
        } else {
            toggleLightMode();
        }

        $('#toggle-darkmode').click(function() {
            if (isDarkMode === 'true') {
                toggleLightMode();
                localStorage.setItem('isDarkMode', 'false');
                isDarkMode = 'false';
            } else {
                toggleDarkMode();
                localStorage.setItem('isDarkMode', 'true');
                isDarkMode = 'true';
            }
        });
    });
</script>
