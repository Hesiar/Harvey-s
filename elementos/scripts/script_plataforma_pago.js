document.addEventListener("DOMContentLoaded", function() {
    const paymentForm = document.querySelector("form");
    const cardHolder = document.getElementById("card_holder");
    const cardNumber = document.getElementById("card_number");
    const expiry = document.getElementById("expiry");
    const cvv = document.getElementById("cvv");
    const guestEmail = document.getElementById("guest_email");
    const payButton = document.querySelector("button[type='submit']");

    function getCardType(number) {
        if (/^4/.test(number)) return "Visa";
        if (/^5[1-5]|^222[1-9]|^22[3-9]|^2[3-6]|^27[0-1]/.test(number)) return "Mastercard";
        if (/^3[47]/.test(number)) return "American Express";
        if (/^6(011|5|4[4-9]|22[1-9])/.test(number)) return "Discover";
        return "Desconocida";
    }

    function createErrorElement(input, message) {
        let errorElement = input.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains("error-message")) {
            errorElement = document.createElement("div");
            errorElement.classList.add("error-message");
            errorElement.style.fontWeight = "bold";
            errorElement.style.color = "red";
            input.parentNode.insertBefore(errorElement, input.nextSibling);
        }
        errorElement.textContent = message;
        errorElement.style.display = message ? "block" : "none";

        checkFormValidity();
    }

    function validateEmail() { 
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!guestEmail.value.match(emailRegex)) {
            createErrorElement(guestEmail, "Ingrese un correo electrónico válido.");
        } else {
            createErrorElement(guestEmail, "");
        }
    }

    function validateExpiryDate() {
        const [month, year] = expiry.value.split("/");
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear() % 100;
        const currentMonth = currentDate.getMonth() + 1;
        const maxYear = currentYear + 20;

        if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry.value)) {
            createErrorElement(expiry, "Formato MM/AA inválido");
        } else if (parseInt(year) < currentYear || (parseInt(year) === currentYear && parseInt(month) < currentMonth)) {
            createErrorElement(expiry, "Esta tarjeta está caducada. Use una con fecha válida.");
        } else if (parseInt(year) > maxYear) {
            createErrorElement(expiry, "La fecha ingresada es demasiado lejana. Revise la información.");
        } else {       
            createErrorElement(expiry, "");
        }
    }

    function validateCardNumber() {
        const cardType = getCardType(cardNumber.value);
        if (cardType === "American Express" && !/^\d{15}$/.test(cardNumber.value)) {
            createErrorElement(cardNumber, "AMEX usa 15 dígitos.");
        } else if (cardType !== "American Express" && !/^\d{16}$/.test(cardNumber.value)) {
            createErrorElement(cardNumber, "Esta tarjeta usa 16 dígitos.");
        } else {
            createErrorElement(cardNumber, "");
        }
    }

    function validateCVV() {
        const cardType = getCardType(cardNumber.value);
        if (cardType === "American Express" && !/^\d{4}$/.test(cvv.value)) {
            createErrorElement(cvv, "AMEX usa un CVV de 4 dígitos.");
        } else if (cardType !== "American Express" && !/^\d{3}$/.test(cvv.value)) {
            createErrorElement(cvv, "Esta tarjeta usa un CVV de 3 dígitos.");
        } else {
            createErrorElement(cvv, "");
        }
    }

    function checkFormValidity() { 
        const errors = Array.from(document.querySelectorAll(".error-message"));
        const hasErrors = errors.some(error => error.textContent.trim() !== "");
        payButton.disabled = hasErrors;
        if (hasErrors) {
            payButton.style.backgroundColor = "grey";
            payButton.style.cursor = "not-allowed";
        } else {
            payButton.style.backgroundColor = "#155724";
            payButton.style.cursor = "pointer";
        }
    }

    guestEmail?.addEventListener("input", function() { 
        validateEmail();
        checkFormValidity();
    });

    cardNumber.addEventListener("input", function() {
        validateCardNumber(); 
        validateCVV();
        checkFormValidity();
    });

    cvv.addEventListener("input", function() {
        validateCVV();
        checkFormValidity();
    });

    expiry.addEventListener("input", function() {
        validateExpiryDate();
        checkFormValidity();
    });

    document.addEventListener("DOMContentLoaded", checkFormValidity);

    paymentForm.addEventListener("submit", function(event) {
        event.preventDefault();
        console.log("Formulario enviado");

        if (guestEmail && guestEmail.style.display !== "none" && guestEmail.value.trim() === "") {
            Swal.fire({
                icon: "error",
                iconColor: "#fa0505",
                title: "Correo requerido",
                text: "Debes ingresar un correo para recibir el ticket.",
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#155724",
                allowOutsideClick: false
            });
            return;
        }

        if (payButton.disabled) {
            Swal.fire({
                icon: "error",
                iconColor: "#fa0505",
                title: "Error en el formulario",
                text: "Por favor, verifica que los campos sean correctos antes de continuar.",
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#155724",
                allowOutsideClick: false
            });
            return;
        }

        const formData = new FormData(paymentForm);

        fetch("../secciones/finalizar_compra.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            console.log("Respuesta del servidor:", text);
            try {
                return JSON.parse(text);
            } catch (error) {
                console.error("Error al procesar JSON:", error);
                return { status: "error", message: "Respuesta inesperada del servidor" };
            }
        })
        .then(data => {
            if (data.status === "ok") {
                Swal.fire({
                    icon: "success",
                    iconColor: "#155724",
                    title: "¡Muchas gracias por tu compra!",
                    allowOutsideClick: false,
                    text: data.message,
                    timer: 4000,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    fetch("verificar_sesion.php")
                    .then(response => response.json())
                    .then(sessionData => {
                        window.location.href = "/Harvey-s/layout/home.php";
                    });
                }, 4000);
            } else {
                Swal.fire({
                    icon: "error",
                    iconColor: "#fa0505",
                    title: "Error en el pago",
                    allowOutsideClick: false,
                    text: "Hubo un problema al procesar tu pago.",
                    showCancelButton: false,
                    confirmButtonText: "Reintentar",
                    confirmButtonColor: "#155724",
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            }
        });
    });
});
