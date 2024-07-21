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

    $(document).ready(function() {
        getCurrentTime();
    });
</script>
