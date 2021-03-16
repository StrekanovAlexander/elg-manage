const alert = require('./lib/alerts');
const buttons = require('./lib/buttons');
const masks = require('./lib/masks');
const validation = require('./lib/validation');

alert.hide();
buttons.bgTD();
masks.setMask();
validation.run();