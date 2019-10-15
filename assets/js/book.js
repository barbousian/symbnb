/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/book.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');
global.$ = global.jQuery = $;
// après avoir fait npm install --save-dev popper.js, on ne require plus le popper.min.js de notre repertoire assets, mais le popper.js qui a été installé dans notre node module
// après avoir fait npm install --save-dev bootstrap, on ne require plus que bootstrap

var cal = require('./bootstrap-datepicker.min.js');


