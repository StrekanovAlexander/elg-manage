;(function () {

    const currElems = document.querySelectorAll('[data-curr-id]');

    if (currElems) {
        const currs = [...currElems].map(el => el.dataset.currId);
        currs.forEach(el => {
            elem = document.querySelector('#rate_buy_' + el); 
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

    const password = document.querySelector('#password');
    const curr_short_name = document.querySelector('#curr_short_name');
    const dep_full_name = document.querySelector('#dep_full_name');

    if (password) {
        const btn = document.querySelector('#btn-save');
        const password2 = document.querySelector('#password2');

        const validate = (el) => /^\w{3,}$/.test(el.value);
        const compare = (el1, el2) => el1.value == el2.value;
        const isValid = (el1, el2) => !(validate(el1) && compare(el1, el2));
  
        password.addEventListener('blur', function() {
            btn.disabled = isValid(password, password2);
        });
        
        password2.addEventListener('blur', function(ev) {
            btn.disabled = isValid(password, password2);
        });
       
    }

    if (curr_short_name) {
        const btn = document.querySelector('#btn-save');
        const validate = (el) => /^[a-zA-Z]{3}$/.test(el.value);
        curr_short_name.addEventListener('blur', function() {
            btn.disabled = !validate(curr_short_name);
        });
    }

    if (dep_full_name) {
        const btn = document.querySelector('#btn-save');
        const validate = (el) => /^\w{3,}$/.test(el.value);
        dep_full_name.addEventListener('blur', function() {
            btn.disabled = !validate(dep_full_name);
        });
    }    

    const btn_steps = document.querySelectorAll('.btn-step');
    if (btn_steps) {
        [...btn_steps].forEach(el => {
            el.addEventListener('click', function(ev) {
                const el = ev.target;
                const div = el.closest('div');
                const input = div.previousElementSibling;
                const inc = el.innerHTML == '⇑' ? 1 : -1;
                input.value = Number(input.value) + inc;
                const td = input.closest('div').closest('td');
                td.className = '';
                td.classList.add(getClass(input.value));
            });
        });

        const getClass = (val) => val < 0 ? 'orangered' : val == 0 ? 'white' : 'yellowgreen'; 
    }

    const alert = document.querySelector('.alert');
    if (alert) {
        alert.classList.add('hiding');
    }
    
})();