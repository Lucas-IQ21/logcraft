OC.L10N.register(
    "settings",
    {
    "Private" : "Privāts",
    "Local" : "Vietējs",
    "Federated" : "Federated",
    "Verify" : "Verificēt",
    "Unable to change password" : "Nav iespējams nomainīt paroli",
    "Very weak password" : "Ļoti vāja parole",
    "Weak password" : "Vāja parole",
    "So-so password" : "Viduvēja parole",
    "Good password" : "Laba parole",
    "Strong password" : "Spēcīga parole",
    "Groups" : "Grupas",
    "Group list is empty" : "Grupu saraksts ir tukšš",
    "Unable to retrieve the group list" : "Nevarēja saņemt grupu sarakstu",
    "{actor} changed your password" : "{actor] nomainīja Tavu paroli",
    "You changed your password" : "Tu nomainīji savu paroli",
    "You renamed app password \"{token}\" to \"{newToken}\"" : "Tu pārdēvēji lietotnes paroli \"{token}\" par \"{newToken}\"",
    "Security" : "Drošība",
    "You successfully logged in using two-factor authentication (%1$s)" : "Sekmīga pieteikšanās ar divpakāpju autentificēšanos (%1$s)",
    "A login attempt using two-factor authentication failed (%1$s)" : "Pieteikšanās mēģinājums ar divpakāpju autentifikāciju neizdevās (%1$s)",
    "Settings" : "Iestatījumi",
    "Could not update app." : "Lietotni nevarēja atjaunināt.",
    "Wrong password" : "Nepareiza parole",
    "Saved" : "Saglabāts",
    "Authentication error" : "Autentificēšanās kļūda",
    "Wrong admin recovery password. Please check the password and try again." : "Nepareiza pārvaldītāja atkopes parole. Lūgums pārbaudītt paroli un mēģināt vēlreiz.",
    "Administrator documentation" : "Pārvaldītāja dokumentācija",
    "User documentation" : "Lietotāja dokumentācija",
    "Nextcloud help overview" : "Nextcloud palīdzības pārskats",
    "Invalid SMTP password." : "Nederīga SMTP parole",
    "A problem occurred while sending the email. Please revise your settings. (Error: %s)" : "Atgadījās kļūda e-pasta ziņojuma nosūtīšanas laikā. Lūgums pārskatīt savus iestatījumus. (Kļūda: %s)",
    "Invalid account" : "Nederīgs konts",
    "Invalid mail address" : "Nepareiza e-pasta adrese",
    "Settings saved" : "Iestatījumi saglabāti",
    "Unable to change full name" : "Nevar nomainīt pilno vārdu",
    "Unable to change email address" : "Nevar mainīt e-pasta adresi",
    "In order to verify your Website, store the following content in your web-root at '.well-known/CloudIdVerificationCode.txt' (please make sure that the complete text is in one line):" : "Lai apliecinātu savu tīmekļvietni, zemāk esošais saturs ir jāglabā tīmekļvietnes pamatmapē kā `.well-known/CloudIdVerificationCode.txt` (lūgums pārliecināties, ka viss teksts ir vienā rindiņā):",
    "Your %s account was created" : "Konts %s ir izveidots",
    "Apps" : "Lietotnes",
    "Personal" : "Personīgi",
    "Administration" : "Pārvaldīšana",
    "Users" : "Lietotāji",
    "Additional settings" : "Papildu iestatījumi",
    "Administration privileges" : "Pārvaldīšanas tiesības",
    "Overview" : "Pārskats",
    "Basic settings" : "Pamata iestatījumi",
    "Sharing" : "Koplietošana",
    "Availability" : "Pieejamība",
    "Calendar" : "Kalendārs",
    "Personal info" : "Personiskā informācija",
    "Mobile & desktop" : "Tālrunis un darbvirsma",
    "Email server" : "E-pasta serveris",
    "Unlimited" : "Neierobežota",
    "Could not check that the data directory is protected. Please check manually that your server does not allow access to the data directory." : "Nevarēja pārbaudīt, vai datu mape ir aizsargāta. Lūgums pašrocīgi pārbaudīt, ka serveris neļauj piekļūt datu mapei.",
    "Some columns in the database are missing a conversion to big int. Due to the fact that changing column types on big tables could take some time they were not changed automatically. By running \"occ db:convert-filecache-bigint\" those pending changes could be applied manually. This operation needs to be made while the instance is offline." : "Datubāzē dažām kolonnām trūkst pārveidošana uz lieliem skaitļiem. Tā dēļ, ka kolonnu veida mainīšana lielās tabulās var aizņemt kādu laiku, tās netika mainītas automātiski. Izpildot \"occ db:convert-filecache-bigint\", šīs neveiktās izmaiņas var pielietot pašrocīgi. Šī darbība jāveic, kamēr serveris ir bezsaistē.",
    "You have not set or verified your email server configuration, yet. Please head over to the \"Basic settings\" in order to set them. Afterwards, use the \"Send email\" button below the form to verify your settings." : "Vēl nav iestatīts e-pasta serveris vai apliecināta tā konfigurācija. Lūgums doties uz \"Pamata iestatījumi\", lai varētu to iestatīt. Pēc tam jāizmanto veidlapas apakšā esošā poga\"Nosūtīt e-pasta ziņojumu\", lai apliecinātu savus iestatījumus.",
    "Your IP address was resolved as %s" : "IP adrese tika noteikta kā %s",
    "This server has no working internet connection: Multiple endpoints could not be reached. This means that some of the features like mounting external storage, notifications about updates or installation of third-party apps will not work. Accessing files remotely and sending of notification emails might not work, either. Establish a connection from this server to the internet to enjoy all features." : "Šim serverim nav strādājoša savienojuma ar internetu: vairākus galamērķus nevarēja sasniegt. Tas nozīmē, ka dažas no iespējām, piemēram, ārējas krātuves piemontēšana, paziņojumi par atjauninājumiem vai trešo pušu lietotņu uzstādīšana, nedarbosies. Varētu nedarboties arī attālā piekļūšana datnēm paziņojumu e-pasta ziņojumu nosūtīšana. Šim serverim jānodrošina savienojums ar internetu, lai izmantotu visas iespējas.",
    "Disabled" : "Atspējots",
    "PHP does not seem to be setup properly to query system environment variables. The test with getenv(\"PATH\") only returns an empty response." : "PHP nešķiet pareizi uzstādīts lai veiktu sistēmas vides mainīgo vaicājumus. Tests ar getenv(\"PATH\") atgriež tikai tukšu atbildi.",
    "The read-only config has been enabled. This prevents setting some configurations via the web-interface. Furthermore, the file needs to be made writable manually for every update." : "Ir iespējota tikai lasāma konfigurācija. Tas neatļauj iestatīt atsevišķu konfigurāciju tīmekļa saskarnē. Turklāt šī datne pašrocīgi jāpadara par rakstāmu katram atjauninājumam.",
    "Your database does not run with \"READ COMMITTED\" transaction isolation level. This can cause problems when multiple actions are executed in parallel." : "Datubāze nedarbojas ar \"READ COMMITED\" transakciju izolācijas līmeni. Tas var radīt sarežģījumus, kad vienlaicīgi tiek veiktas vairākas darbības.",
    "Nextcloud settings" : "Nextcloud iestatījumi",
    "Enable" : "Aktivēt",
    "None" : "Nav",
    "Allow apps to use the Share API" : "Ļaut programmām izmantot koplietošanas API",
    "Allow resharing" : "Atļaut atkārtotu koplietošanu",
    "Allow sharing with groups" : "Atļaut koplietošanu ar grupām",
    "Restrict users to only share with users in their groups" : "Ierobežot lietotājiem koplietot tikai ar lietotājiem savās grupās",
    "Allow public uploads" : "Atļaut publisko augšupielādi",
    "Enforce password protection" : "Ieviest paroles aizsardzību",
    "Set default expiration date for internal shares" : "Iestatīt noklusējuma beigu datumu iekšējiem koplietojumiem",
    "Enforce expiration date" : "Uzspiest beigu datumu",
    "Privacy settings for sharing" : "Kopīgošanas privātuma iestatījumi",
    "Two-Factor Authentication" : "Divpakāpju pieteikšanās",
    "Limit to groups" : "Ierobežot kopām",
    "Save changes" : "Saglabāt izmaiņas",
    "Update to {update}" : "Atjaunināt uz {update}",
    "Remove" : "Noņemt",
    "Featured" : "Izcelta",
    "Featured apps are developed by and within the community. They offer central functionality and are ready for production use." : "Izceltās lietotnes ir kopienas izstrādātas. Tās sniedz centrālu funkcionalitāte un ir gatavas izmantošanai produkcijā.",
    "Download and enable all" : "Lejupielādēt un iespējot visu",
    "Icon" : "Ikona",
    "Name" : "Nosaukums",
    "Version" : "Versija",
    "Level" : "Līmenis",
    "Actions" : "Darbības",
    "No apps found for your version" : "Tavai versijai netika atrasta neviena lietotne",
    "_%n app has an update available_::_%n apps have an update available_" : ["%n lietotnēm ir pieejams atjauninājums","%n lietotnei ir pieejams atjauninājums","%n lietotnēm ir pieejams atjauninājums"],
    "_Update_::_Update all_" : ["Atjaunināt visas","Atjaunināt","Atjaunināt visas"],
    "Group name" : "Grupas nosaukums",
    "Could not load app discover section" : "Nevarēja ielādēt lietotņu atklāšanas sadaļu",
    "Loading" : "Ielādē",
    "Type" : "Veids",
    "Display Name" : "Attēlojamais vārds",
    "Learn more" : "Uzziniet vairāk",
    "Confirm" : "Apstiprināt",
    "Cancel" : "Atcelt",
    "Description" : "Apraksts",
    "Visit website" : "Apmeklējiet vietni",
    "Admin documentation" : "Pārvaldītāja dokumentācija",
    "Developer documentation" : "Izstrādātāja dokumentācija",
    "Details" : "Informācija",
    "All" : "Visi",
    "No results" : "Nav iznākuma",
    "Update to {version}" : "Atjaunināt uz {version}",
    "Latest updated" : "Pēdējoreiz atjaunināta",
    "Categories" : "Kategorijas",
    "Resources" : "Resursi",
    "Documentation" : "Dokumentācija",
    "Interact" : "Mijiedarboties",
    "Report a bug" : "Ziņot par kļūdu",
    "Rate" : "Vērtēt",
    "Changelog" : "Izmaiņu žurnāls",
    "Google Chrome for Android" : "Google Chrome for Android",
    "{productName} Android app" : "{productName} Android lietotne",
    "This session" : "Šajā sesijā",
    "Device settings" : "Ierīces iestatījumi",
    "Rename" : "Pārdēvēt",
    "Revoke" : "Atsaukt",
    "Device" : "Ierīce",
    "Last activity" : "Pēdējās darbības",
    "Devices & sessions" : "Ierīces un sesijas",
    "Web, desktop and mobile clients currently logged in to your account." : "Tīmekļa, darbvirsmas un viedierīču klienti, kas pašlaik ir pieteikušies Tavā kontā.",
    "App name" : "Lietotnes nosaukums",
    "Create new app password" : "Izveidot jaunu lietotnes paroli",
    "New app password" : "Jauna lietotnes parole",
    "Use the credentials below to configure your app or device. For security reasons this password will only be shown once." : "Zemāk esošie piekļuves dati jāizmanto, lai konfigurētu lietotni vai ierīci. Drošības iemeslu dēļ šī parole tiks parādīta tikai vienu reizi.",
    "Login" : "Pieteikumvārds",
    "Password" : "Parole",
    "Show QR code for mobile apps" : "Parādīt kvadrātkodu tālruņa lietotnēm",
    "Profile" : "Profils",
    "Password confirmation is required" : "Nepieciešams paroles apstiprinājums",
    "Server-side encryption" : "Servera šifrēšana",
    "Enable server-side encryption" : "Ieslēgt servera šifrēšanu",
    "No encryption module loaded, please enable an encryption module in the app menu." : "Nav ielādēts šifrēšanas moduļis, lūdzu, aktivizējiet šifrēšanas moduli lietotņu izvēlnē.",
    "Select default encryption module:" : "Atlasīt noklusējuma šifrēšanas moduli:",
    "Enable encryption" : "Ieslēgt šifrēšanu",
    "Encryption alone does not guarantee security of the system. Please see documentation for more information about how the encryption app works, and the supported use cases." : "Šifrēšana vien negarantē sistēmas drošību. Skatiet dokumentāciju, lai iegūtu papildinformāciju par šifrēšanas lietotnes izmantošanu un atbalstītajiem izmantošanas veidiem.",
    "Be aware that encryption always increases the file size." : "Jāapzinās, ka šifrēšanas vienmēr palielina datnes lielumu.",
    "It is always good to create regular backups of your data, in case of encryption make sure to backup the encryption keys along with your data." : "Vienmēr ir ieteicams regulāri veidot datu rezerves kopijas, un šifrēšanas gadījumā jāpārliecinās, ka līdz ar datiem rezerves kopijas ir izveidotas arī šifrēšanas atslēgām.",
    "This is the final warning: Do you really want to enable encryption?" : "Šis ir pēdējais brīdinājums: vai tiešām iespējot šifrēšanu?",
    "Failed to delete group \"{group}\"" : "Neizdevās izdzēst kopu \"{group}\"",
    "Submit" : "Iesniegt",
    "Rename group" : "Pārdēvēt kopu",
    "Current password" : "Pašreizējā parole",
    "New password" : "Jaunā parole",
    "The file must be a PNG or JPG" : "Datnei jābūt PNG vai JPG",
    "Unable to update date of birth" : "Nevarēja atjaunināt dzimšanas datumu",
    "Enter your date of birth" : "Ievadi savu dzimšanas datumu",
    "You are using {s}{usage}{/s}" : "Tu izmanto {s}{usage}{/s}",
    "You are using {s}{usage}{/s} of {s}{totalSpace}{/s} ({s}{usageRelative}%{/s})" : "Tu izmanto {s}{usage}{/s} no {s}{totalSpace}{/s} ({s}{usageRelative}%{/s})",
    "You are a member of the following groups:" : "Tu esi zemāk uzskaitīto kopu dalībnieks:",
    "This address is not confirmed" : "Šī adrese nav apstiprināta",
    "Primary email for password reset and notifications" : "Primārā e-pasta adrese paroles atjaunošanai un paziņojumiem",
    "No email address set" : "Nav norādīts e-pasts",
    "Help translate" : "Palīdzi tulkot",
    "Unable to update locale" : "Nevarēja atjaunināt lokalizāciju",
    "Locales" : "Lokalizācijas",
    "Week starts on {firstDayOfWeek}" : "Nedēļa sākas {firstDayOfWeek}",
    "No locale set" : "Lokalizācija nav iestatīta",
    "Your phone number" : "Tavs tālruņa numurs",
    "Edit your Profile visibility" : "Labot sava profila redzamību",
    "Your role" : "Tava loma",
    "Your website" : "Tava tīmekļvietne",
    "Add" : "Pievienot",
    "Change" : "Mainīt",
    "Delete" : "Izdzēst",
    "Display name" : "Attēlojamais vārds",
    "Email" : "E-pasts",
    "Quota" : "Kvota",
    "Language" : "Valoda",
    "Add new account" : "Pievienot jaunu kontu",
    "Scroll to load more rows" : "Ritināt, lai ielādētu vairāk rindu",
    "Avatar" : "Profila attēls",
    "Account name" : "Konta nosaukums",
    "Group admin for" : "Kopa \"pārvaldītājs\"",
    "Storage location" : "Krātuves atrašanās vieta",
    "Last login" : "Pēdējā pieteikšanās",
    "Account actions" : "Konta darbības",
    "{size} used" : "Izmantoti {size}",
    "Delete account" : "Izdzēst kontu",
    "In case of lost device or exiting the organization, this can remotely wipe the Nextcloud data from all devices associated with {userid}. Only works if the devices are connected to the internet." : "Pazaudētas ierīces vai apvienības pamešanas gadījumā šis var attālināti notīrīt Nextcloud datus visās ar {userid} saistītajās ierīcēs. Darbojas tikai tad, ja ierīces ir savienotas ar internetu.",
    "Add account to group" : "Pievienot kontu kopai",
    "Done" : "Pabeigts",
    "Edit" : "Labot",
    "Visibility" : "Redzamība",
    "Show last login" : "Rādīt pēdējo autorizāciju",
    "Send email" : "Nosūtīt e-pasta ziņojumu",
    "Send welcome email to new accounts" : "Nosūtīt sasveicināšanās e-pasta ziņojumu jauniem lietotājiem",
    "Default quota" : "Apjoms pēc noklusējuma",
    "Admins" : "Pārvaldītāji",
    "Sending…" : "Sūta …",
    "Email sent" : "E-pasta ziņojums nosūtīts",
    "Location" : "Atrašanās vieta",
    "Profile picture" : "Profila attēls",
    "About" : "Par",
    "Date of birth" : "Dzimšanas datums",
    "Full name" : "Pilns vārds",
    "Phone number" : "Tālruņa numurs",
    "Role" : "Loma",
    "Website" : "Mājaslapa",
    "Locale" : "Lokalizācija",
    "First day of week" : "Pirmā nedēļas diena",
    "Discover" : "Atklāt",
    "Your apps" : "Tavas lietotnes",
    "Active apps" : "Izmantotās lietotnes",
    "Disabled apps" : "Atspējotās lietotnes",
    "Updates" : "Atjauninājumi",
    "App bundles" : "Lietotņu kopumi",
    "Featured apps" : "Izceltās lietotnes",
    "Hide" : "Paslēpt",
    "Download and enable" : "Lejupielādēt un iespējot",
    "Disable" : "Deaktivēt",
    "Unknown" : "Nezināms",
    "Never" : "Nekad",
    "Do you really want to wipe your data from this device?" : "Vai tiešām izdzēst datus šajā ierīcē?",
    "Error" : "Kļūda",
    "Forum" : "Forums",
    "Nextcloud help & privacy resources" : "Nextcloud palīdzība un privātuma līdzekļi",
    "Privacy policy" : "Privātuma politika",
    "SSL" : "SSL",
    "Open documentation" : "Atvērt dokumentāciju",
    "It is important to set up this server to be able to send emails, like for password reset and notifications." : "Ir svarīgi iestatīt šo serveri, lai varētu nosūtīt e-pasta ziņojumus, piemēram, paroles atiestatīšanai un paziņojumus.",
    "Send mode" : "Sūtīšanas metode",
    "Encryption" : "Šifrēšana",
    "From address" : "No adreses",
    "Server address" : "Servera adrese",
    "Port" : "Ports",
    "Authentication" : "Autentifikācija",
    "Authentication required" : "Nepieciešama autentifikācija",
    "Credentials" : "Akreditācijas dati",
    "SMTP Password" : "SMTP parole",
    "Save" : "Saglabāt",
    "Security & setup warnings" : "Drošības un iestatījumu brīdinājumi",
    "All checks passed." : "Visas pārbaudes veiksmīgas.",
    "Developed by the {communityopen}Nextcloud community{linkclose}, the {githubopen}source code{linkclose} is licensed under the {licenseopen}AGPL{linkclose}." : "Izstrādātās {communityopen}Nextcloud kopiena {linkclose},  {githubopen} avota kods {linkclose} licencēts saskaņā ar {licenseopen}AGPL{linkclose}.",
    "Use a second factor besides your password to increase security for your account." : "Vēl viena apliecināšanas līdzekļa izmantošana papildus parolei, lai palielinātu sava konta drošību.",
    "If you use third party applications to connect to Nextcloud, please make sure to create and configure an app password for each before enabling second factor authentication." : "Ja izmanto trešo pušu lietotnes, lai savienotos ar Nextcloud, lūgums ņemt vērā, ka pirms divpakāpju pieteikšanās iespējošanas katrai no tām ir nepieciešams izveidot un izmantot lietotnes paroli.",
    "Your biography" : "Apraksts par sevi",
    "You are using <strong>{usage}</strong>" : "Tu izmanto <strong>{usage}</strong>",
    "You are using <strong>{usage}</strong> of <strong>{totalSpace}</strong> (<strong>{usageRelative}%</strong>)" : "Tu izmanto <strong>{usage}</strong> no <strong>{totalSpace}</strong> (<strong>{usageRelative}%</strong>)"
},
"nplurals=3; plural=(n%10==1 && n%100!=11 ? 0 : n != 0 ? 1 : 2);");
