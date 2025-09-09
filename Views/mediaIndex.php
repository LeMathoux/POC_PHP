<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Médiathèque</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    </head>
    <body class="bg-gray-100">
        <nav class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
                <a href="<?= BASE_URL ?>/" class="text-2xl font-bold text-blue-600">Médiathèque</a>

                <ul class="flex space-x-6 items-center">
                    <li><a href="<?= BASE_URL ?>/" class="text-gray-700 hover:text-blue-600">Accueil</a></li>
                    <li><a href="<?= BASE_URL ?>/media" class="text-gray-700 hover:text-blue-600">Les Médias</a></li>

                    <?php if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])): ?>
                        <?php $user = $_SESSION['currentUser']; ?>
                        <li class="ml-4 px-3 py-2 bg-gray-100 rounded-xl text-gray-800 font-semibold">
                            <?= htmlspecialchars($user->getUserName()) ?> ( <?= htmlspecialchars($user->getEmail()) ?> )
                        </li>
                        <li>
                            <a href="<?= BASE_URL ?>/connexion/logout" 
                            class="ml-2 px-3 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition">
                                Déconnexion
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?= BASE_URL ?>/connexion" 
                            class="px-3 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition">
                                Connexion
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

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

         <footer class="bg-white shadow-inner py-4 mt-10 text-center text-gray-600">
        © <?php echo date("Y"); ?> - Médiathèque. Tous droits réservés.
        </footer>
    </body>
</html>
