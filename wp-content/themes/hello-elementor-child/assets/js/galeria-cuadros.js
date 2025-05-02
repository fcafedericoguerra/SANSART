
jQuery(document).ready(function($) {
  // === TOGGLE FILTROS EN MÓVIL ===
  const $toggleBtn  = $('#toggle-filtros');
  const $filtros    = $('.galeria-filtros');
  const $textoBtn   = $toggleBtn.find('.texto-btn');
  const $cerrarBtn  = $('.galeria-filtros .cerrar-filtros');

  if ($toggleBtn.length && $filtros.length) {
    $toggleBtn.on('click', function () {
      $filtros.addClass('abierto');
      $textoBtn.text('Ocultar filtros');
    });

    $cerrarBtn.on('click', function () {
      $filtros.removeClass('abierto');
      $textoBtn.text('Mostrar filtros');
    });
  }

  // === FILTROS DINÁMICOS ===
  $('#filtro-categoria, #filtro-tecnica, #filtro-fecha').on('change', function() {
    const categoria = $('#filtro-categoria').val();
    const tecnica   = $('#filtro-tecnica').val();
    const orden     = $('#filtro-fecha').val();
    const precioMin = $('#filtro-precio-min').val();
    const precioMax = $('#filtro-precio-max').val();

    $.ajax({
      url: ajaxurl,
      type: 'POST',
      data: {
        action: 'filtrar_productos',
        categoria,
        tecnica,
        orden,
        precio_min: precioMin,
        precio_max: precioMax
      },
      beforeSend: function() {
        $('.galeria-productos').html('<p>Cargando resultados...</p>');
      },
      success: function(res) {
        if (res.success) {
          $('.galeria-productos').html(res.data);
          $('.producto-cuadro').addClass('animar-entrada');
        } else {
          $('.galeria-productos').html('<p>No se encontraron productos.</p>');
        }
      }
    });
  });

  // === CARGAR MÁS PRODUCTOS SOLO CON BOTÓN ===
  let page = 1;
  let loading = false;

  function cargarMasProductos() {
    if (loading) return;
    loading = true;
    page++;

    $.ajax({
      url: ajaxurl,
      type: 'POST',
      data: {
        action: 'cargar_mas_productos',
        paged: page
      },
      beforeSend: function() {
        $('#btn-cargar-mas').html(
          '<span class="spinner"></span>Cargando...'
        ).prop('disabled', true);
      },
      success: function(res) {
        if (res.success) {
          $('.galeria-productos').append(res.data);
          $('.producto-cuadro').slice(-9).addClass('animar-entrada');
          $('#btn-cargar-mas').html('Cargar más').prop('disabled', false);
          loading = false;
        } else {
          $('#btn-cargar-mas').hide();
        }
      }
    });
  }

  $('#btn-cargar-mas').on('click', function(e){
    e.preventDefault();
    cargarMasProductos();
  });

  // === SLIDER DE PRECIO ===
  if (typeof noUiSlider !== 'undefined') {
    const slider = document.getElementById('slider-precio');
    if (slider) {
      noUiSlider.create(slider, {
        start: [0, 5000],
        connect: true,
        range: {
          'min': 0,
          'max': 5000
        },
        step: 100,
        format: {
          to: value => Math.round(value),
          from: value => Number(value)
        }
      });

      const inputMin = document.getElementById('filtro-precio-min');
      const inputMax = document.getElementById('filtro-precio-max');
      const displayMin = document.getElementById('precio-min');
      const displayMax = document.getElementById('precio-max');

      slider.noUiSlider.on('update', function(values) {
        inputMin.value = values[0];
        inputMax.value = values[1];
        displayMin.innerText = `$${values[0]}`;
        displayMax.innerText = `$${values[1]}`;
      });

      slider.noUiSlider.on('change', function() {
        $('#filtro-categoria').trigger('change');
      });
    }
  }

  // === ANIMACIÓN CSS ===
  $('<style>').text(`
    .animar-entrada {
      opacity: 0;
      transform: translateY(20px);
      animation: fadeUp 0.5s ease forwards;
    }
    @keyframes fadeUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .noUi-connect {
      background: #005C00;
    }
    .noUi-handle {
      border-color: #005C00;
    }
    .noUi-horizontal .noUi-handle {
      background: white;
      border: 2px solid #005C00;
      box-shadow: none;
    }
  `).appendTo('head');
});
