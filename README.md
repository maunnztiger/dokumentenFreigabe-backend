Beschreibung

Dieses Projekt ist das PHP-basierte Backend REST-API für eine Sicherheitsfreigabe für Dokumenten- und Videocontent. Es werden in diesem Backend Schnittstellen definiert, die JSON-Objekte, XML- und PDF Dateien sowie Video-Streams zustandslos zum entsprechenden Frontend schicken.

Die Parameter, die empfangen werden, werden  auf den jeweiligen HTTP-Request
GET, POST, oder PUT oder DELETE geprüft. Sie entsprechen somit einer einfachen CRUD Anweisung ans Backend. Geprüft werden diese Requests in der adressierten Action im jeweiligen Controller, welcher vom Frontend über eine URL direkt adressiert wird.

Beispiel: Um im Admin-Controller das ONbjekt
{
    var params = {
        pdfName : pdfName
    }

}

an die Action getPDFBinary zu adressieren, wird hier einfach im Ajax-POST-Request die base-URL 'localhost/dokumentFreigabe-backend' mit der URL 'admin/getPDFBinary' ergänzt. Sodann bekommt man Zugriff auf das Objekt, was hier über einen POST Request gesendet wurde, dann im 
{
    $pdfname = isset($_POST['pdfName']);
}


in der adressierten Action getPDFBinary.

Die HTTP-Response erfolgt dann nach der Prüfung bestimmer Bedingungen und meistens, nach Ablauf eines Commands des im Backend implementierten Command Pattern. Das Command-Pattern regelt dann, wenn die Bedingung in einer Kontrollschleife erfolgreich geprüft und erfüllt wurde, "WAS" genau dann passieren soll. Somit lässt sich der Großteil der Geschäftslogik aus dem Controller in den entsprechenden Command verlagern, dern dann letztlich auf eine Model-Klasse zugreift.
Teilweise wurde aus Zeitgründen auf die aufwendige Implementierung eines weiteren Commands verzichtet, sofern die Operation schon kurz im der Controller-Action ausgeführt werden kann.

Das Berechtigungs-system:

Diese Backend-Schnittstelle ist also eine einfach Model-Controller-Struktur, in der ein Command Pattern integriert wurde, um das Berechtigungs-System zu definieren. Die eigentliche Berechtigung wurzelt in der Zugehörigkeit zu einer bestimmten Benutzergruppe: Admin, Employee oder Customer. Diese werden beim Login aus der Datenbank ausgelesen und in einem Objekt gespeichert, damit diese für die jeweilige Session immer wieder ausgelesen werden kann. Je nach Zugehörigkeit zu einer bstimmten Gruppe können dann bestimmte Commands ausgelöst werden, oder eben nicht. 

Bei letzterem wird dann geprüft, ob für den jeweiligen angefragten Content inder Datenbank eine Freigabe durch den Admin erteilt wurde. Der Admin kann im Frontend mit dem Rechtsclick auf ein Objekt in einem Contextmenü den Content für eine spezielle Person freischalten, wobei die ID des Contents und der Person als Fremdschlüssel in eine Tabelle in der Datenbank eingetragen werden. Loggt sich die entsprechende Person dann ein, kann sie diesen, -und nur diesen!, freigegebenen Content dann aufrufen.

Auf diese Weise wurde die Sicherheitsfreigabe für Video- und PDF-Content geregelt.

Die Implementierung:

Zunächst müssen Sie Frontend und Backend in xammp/htdocs speichern. 
Dann müssen sie den 
{
    mysql dump contentfreigabe.sql 
}
 
 in ihr phpmyadmin importieren.

Sofern Sie content aufrufen wollen, müssen Sie diesen in einem entsprechenden Pfad anlegen und diesen Pfad in den entsprechenden Klassen noch anpassen, da die API speziell mit speziell meinem eigenem Content auf meinem Host auf Funktionalität getestet wurde.

Wenn Sie dies getan haben, können sie das Frontend mit 
{
    http://localhost/dokumentFreigabe-frontend 
}


aufrufen und müssten die Startseite mit dem Thumbnail von der Serie NCIS sehen.

Loggen Sie sich dann ein als admin:admin (Administrator) oder jule:jule (Employee), um die jeweilige Funktionalität zu testen.

Viel Vergnügen!