OC.L10N.register(
    "bruteforcesettings",
    {
    "Brute-force settings" : "Paramètres contre les attaques par force brute",
    "Whitelist IPs" : "Autoriser des adresses IP",
    "Brute-force protection is meant to protect Nextcloud servers from attempts to\nguess account passwords in various ways. Besides the obvious \"*let's try a big\nlist of commonly used passwords*\" attack, it also makes it harder to use\nslightly more sophisticated attacks via the reset password form or trying to\nfind app password tokens.\n\nIf triggered, brute-force protection makes requests coming from an IP on a\nbrute-force protected controller with the same API slower for a 24 hour period.\n\nWith this app, the admin can exempt an IP address or range from this\nprotection which can be useful for testing purposes or when there are false\npositives due to a lot of accounts on one IP address." : "La protection contre les attaques de type brute force est destiné à protéger les serveurs Nextcloud des tentatives de devinez les mots de passe des utilisateurs de différentes manières. Outre l'attaque classique \"* essayons une grande liste des mots de passe couramment utilisés * \", il rend également difficile les attaques plus sophistiquées qui utilisent le formulaire de réinitialisation du mot de passe ou essayent de trouver des jetons de mot de passe d'application.Si elle est déclenchée, la protection transmet, pendant une période de 24 heures, les requêtes provenant de l'IP de l'attaquant à un contrôleur qui, en utilisant une API identique mais ralentie, est protégé des attaques brute-force.Avec cette application, l'administrateur peut exempter une adresse IP ou une plage IP de cette protection, ce qui peut être utile à des fins de test ou en cas faux positifs en raison d'un grand nombre d'utilisateurs sur une même adresse IP.",
    "Your remote address was identified as \"{remoteAddress}\" and is throttled at the moment by {delay}ms." : "Votre adresse distante a été identifiée comme « {remoteAddress} » et est limitée pour le moment à {delay} ms.",
    "Your remote address was identified as \"{remoteAddress}\" and is bypassing brute-force protection." : "Votre adresse distance a été identifiée comme « {remoteAddress} » et contourne la protection anti brute-force.",
    "Your remote address was identified as \"{remoteAddress}\" and is not actively throttled at the moment." : "Votre adresse distante a été identifiée comme « {remoteAddress} » et n'est pas limitée pour le moment.",
    "There was an error adding the IP to the whitelist." : "Il y a eu une erreur lors de l'ajout de l'adresse IP à la liste blanche.",
    "Brute-force IP whitelist" : "Liste blanche des IP pour attaque par force brute",
    "To whitelist IP ranges from the brute-force protection specify them below. Note that any whitelisted IP can perform authentication attempts without any throttling. For security reasons, it is recommended to whitelist as few hosts as possible or ideally even none at all." : "Pour ajouter des plages d'adresses IP à la protection par brute force, spécifiez-les ci-dessous. Notez que n'importe quelle adresse IP figurant sur la liste blanche peut effectuer des tentatives d'authentification sans aucune limitation. Pour des raisons de sécurité, il est recommandé de mettre en liste blanche le plus petit nombre possible ou idéalement aucun hôte.",
    "Apply whitelist to rate limit" : "Appliquer une liste blanche à la limitation du débit",
    "Add a new whitelist" : "Ajouter une nouvelle liste blanche",
    "IP address" : "Adresse IP",
    "Mask" : "Masque de sous-réseau",
    "Add" : "Ajouter",
    "Delete entry for {subnet}" : "Supprimer l'entrée pour {subnet}"
},
"nplurals=3; plural=(n == 0 || n == 1) ? 0 : n != 0 && n % 1000000 == 0 ? 1 : 2;");
