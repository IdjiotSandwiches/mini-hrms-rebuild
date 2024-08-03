import './bootstrap';
import jQuery from 'jquery';
import toastr from 'toastr';
import dayjs from 'dayjs';
import customParseFormat from 'dayjs/plugin/customParseFormat';
import 'toastr/build/toastr.min.css';
import Swal from 'sweetalert2';

window.$ = jQuery;
window.toastr = toastr;
window.dayjs = dayjs.extend(customParseFormat);
window.Swal = Swal;

toastr.options = {
    'closeButton': true,
}
