(function($){
  $(function(){

    const $designer = $('.fpd-container-wrapper'); // envuelve [fpd]
    const $editBtn  = $('.fpd-open-btn');          // lo crea [fpd_button]
    const $atc      = $('.single_add_to_cart_button');

    // Estado inicial
    $designer.hide();
    $editBtn.hide();
    $atc.prop('disabled', true).addClass('disabled');

    // Muestra el botón cuando se detecta una variación válida
    $('form.variations_form').on('found_variation', () => $editBtn.show());

    // Cuando FPD esté listo
    document.addEventListener('fpd_ready', () => {
      const fpd = FancyProductDesigner.getInstance();
      $editBtn.on('click', e => {
        e.preventDefault();
        $designer.slideToggle();
      });
      const toggleATC = () => {
        const ok = fpd.getCustomElements().length > 0;
        $atc.prop('disabled', !ok).toggleClass('disabled', !ok);
      };
      ['elementAdd','elementRemove','designReset'].forEach(evt =>
        fpd.addEventListener(evt, toggleATC)
      );
      toggleATC();
    });

  });
})(jQuery);