# Beschreibung

Dieses Projekt ist das PHP-basierte Backend REST-API für eine Sicherheitsfreigabe für Dokumenten- und Videocontent. Es werden in diesem Backend Schnittstellen definiert, die JSON-Objekte, XML- und PDF Dateien sowie Video-Streams zustandslos zum entsprechenden Frontend schicken.

Die Parameter, die empfangen werden, werden  auf den jeweiligen HTTP-Request
GET, POST, oder PUT oder DELETE geprüft. Sie entsprechen somit einer einfachen CRUD Anweisung ans Backend. Geprüft werden diese Requests in der adressierten Action im jeweiligen Controller, welcher vom Frontend über eine URL direkt adressiert wird.

Beispiel: Um im Admin-Controller das Objekt
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

# Das Berechtigungs-system:

Diese Backend-Schnittstelle ist also eine einfach Model-Controller-Struktur, in der ein Command Pattern integriert wurde, um das Berechtigungs-System zu definieren. Die eigentliche Berechtigung wurzelt in der Zugehörigkeit zu einer bestimmten Benutzergruppe: Admin, Employee oder Customer. Diese werden beim Login aus der Datenbank ausgelesen und in einem Objekt gespeichert, damit diese für die jeweilige Session immer wieder ausgelesen werden kann. Je nach Zugehörigkeit zu einer bstimmten Gruppe können dann bestimmte Commands ausgelöst werden, oder eben nicht. 

Bei letzterem wird dann geprüft, ob für den jeweiligen angefragten Content inder Datenbank eine Freigabe durch den Admin erteilt wurde. Der Admin kann im Frontend mit dem Rechtsclick auf ein Objekt in einem Contextmenü den Content für eine spezielle Person freischalten, wobei die ID des Contents und der Person als Fremdschlüssel in eine Tabelle in der Datenbank eingetragen werden. Loggt sich die entsprechende Person dann ein, kann sie diesen, -und nur diesen!, freigegebenen Content dann aufrufen.

Auf diese Weise wurde die Sicherheitsfreigabe für Video- und PDF-Content geregelt.

# Das Command Pattern:

Das hier verwendete Befehlsmuster habe ich so umgeschrieben, das eine Array-Property einer Command-Contextklasse solange als Datenspeicher für alle Parameter dient, wie das jeweilige Command läuft:
 {
     class CommandContext {

        private $params = array();
        private $error = "";

        public function __construct() {
            $this->params = $_REQUEST;
        }

        public function addParam(string $key, $val){
            $this->params[$key] = $val;
        }

        public function get(string $key) {
            if (isset($this->params[$key])) {
                return $this->params[$key];
            }
            return null;
        }
    }

}




Ein einfaches statisches Factory Pattern erzeugt ein Objekt der jeweiligen Command-Klasse und gleichzeitig ein Objekt der Command-Contextklasse:
{
    class CommandFactory
{

    private static $dir = 'command';

    public static function getCommand(string $action = 'Default'): Command
    {
        if (preg_match('/\W/', $action)) {
            throw new Exception("illegal character found");
        }

        $class = __NAMESPACE__ . DIRECTORY_SEPARATOR . UCFirst(strtolower($action)) . "Command";

        if (!class_exists($class)) {
            throw new CommandNotFoundException("no $class class located");
        }

        $cmd = new $class;

        return $cmd;
    }
}
}



Die jeweilige Command-Klasse ist der Receiver, der Invoker tritt nur ein einziges Mal abstrahiert in als Model-Klasse auf und initiiert dann die aktuelle Command-Klasse, die vorher im Command-Context als Action im Context-Array gespeichert wurde: 

Beispiel:
{
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'changePermission');
            $context->addParam('userName', $_POST['userName']);
            $context->addParam('videoName', $_POST['videoName']);
            $invoker->process();
}

Die Methode process prüft an dieser Stelle nur, ob das jeweilige Command ausführbar ist und den Boolschen Wert true oder false returned. Wird true returned, ist der Command erfolgreich durchgelaufen, andernfalls wird eine Exception ausgegeben und false returned: 
{
    public function process()
    {
        $action = $this->context->get('action');
        $action = (is_null($action)) ? "default" : $action;
        $cmd = CommandFactory::getCommand($action);

        if (!$cmd->execute($this->context)) {
            throw new \Exception("Command cannot been processed");
            return false;
        }

    }
}

Auf diese Weise ist das Command-Pattern sehr leicht zu skalieren und kann, einmal implementiert. mit relativ wenig Aufwand sehr einfach um entsprechende Commands erweitert werden, ohne das komplett einmal die gesamte Pattern-Struktur immer wieder neu geschrieben werden muss.
# Die Implementierung:

Zunächst müssen Sie Frontend und Backend in xammp/htdocs speichern. 
Dann müssen sie den 
{
    mysql dump contentfreigabe.sql 
}
 
 in ihr phpmyadmin importieren.

Sofern Sie content aufrufen wollen, müssen Sie diesen in einem entsprechenden Pfad anlegen und diesen Pfad in den entsprechenden Klassen noch anpassen, da die API speziell mit speziell meinem eigenem Content auf meinem Host auf Funktionalität getestet wurde. 

Wenn Sie Video-Content aufrufen wollen, müssen Sie noch ein Thumbnail von dem Video erstellen - das geht am besten mit ffmpeg und dieses in dem entsprechenden img-Folder speichern, damit das Video auch korrekt angezeigt wird. PHP braucht unter Umständen zu lange, um beim Laden der Page mit ffmpeg erst ein Thumbnail zu stellen und beim Lden der Page ans Frontend zu routen. Daher muss dies vorher passieren, was die Applikation natürlich sehr content-abhängig macht.

Wenn Sie dies getan haben, können sie das Frontend mit 
{
    http://localhost/dokumentFreigabe-frontend 
}


aufrufen und müssten die Startseite mit dem Thumbnail von der Serie NCIS sehen.

Loggen Sie sich dann ein als admin:admin (Administrator) oder jule:jule (Employee), um die jeweilige Funktionalität zu testen.

Viel Vergnügen!