import './bootstrap';
import 'flyonui/flyonui.js'
import Raty from 'raty-js'
import { Observer } from 'tailwindcss-intersect';
 
Observer.start();

// si quieres usarlo global
window.Raty = Raty;

import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";

window.flatpickr = flatpickr;