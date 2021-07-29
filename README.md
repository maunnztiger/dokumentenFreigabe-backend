# Beschreibung

Dieses Projekt ist das PHP-basierte Backend REST-API für eine Sicherheitsfreigabe für Dokumenten- und Videocontent. Es werden in diesem Backend Schnittstellen definiert, die JSON-Objekte, XML- und PDF- und Word Dateien sowie Video-Streams zustandslos zum entsprechenden Frontend schicken.

Die Parameter, die empfangen werden, werden  auf den jeweiligen HTTP-Request
GET, POST, oder PUT oder DELETE geprüft. Sie entsprechen somit einer einfachen CRUD Anweisung ans Backend. Geprüft werden diese Requests in der adressierten Action im jeweiligen Controller, welcher vom Frontend über eine URL direkt adressiert wird.

Beispiel: Um im Frontend das Objekt
{
    var params = {
        pdfName : pdfName
    }

}

an die Backend-Action getPDFBinary zu adressieren, wird hier einfach im Ajax-POST-Request die base-URL 'localhost/dokumentFreigabe-backend' mit der URL 'admin/getPDFBinary' ergänzt. Sodann bekommt man Zugriff auf das Objekt, was hier über einen POST Request gesendet wurde, dann im 
{
    $pdfname = isset($_POST['pdfName']);
}


in der adressierten Action getPDFBinary.

Die HTTP-Response erfolgt dann nach der Prüfung bestimmer Bedingungen und meistens, nach Ablauf eines Commands des im Backend implementierten Command Pattern. Das Command-Pattern regelt dann, wenn die Bedingung in einer Kontrollschleife erfolgreich geprüft und erfüllt wurde, "WAS" genau dann passieren soll. Somit lässt sich der Großteil der Geschäftslogik aus dem Controller in den entsprechenden Command verlagern, der dann letztlich auf eine Model-Klasse zugreift.
Teilweise wurde aus Zeitgründen auf die aufwendige Implementierung eines weiteren Commands verzichtet, sofern die Operation schon kurz in der Controller-Action ausgeführt werden kann.

# Das Berechtigungs-system:

Diese Backend-Schnittstelle ist also eine einfach Model-Controller-Struktur. Die eigentliche Berechtigung wurzelt in der Zugehörigkeit zu einer bestimmten Benutzergruppe: Admin, Employee oder Customer. Diese werden beim Login aus der Datenbank ausgelesen und in einem Objekt gespeichert, damit diese für die jeweilige Session immer wieder ausgelesen werden kann. Je nach Zugehörigkeit zu einer bstimmten Gruppe können dann bestimmte Commands ausgelöst werden, oder eben nicht. 

Für jeden Content wird beim Aufrufen geprüft, ob für ihn in der Datenbank eine Freigabe durch den Admin erteilt wurde. Der Admin kann im Frontend mit dem Rechtsclick auf ein Objekt in einem Contextmenü den Content für eine spezielle Person freischalten, wobei die ID des Contents und der Person als Fremdschlüssel in eine Tabelle in der Datenbank eingetragen werden. Loggt sich die entsprechende Person dann ein, kann sie diesen, -und nur diesen!, freigegebenen Content dann aufrufen.

Auf diese Weise wurde die Sicherheitsfreigabe für Content geregelt.

# Das Command Pattern:

Der Grund für die Entscheidung für das Command-Pattern lag in der einfacherenn Wartbarkeit der Business-Logik. Das Command-Pattern ist an sich sehr aufwendig zu implementieren und dies muss vorher gut überlegt werden, ob das Zeitfenster für das Projekt dies überhaupt zulässt. Einmal implementiert lässt es sich aber sehr leicht erweitern und warten und kann helfen, jeden einzelnen Use-Case gezielt anzupassen.

Das hier verwendete Command-Pattern habe ich so umgeschrieben, dass ich zuerst eine Data-Storage-Klasse geschrieben habe, die Klasse CommanContext:

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

Diese Klasse hat eine Array-Property. Als key oder Index für diesen Value Speicher kann jedweder String dienen, somit auch der string 'action'. Wird ein String 'action'
mit einem Value, also ein Command-Name in diesem Array gesetzt, kann eine andere Klasse, in diesem Fall der Invoker mit der Getter-Methode dieses Command auslesen und prüfen, ob dieses Command existiert und ob es auführbar ist:

