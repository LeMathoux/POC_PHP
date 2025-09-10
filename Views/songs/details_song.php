<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la chanson</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php require_once('Views/components/navbar.php'); ?>

    <div class="flex flex-col bg-gray-100 min-h-screen p-6 justify-center items-center">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-lg p-8">

            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
                <?= htmlspecialchars($song->getTitle()) ?>
            </h1>

            <div class="space-y-3 text-gray-700 mb-8">
                <p><span class="font-semibold">Note :</span> <?= htmlspecialchars($song->getNote()) ?>/10</p>
                <p><span class="font-semibold">Durée :</span> 
                    <?= sprintf("%d:%02d", intdiv($song->getDuration(), 60), $song->getDuration() % 60) ?>
                </p>
                <p>
                    <span class="font-semibold">Album :</span>
                    <a href="<?= BASE_URL.'/album/show/'.$song->getAlbumId(); ?>" 
                       class="text-blue-600 underline hover:text-blue-800">
                        <?php echo $album->getTitle()?> (<?php echo $album->getAuthor() ?>)
                    </a>
                </p>
            </div>

            <?php if(isset($_SESSION['currentUser']) && $_SESSION['currentUser'] instanceof User): ?>
                <div class="flex flex-wrap gap-3 justify-center">
                    <a href="<?= BASE_URL.'/song/update/'.$song->getId(); ?>" 
                       class="px-4 py-2 bg-yellow-400 text-white rounded-lg shadow hover:bg-yellow-500 transition">
                        Modifier
                    </a>

                    <a href="<?= BASE_URL.'/song/delete/'.$song->getId(); ?>" 
                       onclick="return confirm('Voulez-vous vraiment supprimer cette chanson ?')" 
                       class="px-4 py-2 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition">
                        Supprimer
                    </a>
                </div>
            <?php else : ?>
                <p class='bg-red-100 text-red-700 p-3 rounded mb-4 text-center mt-[10px]'>
                    Connectez-vous pour utiliser les actions :<br>
                    <a class="underline" href="<?= BASE_URL.'/connexion' ?>">Connexion</a>
                </p>
            <?php endif; ?>

            <div class="mt-8 text-center">
                <a href="<?= BASE_URL.'/song/All' ?>"  
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <?php require_once('Views/components/footer.php'); ?>
</body>
</html>
