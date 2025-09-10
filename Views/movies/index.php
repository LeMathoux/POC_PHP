<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Movies - Médiathèque</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php require_once('Views/components/navbar.php'); ?>

    <div class="flex flex-col bg-gray-100 h-[80vh] p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Bibliothèque - Movies</h1>
            
        <form method="post" action="" class="flex flex-col md:flex-row md:items-center mb-6 space-y-4 md:space-y-0 md:space-x-4">
            <input type="text" id="search" name='search' placeholder="Rechercher..." value="<?php if(isset($_POST['search'])){ echo $_POST['search']; } ?>"
                class="w-full md:w-1/2 px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition shadow">Rechercher</button>

            <select id="filterSelect"  name="sort" onchange="this.form.submit()"
                    class="w-full md:w-1/4 px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="title" <?php if(isset($_POST['sort']) && $_POST['sort'] === 'title') echo 'selected'; ?>>
                    Titre
                </option>  
                <option value="duration" <?php if(isset($_POST['sort']) && $_POST['sort'] === 'duration') echo 'selected'; ?>>
                    Durée
                </option>  
                <option value="author" <?php if(isset($_POST['sort']) && $_POST['sort'] === 'author') echo 'selected'; ?>>
                    Author
                </option>  
                <option value="genre" <?php if(isset($_POST['sort']) && $_POST['sort'] === 'genre') echo 'selected'; ?>>
                    Genre
                </option>
                <option value="available" <?php if(isset($_POST['sort']) && $_POST['sort'] === 'available') echo 'selected'; ?>>
                    Disponible
                </option>         
            </select>
            <?php if(isset($_SESSION['currentUser']) && !empty($_SESSION['currentUser'])): ?>
                <div class="flex justify-between items-center mb-6 h-[20px]">
                    <h1 class="text-3xl font-bold text-gray-800"></h1>
                    <a href="<?= BASE_URL ?>/movie/new" 
                    class="px-6 py-2 bg-green-500 text-white rounded-xl shadow hover:bg-green-600 transition">
                        + Ajouter un film
                    </a>
                </div>
            <?php endif; ?>
        </form>

        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disponible</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="booksTable" class="bg-white divide-y divide-gray-200">
                    <?php
                    foreach($movies as $movie){
                    echo "<tr>
                        <td class='px-6 py-4'>".$movie->getTitle()."</td>
                        <td class='px-6 py-4'>".$movie->getAuthor()."</td>
                        <td class='px-6 py-4'>".$movie->showDuration()."</td>
                        <td class='px-6 py-4'>".$movie->getGenre()->value."</td>
                        <td class='px-6 py-4'>"
                            .($movie->getAvailable() 
                                ? "<span class='text-green-600 font-medium'>Oui</span>" 
                                : "<span class='text-red-600 font-medium'>Non</span>")
                        ."</td>
                        <td class='px-6 py-4 text-center space-x-2'>
                            <a href='".BASE_URL."/movie/show/".$movie->getId()."' class='px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-sm'>Gérer</a>
                        </td>
                    </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php require_once('Views/components/footer.php'); ?>
</body>
</html>
