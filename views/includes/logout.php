 <script>
    let btn_exit_system = document.querySelector(".btn-exit-system");

    btn_exit_system.addEventListener('click', function(e) {
        e.preventDefault();
		Swal.fire({
  			title: '¿Deseas cerrar esta sesión?',
			text: "Estas a punto de cerrar la sesión y salir del sistema.",
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sí, salir!',
			cancelButtonText: 'No, cancelar'
		}).then((result) => {
			if (result.value) {
                let url = '<?php echo SERVER_URL . 'endpoint/login-ajax/'; ?>';
                let token = '<?php echo $insLoginController->encrypt_data($_SESSION['token_spm']); ?>';
                let usuario = '<?php echo $insLoginController->encrypt_data($_SESSION['usuario_spm']); ?>';

                let data = new FormData();
                data.append("token", token);
                data.append("usuario", usuario);

                fetch(url, {
                    method: 'POST',
                    body: data
                })
                .then(res => {
				    return res.json()
                })
                .then(res => {
                    return ajax_alerts(res);
                });
            }
        });
    });
 </script>