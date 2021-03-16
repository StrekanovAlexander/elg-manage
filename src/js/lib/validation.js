const run = () => {
  const password = document.querySelector('#password');
  const curr_short_name = document.querySelector('#curr_short_name');
  const dep_full_name = document.querySelector('#dep_full_name');

  if (password) {
    const btn = document.querySelector('#btn-save');
    const password2 = document.querySelector('#password2');
    const check = (el) => /^\w{3,}$/.test(el.value);
    const isCorrect = (first, second) => !(check(first) && equiv(first, second));
   
    password.addEventListener('blur', function() {
      btn.disabled = isCorrect(password, password2);
    });
          
    password2.addEventListener('blur', function(ev) {
      btn.disabled = isCorrect(password, password2);
    });
         
  }
    
  if (curr_short_name) {
    const btn = document.querySelector('#btn-save');
    const check = (el) => /^[a-zA-Z]{3}$/.test(el.value);
    
    curr_short_name.addEventListener('blur', function() {
      btn.disabled = !check(curr_short_name);
    });
  
  }
    
  if (dep_full_name) {
    const btn = document.querySelector('#btn-save');
    const check = (el) => /^\w{3,}$/.test(el.value);
  
    dep_full_name.addEventListener('blur', function() {
      btn.disabled = !check(dep_full_name);
    });
  
  }    

};

const equiv = (first, second) => first.value == second.value;

export { run };