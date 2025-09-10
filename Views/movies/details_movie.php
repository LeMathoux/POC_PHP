<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Film</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php require_once('Views/components/navbar.php'); ?>

    <div class="flex flex-col bg-gray-100 h-[80vh] p-6 justify-center items-center">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-lg p-8">

            <!-- Titre -->
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
                <?= htmlspecialchars($movie->getTitle()) ?>
            </h1>

            <!-- Infos principales -->
            <div class="space-y-3 text-gray-700 mb-8">
                <p><span class="font-semibold">Auteur :</span> <?= htmlspecialchars($movie->getAuthor()) ?></p>
                
                <p>
                    <span class="font-semibold">Durée :</span> 
                    <?php 
                        echo htmlspecialchars($movie->showDuration());
                    ?>
                </p>

                <p><span class="font-semibold">Genre :</span> <?= htmlspecialchars($movie->getGenre()->value) ?></p>
            </div>

            <!-- Actions (si connecté) -->
            <?php if(isset($_SESSION['currentUser']) && $_SESSION['currentUser'] instanceof User): ?>
                <div class="flex flex-wrap gap-3 justify-center">

                    <?php if($movie->getAvailable()): ?>
                        <a href="<?= BASE_URL.'/movie/emprunter/'.$movie->getId(); ?>" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                            Emprunter
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL.'/movie/rendre/'.$movie->getId(); ?>" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                            Rendre
                        </a>
                    <?php endif; ?>

                    <a href="<?= BASE_URL.'/movie/update/'.$movie->getId(); ?>" 
                       class="px-4 py-2 bg-yellow-400 text-white rounded-lg shadow hover:bg-yellow-500 transition">
                        Modifier
                    </a>

                    <a href="<?= BASE_URL.'/movie/delete/'.$movie->getId(); ?>" 
                       onclick="return confirm('Voulez-vous vraiment supprimer ce film ?')" 
                       class="px-4 py-2 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition">
                        Supprimer
                    </a>
                </div>
            <?php else : ?>
                <p class='bg-red-100 text-red-700 p-3 rounded mb-4 text-center mt-[10px]' id='errorMsg'>
                    Connectez-vous pour utiliser les actions :<br>
                    <a class="underline" href="<?= BASE_URL.'/connexion' ?>">Connexion</a>
                </p>
            <?php endif; ?>

            <!-- Bouton retour -->
            <div class="mt-8 text-center">
                <a href="<?= BASE_URL.'/movie/All' ?>"  
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <?php require_once('Views/components/footer.php'); ?>

</body>
</html>
