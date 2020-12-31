/*--- This script contains the codo do ajax requests ---*/

const ajax_forms = document.querySelectorAll('.ajax-form');

function send_ajaxs_form(e) {
  e.preventDefault(); // avoid default behavior

  let data = new FormData(this);
  let method = this.getAttribute('method');
	let action = this.getAttribute('action');
  let type = this.getAttribute('data-form');

  let headers = new Headers();

  let config = {
    method: method,
    headers: headers,
    mode: 'cors',
    cache: 'no-cache',
    body: data
  };

  let alert_text;

  if (type === "save") {
    alert_text = "Los datos quedaran guardados en el sistema";
  } else if (type === "delete") {
    alert_text = "Los datos serán eliminados completamente del sistema";
  } else if (type === "update") {
    alert_text = "Los datos del sistema serán actualizados";
  } else if (type === "search") {
    alert_text = "Se eliminara el termino de búsqueda y tendrá que escribir uno nuevo";
  } else if (type === "loans") {
    alert_text = "¿Desea remover los datos seleccionados para prestamos o reservaciones?";
  } else {
    alert_text = "¿Quieres realizar la operación solicitada?";
  }

  Swal.fire({
    icon: 'question',
    title: 'Confirmación',
    text: alert_text,
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Aceptar',
    cancelButtonText: 'Cancelar'
  }).then((res) => {
    if (res.isConfirmed) {
			// Utilizamos ajax para enviar el formulario con el metodo POST al backend
      fetch(action, config)
      .then(res => {
				return res.json()
			})
      .then(res => {
        return ajax_alerts(res);
      });
    }
  });
}

ajax_forms.forEach((forms) => {
  forms.addEventListener('submit', send_ajaxs_form);
});

function ajax_alerts(alert) {
  if (alert.alert === 'simple') {
    Swal.fire({
      icon: alert.type,
      title: alert.title,
      text: alert.text,
      confirmButtonText: 'Aceptar'
    });
  } else if (alert.alert === 'reload') {
    Swal.fire({
      icon: alert.type,
      title: alert.title,
      text: alert.text,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Aceptar'
    }).then((res) => {
      if (res.isConfirmed) {
        location.reload();
      }
    });
  } else if (alert.alert === 'clean') {
    Swal.fire({
      icon: alert.type,
      title: alert.title,
      text: alert.text,
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Aceptar'
    }).then((res) => {
      if (res.isConfirmed) {
        document.querySelector('.ajax-form').reset();
      }
    });
  } else if (alert.alert === 'redirect') {
    window.location.href = alert.url;
  }
}