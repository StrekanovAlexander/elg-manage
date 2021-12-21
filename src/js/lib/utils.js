const removeClass = (el, currClass) => {
  if (el.classList.contains(currClass)) {
    el.classList.remove(currClass);
  }  
}

const qSel = (el) => document.querySelector(el);
const qSelAll = (el) => document.querySelectorAll(el);

const fixed = (val, size) => Number(val).toFixed(size);

export { removeClass, qSel, qSelAll, fixed }