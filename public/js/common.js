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

    if (password) {
        const btn = document.querySelector('#btn-save-pwd');
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

        // function validate (el) {
        //     return /^\w{3,}$/.test(el.value);
        // };
    
        // function compare (el1, el2) {
        //     return el1.value == el2.value;
        // };
    
        // function isValid(el1, el2) {
        //     return !(validate(el1) && compare(el1, el2));
        // }
    }
    
})();