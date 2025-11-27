<?php
require '../includes/db_connection.php';
proteger_pagina();

$mascota = [
    'id' => '', 'nombre' => '', 'especie' => '', 'raza' => '', 'edad' => '', 'genero' => '', 
    'tamano' => '', 'descripcion' => '', 'foto' => '', 'estado_salud' => '', 'estado' => 'Disponible'
];
$page_title = 'Añadir Nueva Mascota';
$is_edit = false;

// Modo Edición
if (isset($_GET['id'])) {
    $is_edit = true;
    $page_title = 'Editar Mascota';
    $stmt = $pdo->prepare("SELECT * FROM mascotas WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $mascota = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$mascota) die('Mascota no encontrada.');
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ruta_imagen_db = $_POST['foto_actual'] ?? null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $upload_dir = '../uploads/';
        $nombre_archivo = uniqid() . '-' . basename($_FILES["foto"]["name"]);
        $target_file = $upload_dir . $nombre_archivo;
        
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $ruta_imagen_db = $nombre_archivo;
        } else {
            die("Hubo un error al subir la imagen.");
        }
    }

    try {
        if (!empty($_POST['id'])) { // UPDATE
            $stmt = $pdo->prepare("UPDATE mascotas SET nombre=?, especie=?, raza=?, edad=?, genero=?, tamano=?, descripcion=?, estado_salud=?, estado=?, foto=? WHERE id=?");
            $stmt->execute([$_POST['nombre'], $_POST['especie'], $_POST['raza'], $_POST['edad'], $_POST['genero'], $_POST['tamano'], $_POST['descripcion'], $_POST['estado_salud'], $_POST['estado'], $ruta_imagen_db, $_POST['id']]);
        } else { // CREATE
            $stmt = $pdo->prepare("INSERT INTO mascotas (nombre, especie, raza, edad, genero, tamano, descripcion, estado_salud, estado, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['nombre'], $_POST['especie'], $_POST['raza'], $_POST['edad'], $_POST['genero'], $_POST['tamano'], $_POST['descripcion'], $_POST['estado_salud'], $_POST['estado'], $ruta_imagen_db]);
        }
        header("Location: gestion_mascotas.php");
        exit();
    } catch (PDOException $e) {
        die("Error al guardar la mascota: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <a href="index.php" class="sidebar-logo"><span>🐾</span> Click & Paw</a>
            <nav>
                <ul>
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="gestion_mascotas.php" class="active">Gestión de Mascotas</a></li>
                    <li><a href="gestion_solicitudes.php">Solicitudes</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <h1><?= $page_title ?></h1>
            <div class="form-card">
                <form method="post" enctype="multipart/form-data">
                    <?php if ($is_edit): ?>
                        <input type="hidden" name="id" value="<?= $mascota['id'] ?>">
                        <input type="hidden" name="foto_actual" value="<?= htmlspecialchars($mascota['foto']) ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" value="<?= htmlspecialchars($mascota['nombre']) ?>" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="especie">Especie:</label>
                            <input type="text" name="especie" value="<?= htmlspecialchars($mascota['especie']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="raza">Raza:</label>
                            <input type="text" name="raza" value="<?= htmlspecialchars($mascota['raza']) ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edad">Edad (en años):</label>
                            <input type="number" name="edad" value="<?= htmlspecialchars($mascota['edad']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Género:</label>
                            <select name="genero" required>
                                <option value="Macho" <?= ($mascota['genero'] ?? '') == 'Macho' ? 'selected' : '' ?>>Macho</option>
                                <option value="Hembra" <?= ($mascota['genero'] ?? '') == 'Hembra' ? 'selected' : '' ?>>Hembra</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tamaño:</label>
                            <select name="tamano" required>
                                <option value="Pequeño" <?= ($mascota['tamano'] ?? '') == 'Pequeño' ? 'selected' : '' ?>>Pequeño</option>
                                <option value="Mediano" <?= ($mascota['tamano'] ?? '') == 'Mediano' ? 'selected' : '' ?>>Mediano</option>
                                <option value="Grande" <?= ($mascota['tamano'] ?? '') == 'Grande' ? 'selected' : '' ?>>Grande</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción (historia, personalidad, etc.):</label>
                        <textarea name="descripcion" required><?= htmlspecialchars($mascota['descripcion']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="estado_salud">Estado de Salud (vacunas, esterilización, etc.):</label>
                        <textarea name="estado_salud" required><?= htmlspecialchars($mascota['estado_salud']) ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="foto">Imagen de la Mascota:</label>
                            <input type="file" name="foto" id="foto" accept="image/*">
                            <?php if ($is_edit && !empty($mascota['foto'])): ?>
                                <p class="helper-text">Imagen actual: <?= htmlspecialchars($mascota['foto']) ?></p>
                                <img src="../uploads/<?= htmlspecialchars($mascota['foto']) ?>" alt="Imagen actual" style="width:80px; height:80px; object-fit:cover; border-radius:8px; margin-top:10px;">
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                             <label>Estado de Adopción:</label>
                            <select name="estado" required>
                                <option value="Disponible" <?= $mascota['estado'] == 'Disponible' ? 'selected' : '' ?>>Disponible</option>
                                <option value="En Proceso" <?= $mascota['estado'] == 'En Proceso' ? 'selected' : '' ?>>En Proceso</option>
                                <option value="Adoptado" <?= $mascota['estado'] == 'Adoptado' ? 'selected' : '' ?>>Adoptado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <a href="gestion_mascotas.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Mascota</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>