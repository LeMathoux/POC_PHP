<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Médiathèque</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    </head>
    <body class="bg-gray-100">

        <?php require_once('Views/components/navbar.php'); ?>

        <div class="flex flex-col items-center justify-center h-[80vh]">
            <div class="flex flex-col items-center mb-10">
                <i class="fa-solid fa-layer-group text-7xl text-blue-600 mb-4"></i>
                <h1 class="text-4xl font-bold text-gray-800">Catégories</h1>
                <p class="text-gray-500">Choisissez un type de média</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6">
                <a href="<?= BASE_URL ?>/categories" class="flex flex-col items-center justify-center bg-white p-6 rounded-2xl shadow hover:shadow-xl transition">
                    <i class="fa-solid fa-layer-group text-5xl text-purple-600 mb-4"></i>
                    <span class="text-lg font-semibold text-gray-700">Toutes</span>
                </a>
                <a href="<?= BASE_URL ?>/albums" class="flex flex-col items-center justify-center bg-white p-6 rounded-2xl shadow hover:shadow-xl transition">
                    <i class="fa-solid fa-compact-disc text-5xl text-blue-500 mb-4"></i>
                    <span class="text-lg font-semibold text-gray-700">Albums</span>
                </a>

                <a href="<?= BASE_URL ?>/book/All" class="flex flex-col items-center justify-center bg-white p-6 rounded-2xl shadow hover:shadow-xl transition">
                    <i class="fa-solid fa-book text-5xl text-green-500 mb-4"></i>
                    <span class="text-lg font-semibold text-gray-700">Books</span>
                </a>

                <a href="<?= BASE_URL ?>/songs" class="flex flex-col items-center justify-center bg-white p-6 rounded-2xl shadow hover:shadow-xl transition">
                    <i class="fa-solid fa-music text-5xl text-pink-500 mb-4"></i>
                    <span class="text-lg font-semibold text-gray-700">Songs</span>
                </a>

                <a href="<?= BASE_URL ?>/movies" class="flex flex-col items-center justify-center bg-white p-6 rounded-2xl shadow hover:shadow-xl transition">
                    <i class="fa-solid fa-film text-5xl text-red-500 mb-4"></i>
                    <span class="text-lg font-semibold text-gray-700">Movies</span>
                </a>

            </div>
        </div>

        <?php require_once('Views/components/footer.php'); ?>
        
    </body>
</html>
