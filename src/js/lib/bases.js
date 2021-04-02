
const init = () => {
    
  const bases = document.querySelector('#bases');
    
    if (bases) {

      const chk_set_by_base = document.querySelector('#chk-set-by-base');
      chk_set_by_base.addEventListener('click', function(ev) {
        if (ev.target.checked) {
          setRatesByBase();
        }
      });

      const setBaseRate = (prefix, curr_id, val) => {
        const elem = document.querySelector(`#${prefix}_id_${curr_id}`);
        elem.value = val;
      };

      // const base_rates_buy = document.querySelectorAll('[data-base-rate-by-id]');
      const rates_buy = document.querySelectorAll('[data-rate-buy-id]');
      const rates_sale = document.querySelectorAll('[data-rate-sale-id]');
      const rates_cross = document.querySelectorAll('[data-rate-cross-id]');
      const rates_cross_buy = document.querySelectorAll('[data-rate-cross-buy-id]');
      const rates_cross_sale = document.querySelectorAll('[data-rate-cross-sale-id]');
 
      [...rates_buy].forEach(el => {
        const id = el.id.replace('rate_buy_id_', '');
        setBaseRate('base_rate_buy', id, el.value);
      });

      [...rates_sale].forEach(el => {
        const id = el.id.replace('rate_sale_id_', '');
        setBaseRate('base_rate_sale', id, el.value);
      });

      [...rates_cross_buy].forEach(el => {
        const id = el.id.replace('rate_cross_buy_id_', '');
        setBaseRate('base_rate_cross_buy', id, el.value);
      });

      [...rates_cross_sale].forEach(el => {
        const id = el.id.replace('rate_cross_sale_id_', '');
        setBaseRate('base_rate_cross_sale', id, el.value);
      });

      // [...rates_cross].forEach(el => { // const id = el.id.replace('rate_cross_id_', ''); // setBaseRate ('base_rate_cross_buy', id, el.value); // });

      const btn_incs = document.querySelectorAll('.btn-cnt'); 

      [...btn_incs].forEach(el => {
        el.addEventListener('click', function(ev) {
          ev.preventDefault();
          const el = ev.target;
          const input_rate = getInput(el, 'previousElementSibling');
          const input_steps = getInput(el, 'nextElementSibling');
          const tr = el.closest('td').closest('tr');  
          const step_size = tr.dataset.stepSize;
          let curr_id = tr.dataset.currId;
          input_steps.value = Number(input_steps.value) + step(el);
          setBgInput(input_steps);
          if (input_rate.dataset.rateBuyId) {  
            input_rate.value = (
              Number(input_rate.value) + step(el) * Number(step_size)
            ).toFixed(5);
            setBaseRate('base_rate_buy', curr_id, input_rate.value);

            if (tr.dataset.isBaseCross == 1) {
              if (chk_set_by_base.checked) {
                setRatesByBase();
              }
            }

          }  
          if (input_rate.dataset.rateSaleId) { 
            input_rate.value = (
              Number(input_rate.value) + step(el) * Number(step_size)
            ).toFixed(5); 
            setBaseRate('base_rate_sale', curr_id, input_rate.value);

            if (tr.dataset.isBaseCross == 1) {
              if (chk_set_by_base.checked) {
                setRatesByBase();
              }
            }

          } 
          if (input_rate.dataset.rateCrossBuyId || input_rate.dataset.rateCrossSaleId) {
            const input_rate_cross = document.querySelector(`#rate_cross_id_${curr_id}`);
            input_rate.value = (
              Number(input_rate_cross.value) + (Number(input_steps.value) * Number(step_size))
            ).toFixed(5);

            if (input_rate.dataset.rateCrossBuyId) {
              setBaseRate('base_rate_cross_buy', curr_id, input_rate.value); 
            }

            if (input_rate.dataset.rateCrossSaleId) {
              setBaseRate('base_rate_cross_sale', curr_id, input_rate.value); 
            }
            
            let oper_cross = tr.dataset.operCross;
            // const cross_eqv_id = tr.dataset.crossEqvId; 
            const cross_eqv_buy = tr.dataset.crossEqvBuy; 
            const cross_eqv_sale = tr.dataset.crossEqvSale;   
            const base_curr_id = oper_cross == '*' ? tr.dataset.baseCurrId : tr.dataset.relCurrId;
                        
            if (input_rate.dataset.rateCrossBuyId) {
              let base_rate_buy;
              if (oper_cross == '*') {
                base_rate_buy = (input_rate.value * Number(cross_eqv_buy)).toFixed(5);
              } else if (oper_cross == '/') {
                base_rate_buy = input_rate.value == 0 ? 0 : (Number(cross_eqv_buy) / input_rate.value).toFixed(5);
              } 
              setBaseRate('base_rate_buy', base_curr_id, base_rate_buy);
            }

            if (input_rate.dataset.rateCrossSaleId) {
              let base_rate_sale;
              if (oper_cross == '*') {
                base_rate_sale = (input_rate.value * Number(cross_eqv_buy)).toFixed(5);
              } else if (oper_cross == '/') {
                base_rate_sale = input_rate.value == 0 ? 0 : (Number(cross_eqv_sale) / input_rate.value).toFixed(5);
              } 
              setBaseRate('base_rate_sale', base_curr_id, base_rate_sale);
            }

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

      const input_steps = document.querySelectorAll('.input-step');
      [...input_steps].forEach(el => {
        el.addEventListener('blur', function(ev) {
          const el = ev.target;
          correctStep(el);
          setBgInput(el);
        });
      });

      const input_rates = document.querySelectorAll('.input-rate');
      [...input_rates].forEach(el => {
        el.addEventListener('blur', function(ev) {
          const el = ev.target;
          correctRate(el);
        });
      });

      const correctStep = (el) => {
        const str = el.value;
        const val = parseInt(str);
        el.value = isNaN(val) ? 0 : val;
      };  

      const correctRate = (el) => {
        const str = (el.value).replace(',', '.');
        const val = parseFloat(str);
        el.value = isNaN(val) ? 0 : el.value = val;
      };  

      const setRatesByBase = () => {
        const els_base_cross = document.querySelectorAll('[data-is-base-cross]');
        const el_base_cross = [...els_base_cross].filter(el => el.dataset.isBaseCross == 1);
        const main_curr_id = el_base_cross[0].dataset.currId;
        const rate_buy = document.querySelector(`#rate_buy_id_${main_curr_id}`).value;
        const rate_sale = document.querySelector(`#rate_sale_id_${main_curr_id}`).value;

        const els_cross = document.querySelectorAll('[data-oper-cross]'); 

        [...els_cross].forEach(el => {
          const oper_cross = el.dataset.operCross;
          const curr_id = el.dataset.currId;
          const base_curr_id = oper_cross == '*' ? el.dataset.baseCurrId : el.dataset.relCurrId;
          const input_rate_buy = document.querySelector(`#rate_cross_buy_id_${curr_id}`);
          const input_rate_sale = document.querySelector(`#rate_cross_sale_id_${curr_id}`);
          let base_rate_buy, base_rate_sale;
          if (oper_cross == '*') {
            base_rate_buy = (input_rate_buy.value * Number(rate_buy)).toFixed(5);
            base_rate_sale = (input_rate_sale.value * Number(rate_sale)).toFixed(5);
          } else if (oper_cross == '/') {
            base_rate_buy = input_rate_buy.value == 0 ? 0 : (Number(rate_buy) / input_rate_buy.value).toFixed(5);
            base_rate_sale = input_rate_sale.value == 0 ? 0 : (Number(rate_sale) / input_rate_sale.value).toFixed(5);
          } 
          setBaseRate('base_rate_buy', base_curr_id, base_rate_buy);
          setBaseRate('base_rate_sale', base_curr_id, base_rate_sale);
                    
        });

      }; 
    
    }

};

export { init };