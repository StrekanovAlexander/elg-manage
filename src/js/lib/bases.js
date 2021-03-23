
const init = () => {
    const bases = document.querySelector('#bases');
    if (bases) {
      const setRateBuy = (id, val) => {
        const elem = document.querySelector(`#base_rate_id_${id}`);
        elem.value = val;
      };

      const rates_buy = document.querySelectorAll('[data-rate-buy-id]');

      [...rates_buy].forEach(el => {
        const id = el.id.replace('rate_buy_id_', '');
        setRateBuy(id, el.value);
      });

      const btn_incs = document.querySelectorAll('.btn-cnt'); 

      [...btn_incs].forEach(el => {
        el.addEventListener('click', function(ev) {
          ev.preventDefault();
          const el = ev.target;
          const input_rate = getInput(el, 'previousElementSibling');
          const input_steps = getInput(el, 'nextElementSibling');
          const tr = el.closest('td').closest('tr');  
          const step_size = tr.dataset.stepSize;
          const curr_id = tr.dataset.currId;
          input_steps.value = Number(input_steps.value) + step(el);
          setBgInput(input_steps);
          input_rate.value = (
              Number(input_rate.value) + step(el) * Number(step_size)
            ).toFixed(5);
          if (input_rate.dataset.rateBuyId) {  
            setRateBuy(curr_id, input_rate.value);
          }  
        });
      });

      const step = (el) => el.innerHTML == '⇑' ? 1 : -1;

      const getInput = (el, siblDir) => {
        const sibling = el[siblDir];
        return sibling.tagName == 'INPUT' ? sibling : sibling[siblDir];
      };

      const setBgInput = (el) => {
        const color = el.value < 0 ? 'red' : el.value == 0 ? 'lightgray' : 'yellowgreen'; 
        el.style.cssText = `border: 1px solid ${color}; text-align: center`;
      }  

    }
    
};

export { init };