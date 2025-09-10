<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de l'album</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php require_once('Views/components/navbar.php'); ?>

    <div class="flex flex-col bg-gray-100 min-h-screen p-6 justify-center items-center">
        <div class="bg-white w-full max-w-3xl rounded-2xl shadow-lg p-8">

            <!-- Infos album -->
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
                <?= htmlspecialchars($album->getTitle()) ?>
            </h1>

            <div class="space-y-3 text-gray-700 mb-6">
                <p><span class="font-semibold">Auteur :</span> <?= htmlspecialchars($album->getAuthor()) ?></p>
                <p><span class="font-semibold">Éditeur :</span> <?= htmlspecialchars($album->getEditor()) ?></p>
                <p>
                    <span class="font-semibold">Disponible :</span> 
                    <?php if($album->getAvailable()): ?>
                        <span class="text-green-600 font-medium">Oui</span>
                    <?php else: ?>
                        <span class="text-red-600 font-medium">Non</span>
                    <?php endif; ?>
                </p>
            </div>

            <!-- Liste des chansons -->
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Chansons de l'album</h2>

            <?php if (!empty($album->getSongs())): ?>
                <ul class="space-y-2 mb-6">
                    <?php foreach($album->getSongs() as $song): ?>
                        <li class="flex justify-between items-center bg-gray-100 p-3 rounded-lg">
                            <span><a href="<?php echo BASE_URL.'/song/show/'.$song->getId(); ?>"><?= htmlspecialchars($song->getTitle()) ?></a></span>
                            <span class="text-gray-500"><?= sprintf("%d:%02d", intdiv($song->getDuration(), 60), $song->getDuration() % 60) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500 mb-6">Aucune chanson dans cet album.</p>
            <?php endif; ?>

            <!-- Actions -->
            <?php if(isset($_SESSION['currentUser']) && $_SESSION['currentUser'] instanceof User): ?>
                <div class="flex flex-wrap gap-3 justify-center mb-6">
                    <?php if($album->getAvailable()): ?>
                        <a href="<?php echo BASE_URL.'/album/emprunter/'.$album->getId(); ?>" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                            Emprunter
                        </a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL.'/album/rendre/'.$album->getId(); ?>" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
                            Rendre
                        </a>
                    <?php endif; ?>

                    <a href="<?= BASE_URL.'/album/update/'.$album->getId(); ?>" 
                       class="px-4 py-2 bg-yellow-400 text-white rounded-lg shadow hover:bg-yellow-500 transition">
                        Modifier l'album
                    </a>

                    <a href="<?= BASE_URL.'/album/delete/'.$album->getId(); ?>" 
                       onclick="return confirm('Voulez-vous vraiment supprimer cet album ?')" 
                       class="px-4 py-2 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition">
                        Supprimer l'album
                    </a>

                    <a href="<?= BASE_URL.'/song/new?albumId='.$album->getId(); ?>" 
                       class="px-4 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition">
                        + Ajouter une chanson
                    </a>
                </div>
            <?php else: ?>
                <p class='bg-red-100 text-red-700 p-3 rounded mb-4 text-center'>
                    Connectez-vous pour utiliser les actions :<br>
                    <a class="underline" href="<?= BASE_URL.'/connexion' ?>">Connexion</a>
                </p>
            <?php endif; ?>

            <!-- Bouton retour -->
            <div class="mt-4 text-center">
                <a href="<?= BASE_URL.'/album/All' ?>"  
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <?php require_once('Views/components/footer.php'); ?>
</body>
</html>
