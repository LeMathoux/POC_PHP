<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une chanson</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php require_once('Views/components/navbar.php'); ?>

    <div class="flex flex-col justify-center items-center min-h-screen bg-gray-100 p-6">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-lg p-8">
            
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
                Modifier la chanson
            </h1>

            <form action="" method="post" class="space-y-4">

                <div>
                    <label for="albumId" class="block text-gray-700 font-semibold mb-2">Album</label>
                    <select id="albumId" name="albumId" required
                            class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Sélectionner un album --</option>
                        <?php foreach($albums as $album): ?>
                            <option value="<?= $album->getId(); ?>" <?= ($updatedSong->getAlbumId() == $album->getId()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($album->getTitle()); ?> (<?= htmlspecialchars($album->getAuthor()); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="title" class="block text-gray-700 font-semibold mb-2">Titre</label>
                    <input type="text" id="title" name="title" 
                           value="<?= htmlspecialchars($updatedSong->getTitle()) ?>" required
                           class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="note" class="block text-gray-700 font-semibold mb-2">Note (sur 10)</label>
                    <input type="number" id="note" name="note" min="0" max="10" step="0.1" required
                           value="<?= htmlspecialchars($updatedSong->getNote()) ?>"
                           class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="duration" class="block text-gray-700 font-semibold mb-2">Durée (minutes)</label>
                    <input type="number" id="duration" name="duration" min="1" required
                           value="<?= htmlspecialchars($updatedSong->getDuration()) ?>"
                           class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-between items-center mt-6">
                    <a href="<?= BASE_URL.'/album/show/'.$updatedSong->getAlbumId() ?>" 
                       class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg shadow hover:bg-gray-400 transition">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600 transition">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php require_once('Views/components/footer.php'); ?>
</body>
</html>
