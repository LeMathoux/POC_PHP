<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Livre</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

    <div class="flex flex-col bg-gray-100 h-[80vh] p-6 justify-center items-center">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-lg p-8">

            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
                <?= htmlspecialchars($book->getTitle()) ?>
            </h1>

            <div class="space-y-3 text-gray-700 mb-8">
                <p><span class="font-semibold">Auteur :</span> <?= htmlspecialchars($book->getAuthor()) ?></p>
                <p><span class="font-semibold">Nombre de pages :</span> <?= htmlspecialchars($book->getPageNumber()) ?></p>
                <p>
                    <span class="font-semibold">Disponible :</span> 
                    <?php if($book->getAvailable()): ?>
                        <span class="text-green-600 font-medium">Oui</span>
                    <?php else: ?>
                        <span class="text-red-600 font-medium">Non</span>
                    <?php endif; ?>
                </p>
            </div>

            <div class="flex flex-wrap gap-3 justify-center">
                <?php if($book->getAvailable()): ?>
                    <a href="" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                        Emprunter
                    </a>
                <?php else: ?>
                    <a href="" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                        Rendre
                    </a>
                <?php endif; ?>

                <a href="" 
                class="px-4 py-2 bg-yellow-400 text-white rounded-lg shadow hover:bg-yellow-500 transition">
                    Modifier
                </a>

                <a href="" 
                onclick="return confirm('Voulez-vous vraiment supprimer ce livre ?')" 
                class="px-4 py-2 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition">
                    Supprimer
                </a>
            </div>

            <div class="mt-8 text-center">
                <a href="./books" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>
     <footer class="bg-white shadow-inner py-4 mt-10 text-center text-gray-600">
        © <?php echo date("Y"); ?> - Médiathèque. Tous droits réservés.
    </footer>
</body>
</html>
