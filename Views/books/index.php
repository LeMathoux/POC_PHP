<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Books - Médiathèque</title>
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

    <div class="flex flex-col bg-gray-100 h-[80vh] p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Bibliothèque - Books</h1>
            
        <form method="post" class="flex flex-col md:flex-row md:items-center mb-6 space-y-4 md:space-y-0 md:space-x-4">
            <input type="text" id="searchInput" placeholder="Rechercher..." onchange="this.form.submit()"
                class="w-full md:w-1/2 px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">

            <select id="filterSelect" 
                    class="w-full md:w-1/4 px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="title">Titre</option>
                <option value="author">Auteur</option>
                <option value="page_number">Nombre de pages</option>
            </select>

            <div class="flex justify-between items-center mb-6 h-[20px]">
                <h1 class="text-3xl font-bold text-gray-800"></h1>
                <a href="<?= BASE_URL ?>/new" 
                class="px-6 py-2 bg-green-500 text-white rounded-xl shadow hover:bg-green-600 transition">
                    + Ajouter un livre
                </a>
            </div>
        </form>

        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pages</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="booksTable" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4">Le Petit Prince</td>
                        <td class="px-6 py-4">Antoine de Saint-Exupéry</td>
                        <td class="px-6 py-4">96</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="<?= BASE_URL ?>/book/2" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-sm">Gérer</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
     <footer class="bg-white shadow-inner py-4 mt-10 text-center text-gray-600">
        © <?php echo date("Y"); ?> - Médiathèque. Tous droits réservés.
    </footer>
</body>
</html>
