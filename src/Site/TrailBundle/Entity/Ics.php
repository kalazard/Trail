<?php
namespace Site\TrailBundle\Entity;

class Ics
{
    var $data;
    var $name;
    public function __construct($listeEvenement)
    {
        $tz_utc   = new \DateTimeZone('utc'); 
        
        $this->name = "TrailCalendrier";
        $this->data = "BEGIN:VCALENDAR".chr(10);
        $this->data .= "VERSION:2.0".chr(10);
        $this->data .= "PRODID:-//Acrobatt Gr6//Calendrier Trail".chr(10);        
        $this->data .= "CALSCALE:GREGORIAN".chr(10);
        $this->data .= "METHOD:REQUEST".chr(10);

        foreach($listeEvenement as $evenement)
        {
            foreach($evenement as $monEvent)
            {
                $dateDebutUTC = $monEvent->getEvenement()->getDateDebut();
                //$dateDebutUTC = new \DateTime($evenement["debut"]);
                $dateDebutUTC->setTimezone($tz_utc);
                $dateFinUTC = $monEvent->getEvenement()->getDateFin();
                //$dateFinUTC = new \DateTime($evenement["fin"]);
                $dateFinUTC->setTimezone($tz_utc);

                $this->data .= "BEGIN:VEVENT".chr(10);
                $this->data .= "DTSTART:".$dateDebutUTC->format("Ymd\THis\Z").chr(10);
                $this->data .= "DTEND:".$dateFinUTC->format("Ymd\THis\Z").chr(10);
                
                if(method_exists($monEvent, "getLieuRendezVous"))
                {
                    $this->data .= "LOCATION:".$monEvent->getLieuRendezVous()->getTitre().chr(10);
                }                
                //$this->data .= "LOCATION:".$evenement["lieu"].chr(10);
                
                $this->data .= "SEQUENCE:0".chr(10);
                $this->data .= "UID:1:trailEvenement".$monEvent->getEvenement()->getId()."&".$monEvent->getId().chr(10);
                //$this->data .= "UID:1:trailEvenement".$evenement["id"].chr(10);
                $this->data .= "DTSTAMP:".date("Ymd\THis\Z").chr(10);
                $this->data .= "SUMMARY:".$monEvent->getEvenement()->getTitre().chr(10);
                //$this->data .= "SUMMARY:".$evenement["titre"].chr(10);
                $this->data .= "DESCRIPTION:".$monEvent->getEvenement()->getDescription().chr(10);
                //$this->data .= "DESCRIPTION:".$evenement["description"].chr(10);
                //$this->data .= "PRIORITY:1".chr(10);
                //$this->data .= "CLASS:PUBLIC".chr(10);
                $this->data .= "END:VEVENT".chr(10);
            } 
        }
        
        $this->data .= "END:VCALENDAR";
    }
    function save()
    {
        file_put_contents($this->name.".ics",$this->data);
    }
    function show()
    {
        header("Content-type:text/calendar");
        header('Content-Disposition: attachment; filename="'.$this->name.'.ics"');
        Header('Content-Length: '.strlen($this->data));
        Header('Connection: close');
        echo $this->data;
    }
}
?>