const run = () => {
  const password = document.querySelector('#password');
  const curr_short_name = document.querySelector('#curr_short_name');
  const curr_base_curr_id = document.querySelector('#curr_base_curr_id');
  const dep_full_name = document.querySelector('#dep_full_name');
  const place_full_name = document.querySelector('#place_full_name');

  if (password) {
    const btn = document.querySelector('#btn-save');
    const password2 = document.querySelector('#password2');
    const check = (el) => /^\w{3,}$/.test(el.value);
    const isCorrect = (first, second) => !(check(first) && equiv(first.value, second.value));
   
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

  if (curr_base_curr_id) {
    const btn = document.querySelector('#btn-save');
    const rel_curr_id = document.querySelector('#rel_curr_id'); 
    const elems = [curr_base_curr_id, rel_curr_id];
    elems.forEach(elem => {
      elem.addEventListener('change', function() {
        // const rel_curr_short_name = rel_curr_id.options[rel_curr_id.selectedIndex].text; 
        btn.disabled = equiv(curr_base_curr_id.value, rel_curr_id.value);
      });
    }); 
  }
    
  if (dep_full_name) {
    const btn = document.querySelector('#btn-save');
 
    dep_full_name.addEventListener('blur', function() {
      btn.disabled = !checkFullName(dep_full_name);
    });
  
  }    

  if (place_full_name) {
    const btn = document.querySelector('#btn-save');

    place_full_name.addEventListener('blur', function() {
      btn.disabled = !checkFullName(place_full_name);
    });
  
  }    

};

const equiv = (first, second) => first === second;
const checkFullName = (el) => /^[\s0-9a-zA-Zа-яёА-ЯЁ]{3,}$/.test(el.value);

export { run };