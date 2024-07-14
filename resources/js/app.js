import './bootstrap';
import jQuery from 'jquery';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

window.$ = jQuery;
window.toastr = toastr;

toastr.options = {
    'closeButton': true,
}
