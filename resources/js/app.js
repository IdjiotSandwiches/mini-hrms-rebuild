import './bootstrap';
import jQuery from 'jquery';
import toastr from 'toastr';
import dayjs from 'dayjs';
import customParseFormat from 'dayjs/plugin/customParseFormat';
import 'toastr/build/toastr.min.css';

window.$ = jQuery;
window.toastr = toastr;
window.dayjs = dayjs.extend(customParseFormat);

toastr.options = {
    'closeButton': true,
}

(function() {
    let isDarkMode = localStorage.getItem('isDarkMode');
    if (isDarkMode === 'true') {
        document.documentElement.classList.add('dark');
    }
})();
