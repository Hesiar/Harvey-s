$(document).ready(function () {
  $("#editar").on("click", function () {
        $(".perfil").hide();
        $(".editar-perfil").show();
  });

  $("#cancelar-edicion").on("click", function () {
        $(".editar-perfil").hide();
        $(".perfil").show();
  });

  $("#cerrar-sesion").on("click", function () {
        window.location.href = "../autenticacion/cerrar_sesion.php";
  });

  $("#formEditar").on("submit", function (e) {
        e.preventDefault();

        const nombre = $("#edit-nombre").val();
        const apellido = $("#edit-apellido").val();
        const email = $("#edit-email").val();
        const telefono = $("#edit-telefono").val();
        const direccion = $("#edit-direccion").val();
        const ciudad = $("#edit-ciudad").val();
        const provincia = $("#edit-provincia").val();
        const codigo_postal = $("#edit-codigo_postal").val();
        const contrasenia = $("#edit-contrasenia").val();
        const confirmarContrasenia = $("#confirmar-contrasenia").val();

        if (contrasenia || confirmarContrasenia) {
            if (contrasenia !== confirmarContrasenia) {
                alert("Las contraseñas no coinciden.");
                return;
            }
        }

        $.ajax({
            url: "../autenticacion/actualizar.php",
            type: "POST",
            data: {
                nombre,
                apellido,
                email,
                telefono,
                direccion,
                ciudad,
                provincia,
                codigo_postal,
                contrasenia,
            },
            success: function (response) {
                if (response.trim() === "success") {
                    alert("Datos actualizados correctamente.");
                    location.reload();
                } else {
                    alert("Error: " + response);
                }
            },
        });
  });

  $("#eliminar-cuenta").on("click", function () {
    if (
      confirm(
        "¿Seguro que quieres eliminar tu cuenta? Esta acción no se puede deshacer."
      )
    ) {
      $.ajax({
            url: "../autenticacion/eliminar.php",
            type: "POST",
            success: function (response) {
            if (response.trim() === "success") {
                alert("Cuenta eliminada correctamente.");
                window.location.href = "../layout/home.php";
            } else {
                alert("Error: " + response);
            }
            },
      });
    }
  });
});
