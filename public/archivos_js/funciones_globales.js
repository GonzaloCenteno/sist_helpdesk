function mostraralertasconfoco(texto, foco)
{
    swal({
        title: '<i>MENSAJE DEL SISTEMA</i>',
        type: 'error',
        html:
          '<b>'+texto+'</b>',
        confirmButtonText:
          '<i class="fa fa-thumbs-up"></i> ACEPTAR!',
        allowOutsideClick: false,
        allowEscapeKey:false,
        allowEnterKey:false
        }).then((result) => {
          $(foco).focus();
    });  
}

function MensajeConfirmacion(texto){
  let timerInterval
    swal({
      type: 'success',
      title: texto,
      timer: 700,
      allowOutsideClick: false,
      allowEscapeKey:false,
      showConfirmButton: false,
      onOpen: () => {
        timerInterval = setInterval(() => {
        }, 100)
      },
      onClose: () => {
        clearInterval(timerInterval)
      }
    }).then(
        function () {},
        function (dismiss) {
          if (dismiss === 'timer') {
            console.log('I was closed by the timer')
          }
        }
      )
}

function alertaArchivo(texto)
{
    let timerInterval
    swal({
      type: 'warning',
      title: texto,
      timer: 1200,
      allowOutsideClick: false,
      allowEscapeKey:false,
      allowEnterKey:false,
      showConfirmButton: false,
      onOpen: () => {
        timerInterval = setInterval(() => {
        }, 100)
      },
      onClose: () => {
        clearInterval(timerInterval)
      }
    }).then(
        function () {},
        function (dismiss) {
          if (dismiss === 'timer') {
            console.log('I was closed by the timer')
          }
        }
      )
}

function MensajeAdvertencia(texto){
    swal({
      type: 'info',
      confirmButtonText: 'ACEPTAR',
      title: texto,
      allowOutsideClick: false,
      allowEscapeKey:false
    }).then(
        function () {},
        function (dismiss) {
          if (dismiss === 'timer') {
            console.log('I was closed by the timer')
          }
        }
      )
}

function MensajeEspera(texto){
    swal({
        title: texto,
        allowOutsideClick: false,
        allowEscapeKey:false,
        allowEnterKey:false,
        showConfirmButton: false,
        onOpen: function () {
          swal.showLoading()
        }
      }).then(
        function () {},
        // handling the promise rejection
        function (dismiss) {
          if (dismiss === 'timer') {
            console.log('I was closed by the timer')
          }
        }
      )
}

function ValidacionServidor (msg) {
    $(".print-error-msg").find("ul").html('');
    $(".print-error-msg").css('display','block');
    $.each( msg, function( key, value ) {
        $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
    });
    swal.close();
}

function soloNumeroTab(evt) {// con guin y slash ( - / )

    var charCode = (evt.which) ? evt.which : evt.keyCode
    if ((charCode > 44 && charCode < 58) || (charCode > 36 && charCode < 41) || charCode == 9 || charCode == 8 || charCode == 110) {
        if (charCode == 78 || charCode == 40 || charCode == 37 || charCode == 110) {
            return false;
        } else {
            return true;
        }

    } else {
        return false;
    }
}

function sin_permiso()
{
    swal({
      type: 'info',
      confirmButtonText: 'ACEPTAR',
      title: 'NO TIENES PERMISO PARA REALIZAR ESTA ACCION',
      allowOutsideClick: false,
      allowEscapeKey:false
    }).then(
        function () {},
        function (dismiss) {
          if (dismiss === 'timer') {
            console.log('I was closed by the timer')
          }
        }
      )
}