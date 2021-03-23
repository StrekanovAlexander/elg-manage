// import IMask from 'imask';

const alert = require('./lib/alerts');
const bases = require('./lib/bases');
const buttons = require('./lib/buttons');
const masks = require('./lib/masks');
const validation = require('./lib/validation');

alert.hide();
bases.init();
buttons.bgTD();
masks.setMask();
validation.run();