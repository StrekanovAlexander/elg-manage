const alert = require('./lib/alerts');
const bases = require('./lib/bases');
const validation = require('./lib/validation');

alert.hide();
bases.init();
validation.run();