<?php

$page_title = 'Contacto - Click & Paw';
$active_page = 'contacto';

include 'includes/header.php';
?>

<!-- ==========================================================================
   CONTENIDO
   ========================================================================== -->

<main class="contact-wrapper">
    <div class="contact-text">
        <h1>¡Hablemos!</h1>
        <p>Nos encantará saber de ti. Si tienes dudas sobre adopciones, voluntariado o cómo ayudar, escríbenos. Normalmente respondemos dentro de 24 a 48 horas.</p>
        
        <div class="contact-info">
            <p>📧 info@clickandpaw.com</p>
            <p>📞 +507 5555-5555</p>
        </div>

        <div class="social-links">
            <a href="https://instagram.com" target="_blank" title="Instagram"><img src="assets/images/instagram.png" alt="Instagram"></a>
            <a href="https://facebook.com" target="_blank" title="Facebook"><img src="assets/images/facebook.png" alt="Facebook"></a>
        </div>

        <form action="procesar_contacto.php" method="POST">
            <div class="two-inputs">
                <input type="text" name="nombre" placeholder="Tu Nombre" required>
                <input type="text" name="apellido" placeholder="Tu Apellido" required>
            </div>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <textarea name="mensaje" placeholder="Tu mensaje..." required></textarea>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>

    <div class="contact-img">
        <img src="assets/images/contacto.jpg" alt="Cachorro adorable mirando a la cámara">
        <img src="assets/images/contacto2.jpg" alt="Cachorro adorable mirando a la cámara">
    </div>
</main>


<?php
include 'includes/footer.php';
?>