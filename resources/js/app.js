import './bootstrap';
import jQuery from 'jquery';
import dayjs from 'dayjs';
import customParseFormat from 'dayjs/plugin/customParseFormat';
import Swal from 'sweetalert2';
import 'flowbite';

window.$ = jQuery;
window.dayjs = dayjs.extend(customParseFormat);
window.Swal = Swal;

toastr.options = {
    'closeButton': true,
}
