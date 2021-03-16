const bgTD = () => {
  
  const btn_steps = document.querySelectorAll('.btn-step');
  
  if (btn_steps) {
    [...btn_steps].forEach(el => {
      el.addEventListener('click', function(ev) {
        const el = ev.target;
        const inc = step(el);
        const input = el.closest('div').previousElementSibling;
        input.value = Number(input.value) + inc;
        const td = input.closest('div').closest('td');
        td.className = '';
        td.classList.add(classBg(input.value));
      });
    });
    
    const step = (el) => el.innerHTML == '⇑' ? 1 : -1;
    const classBg = (val) => val < 0 ? 'orangered' : val == 0 ? 'white' : 'yellowgreen'; 
  }

}

export {bgTD};