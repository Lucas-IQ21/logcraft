<?php
require_once __DIR__ . '/../model/Log.php';

$logs = Log::getAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des logs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans">
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
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Logs système</h1>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200" id="logsTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" id="sortDate">
                            Date
                            <span id="sortIcon" class="inline-block ml-1">⬍</span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Message
                            <input type="text" id="searchMessage" placeholder="Rechercher..." class="mt-1 w-full px-2 py-1 text-sm border rounded">
                        </th>
                    </tr>
                </thead>
                <tbody id="logBody" class="bg-white divide-y divide-gray-200"></tbody>
            </table>
        </div>

        <div id="paginationControls" class="flex justify-center mt-6 space-x-2"></div>
    </div>

    <script>
        const allLogs = <?= json_encode($logs) ?>;
        let filteredLogs = [...allLogs];
        let sortAsc = true;
        let currentPage = 1;
        const logsPerPage = 10;

        const logBody = document.getElementById("logBody");
        const searchInput = document.getElementById("searchMessage");
        const sortDateHeader = document.getElementById("sortDate");
        const sortIcon = document.getElementById("sortIcon");
        const paginationControls = document.getElementById("paginationControls");

        function renderLogs() {
            const start = (currentPage - 1) * logsPerPage;
            const pageLogs = filteredLogs.slice(start, start + logsPerPage);

            logBody.innerHTML = pageLogs.map(log => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${log.receivedAt}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600">${log.message}</td>
                </tr>
            `).join("");

            renderPagination();
        }

    function renderPagination() {
        const totalPages = Math.ceil(filteredLogs.length / logsPerPage);
        paginationControls.innerHTML = "";

        if (totalPages <= 1) return;

        const createBtn = (text, page = null, isActive = false, disabled = false) => {
            const btn = document.createElement("button");
            btn.textContent = text;
            btn.className = `px-3 py-1 rounded border transition ${
                isActive
                    ? 'bg-blue-500 text-white'
                    : disabled
                        ? 'bg-gray-200 text-gray-400 cursor-not-allowed'
                        : 'bg-white text-blue-500 hover:bg-blue-100'
            }`;
            if (!disabled && page !== null) {
                btn.onclick = () => { currentPage = page; renderLogs(); };
            } else if (disabled) {
                btn.disabled = true;
            }
            return btn;
        };

        paginationControls.appendChild(createBtn("«", currentPage - 1, false, currentPage === 1));

        const range = [];
        const delta = 2;
        const lastPage = totalPages;

        for (let i = 1; i <= totalPages; i++) {
            if (
                i === 1 || i === lastPage || 
                (i >= currentPage - delta && i <= currentPage + delta)
            ) {
                range.push(i);
            } else if (range[range.length - 1] !== "...") {
                range.push("...");
            }
        }

        range.forEach(p => {
            if (p === "...") {
                const dots = document.createElement("span");
                dots.textContent = "...";
                dots.className = "px-2 py-1 text-gray-500";
                paginationControls.appendChild(dots);
            } else {
                paginationControls.appendChild(createBtn(p, p, p === currentPage));
            }
        });


        paginationControls.appendChild(createBtn("»", currentPage + 1, false, currentPage === totalPages));
    }


        function filterLogs() {
            const searchTerm = searchInput.value.toLowerCase();
            filteredLogs = allLogs.filter(log =>
                log.message.toLowerCase().includes(searchTerm)
            );
            currentPage = 1;
            renderLogs();
        }

        function sortByDate() {
            filteredLogs.sort((a, b) => {
                const dateA = new Date(a.receivedAt);
                const dateB = new Date(b.receivedAt);
                return sortAsc ? dateA - dateB : dateB - dateA;
            });
            sortAsc = !sortAsc;
            sortIcon.textContent = sortAsc ? '⬆️' : '⬇️';
            renderLogs();
        }

        searchInput.addEventListener("input", filterLogs);
        sortDateHeader.addEventListener("click", sortByDate);

        renderLogs();
    </script>
</body>
</html>
