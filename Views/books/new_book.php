<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Livre</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php require_once('Views/components/navbar.php'); ?>
    
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
                    <a href="javascript:history.back()" 
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

    <?php require_once('Views/components/footer.php'); ?>

</body>
</html>