{
    use dokumentenFreigabe\Controller\command\CommandContext;
    use dokumentenFreigabe\Controller\command\CommandFactory;

    class Invoker extends \Exception {

        private $context;

        public function __construct() {
            $this->context = new CommandContext();
        }

        public function getContext(): CommandContext {
            return $this->context;
        }

        public function process() {
            $action = $this->context->get('action');
            $action = (is_null($action)) ? "default" : $action;
            $cmd = CommandFactory::getCommand($action);

            if (!$cmd->execute($this->context)) {
                throw new \Exception("Command cannot been processed");
                return false;
            }

        }   

    }
}

Die Methode process prüft an dieser Stelle nur, ob das jeweilige Command ausführbar ist und den Boolschen Wert true oder false returned. Wird true returned, ist der Command erfolgreich durchgelaufen, andernfalls wird eine Exception ausgegeben und false returned: 

Kann das Objekt die execute-Methode ausführen, so dass sie true returned, läuft der Command durch und es können in der Command-Klasse weitere Parameter in die Array-Property der Command-Context-Klasse geschrieben werden, die dann im jeweiligen Controller ausgelesen und deren Values ans Frontend gerouted werden können.

Ein einfaches statisches Factory Pattern erzeugt woanders ein Objekt der jeweiligen Command-Klasse:
{
    class CommandFactory {

        private static $dir = 'command';

        public static function getCommand(string $action = 'Default'): Command {
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

Die jeweilige Command-Klasse ist der Receiver, der Invoker tritt nur ein einziges Mal als Model-Klasse auf und initiiert dann die aktuelle Command-Klasse, die vorher im Command-Context als Action im Context-Array gespeichert wurde: 

Beispiel:
{
            $invoker = Application::getModel('Invoker');
            $context = $invoker->getContext();
            $context->addParam('action', 'changePermission');
            $context->addParam('userName', $_POST['userName']);
            $context->addParam('videoName', $_POST['videoName']);
            $invoker->process();
}

Auf diese Weise ist das, was passieren soll, und durch das Command Pattern geregelt wird, sehr leicht zu skalieren und zu warten.


# Data-Layer

Ein zur Zeit kontrovers diskutierter Gegenstand im Software-Design ist es, die Model-View-Controller-Struktur um eine Schicht zu erweitern und die Datenmanipulations-Logik komplett aus dem Model-Layer herauszuholen und in eine Data-Layer Schicht, bspw. in ein Data-Mapper oder Active Record zu packen. Das ist nicht wirklich neu, jedoch wird die Diskussion in letzter Zeit dadurch verschärft, das Web-Applikationen immer mehr Datensätze aus Datensystemen lesen und an die GUI senden müssen: Freundeslisten und Likes bei social media, Wunschlisten und Bestellhistorien bei Onlineshops etc. pp. Dies macht es im Falle wachsender Software-Architekturen notwendig, geschuldet der notwendigen Wartbarkeit einer Apllikation, die Logik einer Datenbankmanipulation in ein Data-Layer so auszulagern, 
dass der entsprechenden Klasse im Model, die bisher die Datenbank-Operation vorgenommen hat, nur noch Parameter übergeben werden und die eigentliche Operations-Logik im Quellcode dann woanders, nämlich Data-Layer stattfindet.
Das hat einen ganz entscheidenden Vorteil, der genau wie beim Command-Pattern der größte Nachteil ist, sich aber an einer entscheidenden Frage orientiert:

"Wie lange soll mit einer Software Geld verdient werden und wie einfacher muss die Wartbarkeit dieser Software sein im Gegensatz zum Aufwand der Implementierung?"

Eine einfache Website ist schnell implementiert und kann ohne viel Aufwand automitisiert getestet werden. Eine längerfristige Apllikation indes, die eine längere Laufzeit und einen größeren Umfang hat, kann es sinnvoll sein, eine aufwendigere Implementierung und eine höhere Abstraktion der einzelnen Schichten einer einfachen Implementierung vorzuziehen. Das hat folgende Vorteile:
    
    - die Architektur ist schneller und einfacher zu warten
    - sie ist einfacher zu skalieren
    - sie ist langlebiger, was es dem Kunden ermöglicht, monetär mittel und    langfristig zu planen

Die Nachteile liegen natürlich auf der Hand:
    
    - sie ist teurer, weil in der Implementierung aufwendiger
    - unter hohem Zeitdruck entstanden ist sie anfälliger für Fehler und deshalb kritischer zu testen, was nicht immer möglich ist.
    - sie stellt hohe Performance-Ansprüche und benötigt ggfs zusätzliche Last-Tests.

Dennoch ist es sinnvoll, eine entsprechende Data-layer-Schicht zu implementieren, denn einmal implementiert, kann sie, wenn sie in clean code geschrieben ist, immer wieder neu verwendet werden. 

Aktuell befindet sich die Daten-Manipulations-Schicht noch in der Entwicklung, erste Ansätze sind bereits vorhanden.