<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-10 rounded-2xl shadow-2xl w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Créer un compte</h1>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-1" for="userName">Nom d'utilisateur</label>
                <input type="text" id="userName" name="userName" required
                       class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 mb-1" for="email">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 mb-1" for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 mb-1" for="passwordConfirm">Confirmer le mot de passe</label>
                <input type="password" id="passwordConfirm" name="passwordConfirm" required
                       class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 rounded-xl shadow hover:bg-blue-600 transition">
                S'inscrire
            </button>
        </form>

        <?php if(isset($errors) && !empty($errors)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center mt-[10px]">
                <?php foreach($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p class="text-center text-gray-500 mt-4">
            Déjà un compte ? <a href="./connexion" class="text-blue-500 hover:underline">Se connecter</a>
        </p>
    </div>
</body>
</html>
