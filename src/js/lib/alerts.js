const hide = () => {
  const alert = document.querySelector('.alert');
  if (alert) {
    alert.classList.add('hiding');
  }
};

export {hide};