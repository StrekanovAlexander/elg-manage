
const init = () => {
    const bases = document.querySelector('#bases');
    if (bases) {
      const setRateBuy = (id, val) => {
        const elem = document.querySelector(`#base_rate_id_${id}`);
        elem.value = val;
      };

      const setRateSale = (id, val) => {
        const elem = document.querySelector(`#base_rate_sale_id_${id}`);
        elem.value = val;
      };

      const rates_buy = document.querySelectorAll('[data-rate-buy-id]');
      const rates_sale = document.querySelectorAll('[data-rate-sale-id]');

      [...rates_buy].forEach(el => {
        const id = el.id.replace('rate_buy_id_', '');
        setRateBuy(id, el.value);
      });

      [...rates_sale].forEach(el => {
        const id = el.id.replace('rate_sale_id_', '');
        setRateSale(id, el.value);
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
          if (input_rate.dataset.rateSaleId) {  
            setRateSale(curr_id, input_rate.value);
          }  
        });
      });

      const step = (el) => el.classList.contains('btn-cnt-up') ? 1 : -1;

      const getInput = (el, siblDir) => {
        const sibling = el[siblDir];
        return sibling.tagName == 'INPUT' ? sibling : sibling[siblDir];
      };

      const setBgInput = (el) => {
        if (el.value > 0) {
          rmClass(el, 'input-step-minus'); 
          el.classList.add('input-step-plus');
        } else if (el.value < 0) {
          rmClass(el, 'input-step-plus'); 
          el.classList.add('input-step-minus');
        } else {
          rmClass(el, 'input-step-minus'); 
          rmClass(el, 'input-step-plus'); 
        }
      } 
      
      const rmClass = (el, currClass) => {
        if (el.classList.contains(currClass)) {
          el.classList.remove(currClass);
        }  
      }

    }

};

export { init };