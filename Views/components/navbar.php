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