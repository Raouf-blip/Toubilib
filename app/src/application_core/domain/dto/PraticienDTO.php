<?

namespace toubilib\core\domain\dto;

class PraticienDTO {
    public string $id;
    public string $nom;
    public string $prenom;
    public string $ville;
    public string $email;
    public string $specialiteid;

    public function __construct(string $id, string $nom, string $prenom, string $ville, string $email, string $specialiteid)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->ville = $ville;
        $this->email = $email;
        $this->specialiteid = $specialiteid;
    }
}