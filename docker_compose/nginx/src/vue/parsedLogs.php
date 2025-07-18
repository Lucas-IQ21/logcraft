<?php
function getActionColor($action) {
    return [
        'GET' => 'green',
        'POST' => 'purple',
        'DELETE' => 'red',
        'PUT' => 'yellow',
        'PROPFIND' => 'cyan',
        'REPORT' => 'indigo',
        'HEAD' => 'gray'
    ][$action] ?? 'gray';
}

function getActionIcon($action) {
    $color = getActionColor($action);
    return "<svg class='w-5 h-5 text-$color-600' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path d='M9 5l7 7-7 7'/></svg>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs par actions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-wrap gap-4 items-center justify-between">
            <div class="text-xl font-bold text-blue-600">🧾 Journal des logs</div>
            <div class="flex flex-wrap gap-2">
                <button onclick="window.location.href='index.php?page=logsParsed'" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Voir les logs analysés
                </button>
                <button onclick="window.location.href='index.php?page=logs'" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                    Voir les logs bruts
                </button>
                <button onclick="window.location.href='index.php?page=logout'" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                    Se déconnecter
                </button>
            </div>
        </div>
    </nav>

    <header class="text-center mb-10">
        <h1 class="text-3xl font-bold text-blue-700">Logs regroupés par action</h1>
    </header>

    <main class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($groupedLogs as $action => $usersLogs): ?>
            <?php 
                $color = getActionColor($action);
                $totalLogs = count(array_merge(...array_values($usersLogs)));
            ?>
            <div class="bg-white border-l-4 border-<?= $color ?>-500 rounded-xl shadow hover:shadow-xl transition-all overflow-hidden">
                <div class="flex items-center justify-between bg-<?= $color ?>-50 px-4 py-3 border-b border-<?= $color ?>-200">
                    <div class="flex items-center gap-2">
                        <?= getActionIcon($action) ?>
                        <h2 class="text-lg font-semibold text-<?= $color ?>-700"><?= htmlspecialchars($action) ?></h2>
                    </div>
                    <span class="bg-<?= $color ?>-100 text-<?= $color ?>-800 text-sm font-medium px-3 py-1 rounded-full">
                        <?= $totalLogs ?> requêtes
                    </span>
                </div>
                <div class="p-4 space-y-4 max-h-96 overflow-y-auto">
                    <?php foreach ($usersLogs as $user => $logs): ?>
                        <div>
                            <h3 class="text-md font-semibold text-gray-700 mb-1">👤 <?= htmlspecialchars($user) ?></h3>
                            <ul class="space-y-1 text-sm text-gray-700">
                                <?php foreach ($logs as $log): ?>
                                    <li class="pl-2 border-l-2 border-<?= $color ?>-300 flex gap-2">
                                        <span class="font-mono text-xs text-gray-500">[<?= $log->timestamp ?>]</span>
                                        <span><?= htmlspecialchars($log->path) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
</body>
</html>
