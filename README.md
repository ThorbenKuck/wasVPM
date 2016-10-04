# wasVPM

## English disclaimer
####*What is the wasVPM*

The VPM is a website, created on the base of WAS-Framework (WebApplicationSystem).
It is used, to automate monitors, to reduce power consumption. Further more this project provides you
with many features like an automated time-schedule.

####*Why is this project not fully translated to english?*

This project was created on base of the WAS. 
~~As soon as i get this up and running in github, i will link this project here.~~ You find it [here](https://github.com/ThorbenKuck/WAS).
But this project was original created for a german school and i am (at the moment)
way to busy with the university. I am sorry for that and as soon as i have time, i will correct that!

##Aktuelle Version

__BETA v.0.2__

## VPM

VPM steht für Vertretungsplan-Monitor-Steuerung und ist eine Website für die Automatisierung eben jenen Monitoren.
Ursprünglich wurde dies entwickelt um große, hoch hängende Monitore über das Internet und über Zeitpläne
steuern zu können. Mit etwas arbeit kann dies allerding auch genutzt werden um ein beliebig großes System
zu automatisisren.

Die Website ist auf Basis von AJAX mit hilfe von JQuerry und viel PHP entwickelt worden.
Dabei liegt das haupt-augenmerk jedoch auf PHP. So fungiert JQuerry lediglich als einfachere Möglichkeit,
Dynamisch PHP nach zu laden. Des Weiteren basiert der Kern auf dem [WAS-Framework (dem WebApplicationSystem-Framework)](https://github.com/ThorbenKuck/WAS)
mit dem standard socket. ~~Leider habe ich noch keine Zeit gefunden, dieses auf GitHub hoch zu laden, 
aber sobald ich Zeit habe, werde ich das nach holen.~~

## Zu den packages und mods

Für die Monitorsteuerung an sich sind nur je 1 package und mod notwendig, allerdings gibt es für sicherere und
angenehmere Bedienung einige mehr.

 * Mods
   * infoboxes.mod
   
     Ein kleiner Mod, um an zu zeigen, ob ein Nutzer eingeloggt ist, oder nicht, sowie als wer er eingeloggt ist
     und mit welchen Rechten er agiert.
   
   * vpm.mod
   
     Der vpm.mod hat 3 große Aufgaben. Er beinhaltet den Zeitplan, stellt die Berechnungs-routine für
     die Berechnung von Feiertagen (an Ostern orientiert) und stellt eine Schnittstelle für einen Crawler,
     welcher durch Cronjob o.ä. regelmäßig aufgerufen werden sollte und die Steckdose(n), informiert über
     den theoretischen Zustand der Monitore
   
 * Packages
   * password.package
   
     Kompatibilitäts-package für ältere PHP versionen, damit diese die password_hash und password_verify
     "Methoden" nutzen können.
   
   * users.package
   
     Dieses Package nutzt die pseudo functions des sockets und kreiert eine sicherere und Datenbank-basierte
     Methode um logins und anders zu managen
   
   * vpm.package
   
     Stellt Methoden zur bearbeitung des Zeitplanes bereit und steht in direkter Abhängigkeit zu dem
     vpm.mod 

##Vorraussetung

Da das WAS-System auf PHP7 basiert wird auch PHP7 vorrausgesetzt, damit das gsamt System funktioniert.
Auch ein Mysql-Server wird vorrausgesetzt, da das usermanagement andernfalls fehler werfen wird.
Des weitern werden die folgenden Erweiterungen benötigt:
* PHP-Curl
* PHP-Mysql

##Installation

Das Projekt kann "einfach" in (hier mal ein) apache2-server geworfen werden. Nehmen wir an, der apache2-server
hat seinen root-path in /var/www/html/.

Als erstes erstellen wir einen neuen unterorder (sagen wir vpm/) und ziehen das Projekt dort hinein.

Dann müssen wir die Datenbank zum laufen bekommen. Dazu befindet sich in packages/users.package
ein *.sql skript, welches einfach in die Datenbank integriert werden kann.

**ACHTUNG**

In dem *.sql skript ist ein test-nutzer integriert mit 
* nutzername = test
* password = test

und mit **root** rechten (permission=9999)! Dieser sollte gelöscht werden, sollte später geplant sein,
dieses tatsächlich zu nutzen!

Sollte es erwünscht sein, dass der aktuelle Zustand automatisch an das Endgerät geschickt wird,
müssen wir noch ein paar dinge tun.

0. Stell sicher, dass dein Endgerät auf http-get-request entsprechend reagiert.

1. Öffne die Konfiguration des vpm.mod (mods/vpm.mod/config.php) und ändere die letzte Konstante
auf die IP-Adresse des Endgerätes.

2. Öffne den Crawler (mods/vpm.mod/connect/index.php) und suche die Zeile
<code>$response = send_request($ip, ...</code>. Die Parameter, welche bei dem get-request übergeben
werden, sind das 2. Argument dieses funktions-aufrufes. Du kannst das Array löschen und deine eigenen
einfügen.

*Bitte behalte im Hinterkopf, dass die crawler-config (mods/vpm.mod/connect/connect_config.json) von
dem WAS gesetzt wird. Änderungen hier, haben keine Auswirkung auf das Verhalten des Crawlers*

Die ausgaben des Crawlers mögen etwas redundant erscheinen, jedoch empfehle ich einen Cronjob mit
dem folgenden command ein zu pflegen:

<code>/usr/bin/curl -o /var/www/html/backup/last_vpmoutput.html http://127.0.0.1/was/mods/vpm.mod/connect/</code>

Dadurch wird die Ausgabe in eine eigene HTML-Datei dumped und man kann leicht Fehler finden, wie z.B:
* Probleme mit zugriffs-rechten.
* Falsch gelesener Zeit-Plan
* Nicht korrekt geladener socket
* usw...

Natürlich muss man sicherstellen das der Pfad hinter -o ein existierender ist. Es ist außerdem noch
möglich, über dass, von dem WAS mitgelieferte Debug-System alles an zu schauen, jedoch ist die etwas
umständlich.

##Support

Das System wurde unter Linux entwickelt. Für andere Systeme kann ich nicht garantieren, dass dies 
funktioniert. Ich sehe allerding (abgesehen von dem Cronjob und curl aufruf) kein Problem, warum
dies nicht klappen sollte.

##TODO

Viele Sachen sind noch nicht ganz fertig. Hier findet sich eine Liste von TODO's:
* Umstellung der user.function.php auf Nutzung der DBManager klasse.
  * nicht nutzbar sind z.B. die Users\get_user_permissions())
* Fertigstellung der noch nicht angefassten Users\Methoden
  * create_new_user()
  * delete_user()
  * change_permissions()
  * login_locked() 
  * lock_login()
  * unlock_login()
* Eine verständliche Beschreibung für die User-Permissions schreiben
* Eine verständliche Beschreibung für die funktionalitäten schreiben
* Eine verständliche Beschreibung für die Nutzung schreiben
* Mehrere inkonsitenzen beseitigen
* Bugs-jagen und fixen
* Unit-Test's
* Doku (wie immer)
