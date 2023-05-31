/*Funcion para validar e insertar los campos en la db*/
function val_form_nps_tec(){
    var sede = $('#select_sedes option:selected').val();
    var tecnico = document.getElementById('combo_tecnicos').value;
    var fecha = document.getElementById('fecha').value;
    var vin = document.getElementById('vin').value;
    var calificacion = document.getElementById('calificacion').value;
    var tipificacion = document.getElementById('combo_tipificacion').value;
    var tipo_cal = document.getElementById('combo_calificacion').value;
    console.log(tipo_cal);
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
    if (sede == "") {
        //alert("sede vacia");
         Toast.fire({
        type: 'warning',
        title: 'No puedes dejar campos vacios'
      });
    }
    else if (tecnico == "") {   
        Toast.fire({
        type: 'warning',
        title: 'No puedes dejar campos vacios'
      });
    }
    else if (fecha == "") {
        Toast.fire({
        type: 'warning',
        title: 'No puedes dejar campos vacios'
      });
    }
    else if (vin = "") {
        Toast.fire({
        type: 'warning',
        title: 'No puedes dejar campos vacios'
      });
    }
    else if (calificacion == "") {
        Toast.fire({
        type: 'warning',
        title: 'No puedes dejar campos vacios'
      });
    }
    else if (tipificacion == "") {
        Toast.fire({
        type: 'warning',
        title: 'No puedes dejar campos vacios'
      });
    }
    else if (tipo_cal == "") {
                Toast.fire({
                type: 'warning',
                title: 'No puedes dejar campos vacios'
              });
            }
    else{
            var result = document.getElementById("notifi");
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    result.innerHTML = xmlhttp.responseText;
                }
            }
            var datos = "sede="+sede+"&tecnico="+tecnico+"&fecha="+fecha+"&vin="+vin+"&calificacion="+calificacion+"&tipificacion="+tipificacion+"&tipo_cal"+tipo_cal
            xmlhttp.open("GET", "<?=base_url()?>encuesta/insert_nps_tecnicos?"+datos, true);
            xmlhttp.send();
            console.log("vien");
    }
    

}