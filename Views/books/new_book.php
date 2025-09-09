<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Livre</title>
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
    
    <div class="flex items-center justify-center h-[80vh]">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Ajouter un Livre</h1>

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
                    <label for="page_number" class="block text-gray-700 font-medium mb-1">Nombre de pages</label>
                    <input type="number" id="page_number" name="page_number" min="1" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
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
                    <a href="./books" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Retour
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                        Ajouter
                    </button>
                </div>
                <?php
                    if(isset($errors) && !empty($errors)){
                        echo"<p class='bg-red-100 text-red-700 p-3 rounded mb-4 text-center mt-[10px]' id='errorMsg'>";
                        foreach($errors as $error){
                            echo $error."<br>";
                        }
                        echo "</p>";
                    }
                ?>

            </form>
        </div>
    </div>
    <footer class="bg-white shadow-inner py-4 mt-10 text-center text-gray-600">
        © <?php echo date("Y"); ?> - Médiathèque. Tous droits réservés.
    </footer>

</body>
</html>
