import IMask from 'imask';

const setMask = () => {
  const elems = document.querySelectorAll('[data-curr-id]');
  if (elems) {
    const currs = [...elems].map(el => el.dataset.currId);
    currs.forEach(el => {
      const elem = document.querySelector('#rate_' + el); 
      if (elem) {
        IMask(elem, {
          mask: Number,  
          scale: 5,
          signed: false,
          radix: '.'
        });
      }  
    });
  }
};

export { setMask };