const utils = require('./utils');

const setInputStepClass = (el) => {
  
  const minusClass = 'input-step-minus';
  const plusClass = 'input-step-plus'; 

  const remove = utils.removeClass;

  if (el.value > 0) {
    remove(el, minusClass); 
    el.classList.add(plusClass);
  } else if (el.value < 0) {
    remove(el, plusClass); 
    el.classList.add(minusClass);
  } else {
    remove(el, minusClass); 
    remove(el, plusClass); 
  }

} 

const correctInputStep = (el) => {
  const val = parseInt(el.value);
  el.value = isNaN(val) ? 0 : val;
}; 

const correctInputRate = (el) => {
  const str = (el.value).replace(',', '.');
  const val = parseFloat(str);
  el.value = isNaN(val) ? 0 : el.value = val;
};  

const getInputBySibling = (el, sibl) => {
  const sibling = el[sibl];
  return sibling.tagName == 'INPUT' ? sibling : sibling[sibl];
};

export { 
  setInputStepClass, 
  correctInputStep, 
  correctInputRate,
  getInputBySibling 
};