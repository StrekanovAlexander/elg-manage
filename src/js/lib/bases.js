
const init = () => {

  const inputs = require('./inputs');
  const utils = require('./utils');

  const bases = utils.qSel('#bases');
  const retailRates = utils.qSel('#retail-rates');

  if (bases || retailRates) {
    const input_steps = utils.qSelAll('.input-step');
    [...input_steps].forEach(el => {
      el.addEventListener('blur', function(ev) {
        const el = ev.target;
        inputs.correctInputStep(el);
        inputs.setInputStepClass(el);
      });
    });
  }
    
  if (bases) {
    const chk_set_by_base = utils.qSel('#chk-set-by-base');
    chk_set_by_base.addEventListener('click', function(ev) {
      if (ev.target.checked) {
        setRatesByBase();
      }
    });

    const setBaseRate = (prefix, curr_id, val) => {
      const elem = utils.qSel(`#${prefix}_id_${curr_id}`);
      // elem.value = Number(val).toFixed(elem.dataset.precisionSize);
      elem.value = utils.fixed(val, elem.dataset.precisionSize);
    };

    const rates_buy = utils.qSelAll('[data-rate-buy-id]');
    const rates_sale = utils.qSelAll('[data-rate-sale-id]');
    const rates_cross = utils.qSelAll('[data-rate-cross-id]');
    const rates_cross_buy = utils.qSelAll('[data-rate-cross-buy-id]');
    const rates_cross_sale = utils.qSelAll('[data-rate-cross-sale-id]');

    [...rates_buy].forEach(el => {
      const id = el.id.replace('rate_buy_id_', '');
      setBaseRate('base_rate_buy', id, el.value);
    });

    [...rates_sale].forEach(el => {
      const id = el.id.replace('rate_sale_id_', '');
      setBaseRate('base_rate_sale', id, el.value);
    });

    [...rates_cross].forEach(el => {
      const id = el.id.replace('rate_cross_id_', '');
      el.addEventListener('change', function(ev) {
        const rate_cross = ev.target;
        const rate_cross_buy = utils.qSel(`#rate_cross_buy_id_${id}`);
        const steps_cross_buy = utils.qSel(`#steps_cross_buy_id_${id}`);
        const rate_cross_sale = utils.qSel(`#rate_cross_sale_id_${id}`);
        const steps_cross_sale = utils.querySel(`#steps_cross_sale_id_${id}`);
        const tr = ev.target.closest('td').closest('tr');
        const step_size = tr.dataset.stepSize;
        const precision_size = tr.dataset.precisionSize;
        
        rate_cross_buy.value = Number(rate_cross.value) + (steps_cross_buy.value * step_size);
        rate_cross_sale.value = Number(rate_cross.value) + (steps_cross_sale.value * step_size);
        
        setBaseRate('base_rate_cross_buy', id, rate_cross_buy.value);
        setBaseRate('base_rate_cross_sale', id, rate_cross_sale.value);
        
        const base_cross_eqv = getBaseCrossEqv();

        const oper_cross = tr.dataset.operCross;
        const base_curr_id = oper_cross == '*' ? tr.dataset.baseCurrId : tr.dataset.relCurrId;
        let base_rate_buy, base_rate_sale;
        if (oper_cross == '*') {
          base_rate_buy = rate_cross_buy.value * Number(base_cross_eqv.rate_buy);
          base_rate_sale = rate_cross_sale.value * Number(base_cross_eqv.rate_sale);

        } else if (oper_cross == '/') {

          base_rate_buy = rate_cross_buy.value == 0 ? 0 : Number(base_cross_eqv.rate_buy) / rate_cross_buy.value;
          base_rate_sale = rate_cross_sale.value == 0 ? 0 : Number(base_cross_eqv.rate_sale) / rate_cross_sale.value;

        } 
        setBaseRate('base_rate_buy', base_curr_id, base_rate_buy);
        setBaseRate('base_rate_sale', base_curr_id, base_rate_sale);

      });

    });

    [...rates_cross_buy].forEach(el => {
      const id = el.id.replace('rate_cross_buy_id_', '');
      setBaseRate('base_rate_cross_buy', id, el.value);
    });

    [...rates_cross_sale].forEach(el => {
      const id = el.id.replace('rate_cross_sale_id_', '');
      setBaseRate('base_rate_cross_sale', id, el.value);
    });

    const btn_incs = utils.qSelAll('.btn-cnt'); 

    [...btn_incs].forEach(el => {
      el.addEventListener('click', function(ev) {
        ev.preventDefault();
        const el = ev.target;
        const input_rate = inputs.getInputBySibling(el, 'previousElementSibling');
        const input_steps = inputs.getInputBySibling(el, 'nextElementSibling');
        const tr = el.closest('td').closest('tr');  
        const step_size = tr.dataset.stepSize;

        const precision_size = tr.dataset.precisionSize;
        let curr_id = tr.dataset.currId;
        input_steps.value = Number(input_steps.value) + step(el);
        inputs.setInputStepClass(input_steps);
        if (input_rate.dataset.rateBuyId) {  

          input_rate.value = (
            Number(input_rate.value) + step(el) * Number(step_size)
          ).toFixed(precision_size); 
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
          ).toFixed(precision_size); 
          setBaseRate('base_rate_sale', curr_id, input_rate.value);

          if (tr.dataset.isBaseCross == 1) {
            if (chk_set_by_base.checked) {
              setRatesByBase();
            }
          }

        } 

        if (input_rate.dataset.rateCrossBuyId || input_rate.dataset.rateCrossSaleId) {
          const input_rate_cross = utils.qSel(`#rate_cross_id_${curr_id}`);

          input_rate.value = (
            Number(input_rate_cross.value) + (Number(input_steps.value) * Number(step_size))
          ).toFixed(precision_size); // 3 cross precision size

          if (input_rate.dataset.rateCrossBuyId) {
            setBaseRate('base_rate_cross_buy', curr_id, input_rate.value); 
          }

          if (input_rate.dataset.rateCrossSaleId) {
            setBaseRate('base_rate_cross_sale', curr_id, input_rate.value); 
          }
            
          let oper_cross = tr.dataset.operCross;

          const base_cross_eqv = getBaseCrossEqv();

          const base_curr_id = oper_cross == '*' ? tr.dataset.baseCurrId : tr.dataset.relCurrId;
                      
          if (input_rate.dataset.rateCrossBuyId) {
            let base_rate_buy;
            if (oper_cross == '*') {
              base_rate_buy = (input_rate.value * Number(base_cross_eqv.rate_buy)).toFixed(5);
            } else if (oper_cross == '/') {
              base_rate_buy = input_rate.value == 0 ? 0 : (Number(base_cross_eqv.rate_buy) / input_rate.value).toFixed(5);
            } 
            setBaseRate('base_rate_buy', base_curr_id, base_rate_buy);
          }

          if (input_rate.dataset.rateCrossSaleId) {
            let base_rate_sale;
            if (oper_cross == '*') {
              base_rate_sale = (input_rate.value * Number(base_cross_eqv.rate_buy)).toFixed(5);
            } else if (oper_cross == '/') {
              base_rate_sale = input_rate.value == 0 ? 0 : (Number(base_cross_eqv.rate_sale) / input_rate.value).toFixed(5);
            } 
            setBaseRate('base_rate_sale', base_curr_id, base_rate_sale);
          }

        } 

      });

    });

    const step = (el) => el.classList.contains('btn-cnt-up') ? 1 : -1;

    const input_rates = utils.qSelAll('.input-rate');
    [...input_rates].forEach(el => {
      el.addEventListener('blur', function(ev) {
        const el = ev.target;
        inputs.correctInputRate(el);
      });
    });


    const setRatesByBase = () => {
      const els_base_cross = utils.qSelAll('[data-is-base-cross]');
      const el_base_cross = [...els_base_cross].filter(el => el.dataset.isBaseCross == 1);
      const main_curr_id = el_base_cross[0].dataset.currId;
      const rate_buy = utils.qSel(`#rate_buy_id_${main_curr_id}`).value;
      const rate_sale = utils.qSel(`#rate_sale_id_${main_curr_id}`).value;

      const els_cross = utils.qSelAll('[data-oper-cross]'); 

      [...els_cross].forEach(el => {
        const oper_cross = el.dataset.operCross;
        const precision_size = el.dataset.precisionSize; // 4 precision size
        const curr_id = el.dataset.currId;
        const base_curr_id = oper_cross == '*' ? el.dataset.baseCurrId : el.dataset.relCurrId;
        const input_rate_buy = utils.qSel(`#rate_cross_buy_id_${curr_id}`);
        const input_rate_sale = utils.qSel(`#rate_cross_sale_id_${curr_id}`);
        let base_rate_buy, base_rate_sale;
        if (oper_cross == '*') {
          base_rate_buy = (input_rate_buy.value * Number(rate_buy)).toFixed(precision_size); 
          base_rate_sale = (input_rate_sale.value * Number(rate_sale)).toFixed(precision_size); 
        } else if (oper_cross == '/') {
          base_rate_buy = input_rate_buy.value == 0 ? 0 : (Number(rate_buy) / input_rate_buy.value).toFixed(precision_size); 
          base_rate_sale = input_rate_sale.value == 0 ? 0 : (Number(rate_sale) / input_rate_sale.value).toFixed(precision_size);
        } 
        setBaseRate('base_rate_buy', base_curr_id, base_rate_buy);
        setBaseRate('base_rate_sale', base_curr_id, base_rate_sale);
                  
      });

    }; 

    const getBaseCrossEqv = () => {
      const els_base_cross = utils.qSelAll('[data-is-base-cross]');
      const el_base_cross = [...els_base_cross].filter(el => el.dataset.isBaseCross == 1);
      const main_curr_id = el_base_cross[0].dataset.currId;
      return {
        rate_buy: utils.qSel(`#rate_buy_id_${main_curr_id}`).value,
        rate_sale: utils.qSel(`#rate_sale_id_${main_curr_id}`).value
      }
    };
    
  }

};

export { init };