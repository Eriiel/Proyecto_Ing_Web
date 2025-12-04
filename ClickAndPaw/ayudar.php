<?php
$page_title = 'Cómo Ayudar - Click & Paw';
$active_page = 'ayudar';

include 'includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <h1>Tu Apoyo Cambia Vidas</h1>
        <p>Hay muchas maneras de contribuir a nuestra misión. Cada gesto, grande o pequeño, nos ayuda a seguir rescatando y cuidando a más animales necesitados.</p>
    </div>
</section>
    
<!-- ==========================================================================
   CONTENIDO
   ========================================================================== -->
<div class="container" style="padding-top: 4rem; padding-bottom: 4rem;">
    <div class="help-grid">

        <!-- Card 1: Donaciones Monetarias -->
        <div class="help-card">
            <div class="help-icon">💰</div>
            <h3>Haz una Donación</h3>
            <p>Tu aporte monetario es vital. Nos permite cubrir gastos veterinarios urgentes, comprar alimento especializado, pagar tratamientos y mantener nuestras instalaciones seguras y limpias para las mascotas.</p>
            <a href="#" class="btn btn-primary">Donar Ahora</a>
        </div>

        <!-- Card 2: Voluntariado -->
        <div class="help-card">
            <div class="help-icon">🙋‍♀️</div>
            <h3>Sé Voluntario</h3>
            <p>Tu tiempo es uno de los regalos más valiosos. Ayúdanos paseando perros, socializando con gatos, limpiando áreas, asistiendo en eventos de adopción o aportando tus habilidades profesionales.</p>
            <a href="contacto.php" class="btn btn-primary">Quiero ser Voluntario</a>
        </div>

        <!-- Card 3: Donación de Insumos -->
        <div class="help-card">
            <div class="help-icon">📦</div>
            <h3>Dona Insumos</h3>
            <p>Siempre necesitamos suministros. Artículos como comida para perros y gatos (seca y húmeda), arena sanitaria, mantas, toallas, juguetes y productos de limpieza son esenciales para el día a día del refugio.</p>
            <a href="contacto.php" class="btn btn-primary">Coordinar Entrega</a>
        </div>
        
        <!-- Card 4: Apadrinar una Mascota -->
        <div class="help-card">
            <div class="help-icon">💖</div>
            <h3>Apadrina una Mascota</h3>
            <p>¿No puedes adoptar ahora mismo? Al apadrinar a una mascota, cubres sus gastos mensuales de alimentación y cuidados mientras espera su hogar definitivo. Recibirás actualizaciones sobre su progreso.</p>
            <a href="galeria.php" class="btn btn-primary">Ver Mascotas para Apadrinar</a>
        </div>

    </div>
</div>

<?php
include 'includes/footer.php';
?>