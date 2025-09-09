<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Livre</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php require_once('Views/components/navbar.php'); ?>

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

            <?php if(isset($_SESSION['currentUser']) && $_SESSION['currentUser'] instanceof User): ?>
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

                    <a href="<?php echo BASE_URL.'/book/update/'.$book->getId(); ?>" 
                    class="px-4 py-2 bg-yellow-400 text-white rounded-lg shadow hover:bg-yellow-500 transition">
                        Modifier
                    </a>

                    <a href="<?php echo BASE_URL.'/book/delete/'.$book->getId(); ?>" 
                    onclick="return confirm('Voulez-vous vraiment supprimer ce livre ?')" 
                    class="px-4 py-2 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition">
                        Supprimer
                    </a>
                </div>
            <?php else : ?>
                <p class='bg-red-100 text-red-700 p-3 rounded mb-4 text-center mt-[10px]' id='errorMsg'> Connectez vous pour utiliser les actions :<br><a class="underline" href="<?php echo BASE_URL.'/connexion' ?>">Connexion</a></p>
            <?php endif; ?>

            <div class="mt-8 text-center">
                <a href="<?php echo BASE_URL.'/book/All' ?>"  class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>
    <?php require_once('Views/components/footer.php'); ?>
</body>
</html>
