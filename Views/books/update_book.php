<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Livre</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    
    <?php require_once('Views/components/navbar.php'); ?>

    <div class="flex items-center justify-center h-[80vh]">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Modifier le Livre</h1>

            <form action="" method="POST" class="space-y-5">
                <div>
                    <label for="title" class="block text-gray-700 font-medium mb-1">Titre</label>
                    <input type="text" id="title" name="title" required
                        value="<?= htmlspecialchars($updatedBook->getTitle()) ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="author" class="block text-gray-700 font-medium mb-1">Auteur</label>
                    <input type="text" id="author" name="author" required
                        value="<?= htmlspecialchars($updatedBook->getAuthor()) ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="page_number" class="block text-gray-700 font-medium mb-1">Nombre de pages</label>
                    <input type="number" id="page_number" name="page_number" min="1" required
                        value="<?= htmlspecialchars($updatedBook->getPageNumber()) ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="available" class="block text-gray-700 font-medium mb-1">Disponible</label>
                    <select id="available" name="available" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="1" <?= $updatedBook->getAvailable() ? 'selected' : '' ?>>Oui</option>
                        <option value="0" <?= !$updatedBook->getAvailable() ? 'selected' : '' ?>>Non</option>
                    </select>
                </div>

                <div class="flex justify-between items-center">
                    <a href="javascript:history.back()"  
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Retour
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-yellow-400 text-white rounded-lg shadow hover:bg-yellow-500 transition">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php require_once('Views/components/footer.php'); ?>

</body>
</html>
