<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un film</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php require_once('Views/components/navbar.php'); ?>
    
    <div class="flex items-center justify-center h-[80vh]">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Ajouter un nouveau film</h1>

            <form action="" method="POST" class="space-y-5">
                
                <div>
                    <label for="title" class="block text-gray-700 font-medium mb-1">Titre</label>
                    <input type="text" id="title" name="title" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="author" class="block text-gray-700 font-medium mb-1">Auteur</label>
                    <input type="text" id="author" name="author" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="duration" class="block text-gray-700 font-medium mb-1">Durée (en minutes)</label>
                    <input type="number" id="duration" name="duration" min="1" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="genre" class="block text-gray-700 font-medium mb-1">Genre</label>
                    <select id="genre" name="genre" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="Aventure">Aventure</option>
                        <option value="Policier">Policier</option>
                        <option value="Animation">Animation</option>
                    </select>
                </div>

                <div>
                    <label for="available" class="block text-gray-700 font-medium mb-1">Disponible</label>
                    <select id="available" name="available" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>
                </div>

                <div class="flex justify-between items-center">
                    <a href="<?= BASE_URL ?>/movie/All" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Retour
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition">
                        Ajouter le film
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php require_once('Views/components/footer.php'); ?>

</body>
</html>
