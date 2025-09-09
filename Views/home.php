<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Médiathèque</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
        <?php require_once('Views/components/navbar.php'); ?>


    <div class="flex items-center justify-center h-[80vh]">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Bienvenue sur la médiathèque. </h1>
            <p class="text-lg text-gray-600 mb-6">
                Chercher une oeuvre, un livre, et plein d'autres médias.
            </p>
            <a href="<?= BASE_URL ?>/media" 
               class="px-6 py-3 bg-blue-500 text-white rounded-xl shadow hover:bg-blue-600 transition">
               Rechercher un média
            </a>
        </div>
    </div>

        <?php require_once('Views/components/footer.php'); ?>
</body>
</html>