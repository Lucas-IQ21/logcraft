## Classement des recommandations ANSSI

Projet : Mise en place d’un système de journalisation (LogCraft)  
Technologies utilisées :
- **Langage** : PHP  
- **Base de données (BDD)** : MariaDB  
- **Journalisation PHP** : `Syslog()`  
- **Journalisation SQL** : `general_log`  
- **Transfert des logs** : Syslog avec TCP + TLS  
- **Stockage des logs** : `/var/logs/rsyslog_logcraft/`  
- **Visualisation** : `grep`, `journalctl`  

---

### ✅ Recommandations mises en œuvre

| Recommandation | Intitulé |
|----------------|----------|
| **R1** | Utiliser des solutions disposant d’une fonction de journalisation native |
| **R2** | Activer la journalisation sur un grand nombre d’équipements |
| **R3** | Horodater les événements |
| **R4** | Homogénéiser les paramètres d’horodatage |
| **R5** | Synchroniser les horloges |
| **R6** | Identifier la granularité de journalisation |
| **R8** | Estimer l’espace de stockage local des journaux |
| **R9** | Exporter les journaux vers un serveur dédié |
| **R10** | Sécuriser la transmission des journaux |
| **R11** | Préserver l’intégrité des journaux transférés |
| **R14** | Restreindre les accès aux journaux |
| **R15** | Mettre en œuvre une rotation des journaux |
| **R19** | Utiliser des formats de journaux structurés |
| **R20** | Uniformiser les formats et conventions |

---

### 🚫 Recommandations non mises en œuvre

| Recommandation | Intitulé | Motif |
|----------------|----------|-------|
| **R7** | Journaliser les empreintes des fichiers malveillants | Pas d'antivirus/EDR intégré |
| **R9-** | Exporter les journaux vers une autre partition locale | Utilisation directe d’un serveur dédié |
| **R13** | Configurer les niveaux de journalisation avec discernement | Pas de gestion avancée des niveaux dans PHP/MariaDB |
| **R16** | Conserver les journaux sur une durée adaptée | Pas de mécanisme automatisé de rétention configuré |

---

### 🌟 Recommandations "bonus" (souhaitées si temps disponible)

| Recommandation | Intitulé | Commentaire |
|----------------|----------|-------------|
| **R12** | Transfert des journaux en temps réel ou différé avec horodatage | Requiert configuration et vérification de latence avec rsyslog |
| **R17** | Superviser la chaîne de journalisation | Demande outils de supervision ou scripts personnalisés |
| **R18** | Surveiller les volumes et alertes | Implique la mise en place d’un monitoring (ex : Prometheus, fail2ban) |
| **R21** | Documenter et tester la chaîne de journalisation | Bonne pratique, réalisable en fin de projet |

