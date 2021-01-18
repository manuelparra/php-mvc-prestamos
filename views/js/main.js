$(document).ready(function(){

	/*  Show/Hidden Submenus */
	$('.nav-btn-submenu').on('click', function(e){
		e.preventDefault();
		var SubMenu=$(this).next('ul');
		var iconBtn=$(this).children('.fa-chevron-down');
		if(SubMenu.hasClass('show-nav-lateral-submenu')){
			$(this).removeClass('active');
			iconBtn.removeClass('fa-rotate-180');
			SubMenu.removeClass('show-nav-lateral-submenu');
		}else{
			$(this).addClass('active');
			iconBtn.addClass('fa-rotate-180');
			SubMenu.addClass('show-nav-lateral-submenu');
		}
	});

	/* Reset Forms */
	//const reset_button = document.getElementById('button_reset');
	$('#button_reset').on('click', function(e) {
		e.preventDefault();

		Swal.fire({
			title: '¿Deseas borrar los datos del formulario?',
			text: "Estas a punto de limpiar todos los datos del formulario.",
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sí, limpiar!',
			cancelButtonText: 'No, cancelar'
		}).then((result) => {
			if (result.value) {
				$('#new_registration_form').trigger("reset");;
			}
		});
	});
	//reset_button.addEventListener('click', (e) => {


	//});

	/*  Show/Hidden Nav Lateral */
	$('.show-nav-lateral').on('click', function(e){
		e.preventDefault();
		var NavLateral=$('.nav-lateral');
		var PageConten=$('.page-content');
		if(NavLateral.hasClass('active')){
			NavLateral.removeClass('active');
			PageConten.removeClass('active');
		}else{
			NavLateral.addClass('active');
			PageConten.addClass('active');
		}
	});
});
(function($){
    $(window).on("load",function(){
        $(".nav-lateral-content").mCustomScrollbar({
        	theme:"light-thin",
        	scrollbarPosition: "inside",
        	autoHideScrollbar: true,
        	scrollButtons: {enable: true}
        });
        $(".page-content").mCustomScrollbar({
        	theme:"dark-thin",
        	scrollbarPosition: "inside",
        	autoHideScrollbar: true,
        	scrollButtons: {enable: true}
        });
    });
})(jQuery);

$(function(){
  $('[data-toggle="popover"]').popover()
});