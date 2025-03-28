<?php

namespace App\Livewire;

use App\Services\CandidateService;
use App\Models\VIP\{Skill, Training, PostulationOffer};
use Livewire\{Component, WithFileUploads};
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\SDM\{Country, Province, MunicipalDistrict, Section, Municipality, Sector, Language, Nationality};

class PostulationForm extends Component
{
    use WithFileUploads, LivewireAlert;

    protected $listeners = ['goToStep' => 'goToStep', 'fileUploaded'];

    public $step = "personal-information";

    public $steps = ['personal-information' => 'skills', 'skills' => 'experiences', 'experiences' => 'postulation', 'postulation' => 'submit'];

    public $personal_informationName, $personal_informationLastname, $personal_informationBirthdate, $personal_informationEmail,
    $personal_informationPhone, $personal_informationNumDocument, $curriculum, $terms;

    public $MaxLanguageForms = 5, $MaxSkillsForms = 3, $MaxExperienceForms = 5, $MaxReferenceForms = 5, $MaxPostulationForms = 5;

    public $LanguageForms = [1 => ''], $SkillsForms = [1 => ''], $ExperienceForms = [1 => ''], $ReferenceForms = [1 => ''], $PostulationForms = [1 => ''];

    public $personal_informationNumDocumentType = 'cedula-input';

    public $selectedProvince, $selectedMunicipality, $selectedDistrict, $selectedSection, $selectedSector;

    protected function rules()
    {
        return [
            'personal-information' => [
               'personal_informationName' => ['required', 'string', 'max:255'],
                'personal_informationLastname' => ['required', 'string', 'max:255'],
                'personal_informationBirthdate' => ['required'],
                'personal_informationNumDocument' => ['required'],
                'personal_informationEmail' => ['required', 'email'],
                'personal_informationPhone' => ['required'],
                'curriculum' => ['required']
            ],
            'skills' => [
                'curriculum' => 'nullable',
            ],
            'experiences' => [
                'curriculum' => 'nullable',
            ],
            'postulation' => [
                'curriculum' => 'nullable'
            ]
        ][$this->step];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function fileUploaded($curriculum)
    {
        $this->curriculum = $curriculum;
    }

    public function goToBack($step)
    {
        foreach ($this->steps as $key => $value) {
            if ($value === $step) {
                $this->step = $key;
                break;
            }
        }
    }

    public function addForm($form, $reloadScript = false)
    {
        if(count($this->$form) < $this->{"Max$form"}){
            $this->$form[] = '';
            if($reloadScript) $this->reloadScript();
        }else{
            $this->alert('warning', 'Ya no se pueden agregar más.', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
                'showConfirmButton' => false,
                'onConfirmed' => '',
                'text' => '',
                'showCancelButton' => false,
                'onDismissed' => '',
                'showDenyButton' => false,
                'onDenied' => '',
                'width' => '',
                'timerProgressBar' => true,
            ]);
        }
    }

    public function removeForm($form, $index)
    {
        unset($this->$form[$index]);

        $reindexedArray = [];

        $nextIndex = 1;
        foreach ($this->$form as $item) {
            $reindexedArray[$nextIndex] = $item;
            $nextIndex++;
        }

        $this->$form = $reindexedArray;
    }

    public function reloadScript()
    {
        $this->dispatch('reloadScript');
    }

    private function alertToast($type, $message){
        $this->alert($type, $message, [
            'position' => 'top-end',
            'timer' => 4000,
            'toast' => true,
            'showConfirmButton' => false,
            'onConfirmed' => '',
            'text' => '',
            'showCancelButton' => false,
            'onDismissed' => '',
            'showDenyButton' => false,
            'onDenied' => '',
            'width' => '',
            'timerProgressBar' => true,
        ]);
    }

    public function submit()
    {
        try {
            if($this->steps[$this->step] === 'submit'){
                if($this->terms){
                    return $this->dispatch('submit');
                }
               return $this->alertToast('warning', 'Debes aceptar los terminos y condiciones.');
            }
            $this->validate();
            $this->step = $this->steps[$this->step];
        } catch (\Throwable $th) {
            $this->alertToast('warning', 'Existen campos que son necesarios para enviar la solicitud.');
            $this->validate();
        }
    }

    public function mount()
    {
        if(auth()->check()) {
            $profile = (new CandidateService())->profile();
            $this->personal_informationName = $profile['NOM'];
            $this->personal_informationLastname = $profile['APE1'];
            $this->personal_informationBirthdate = convert_date($profile['FECNTO']);
            $this->personal_informationEmail = $profile['EMAIL'];
            $this->personal_informationNumDocument = $profile['CEDULA'];
            $this->personal_informationPhone = $profile['MOVIL'];

            for ($i=1; $i < count($profile['candidateLanguages']); $i++) {
               $this->LanguageForms[] = [$i => ''];
            }

            for ($i=1; $i < count($profile['candidateExperiences']); $i++) {
               $this->ExperienceForms[] = [$i => ''];
            }

            for ($i=1; $i < count($profile['candidateReferences']); $i++) {
               $this->ReferenceForms[] = [$i => ''];
            }

            $this->selectedProvince = json_encode(Province::where(['PAIS' => 'DO', 'COD' => get_array_value($profile, 'CODPRO')])->first());
            $selectedProvince = json_decode($this->selectedProvince);
            $this->selectedMunicipality = json_encode(Municipality::where(['PAIS' => $selectedProvince?->PAIS, 'PRO' => intval($selectedProvince?->COD), 'COD' => get_array_value($profile, 'CODMUN')])->first());
            $selectedMunicipality = json_decode($this->selectedMunicipality);
            $this->selectedDistrict = json_encode(MunicipalDistrict::where(['PAIS' => $selectedMunicipality?->PAIS, 'PRO' => intval($selectedProvince?->COD), 'MUN' => intval($selectedMunicipality?->COD), 'COD' => get_array_value($profile, 'CODDIS')])->first());
            $selectedDistrict = json_decode($this->selectedDistrict);
            $this->selectedSection = json_encode(Section::where('PAIS', $selectedDistrict?->PAIS)->where(['PRO' => intval($selectedProvince?->COD), 'MUN' => intval($selectedMunicipality?->COD), 'DIS' => intval($selectedDistrict?->COD), 'COD' => get_array_value($profile, 'CODSEC')])->first());
            $selectedSection = json_decode($this->selectedSection);
            $this->selectedSector = json_encode(Sector::where('PAIS', $selectedDistrict?->PAIS)->where(['PRO' => intval($selectedProvince?->COD), 'MUN' => intval($selectedMunicipality?->COD), 'DIS' => intval($selectedDistrict?->COD), 'SECCIO' => intval($selectedSection?->COD), 'COD' => get_array_value($profile, 'SECTOR')])->first());
        }
    }

    public function render()
    {
        $cod = request('cod');

        $postulationOffers = PostulationOffer::whereIn('SITOFE', [2, 3, 4])->select('COD', 'NOM')->get()->toArray();

        $this->MaxPostulationForms = count($postulationOffers);

        $provinces = Province::where('PAIS', 'DO')->orderBy('NOM')->get();

        $languages = Language::orderBy('NOM')->get();

        $skills = Skill::get();

        $trainings = Training::get();

        $countries = Country::select('COD', 'NOM')->orderBy('NOM')->get();

        $nationalities = Nationality::select('COD', 'NOM')->orderBy('NOM')->get();

        if(normalize_value($this->selectedProvince)){
            $selectedProvince = json_decode($this->selectedProvince);
            $municipalities = Municipality::where('PAIS', $selectedProvince->PAIS)->where('PRO', intval($selectedProvince->COD))->orderBy('NOM')->get();
        }else{
            $municipalities = [];
        }

        if(normalize_value($this->selectedMunicipality) && normalize_value($this->selectedProvince)){
            $selectedMunicipality = json_decode($this->selectedMunicipality);
            $districts = MunicipalDistrict::where('PAIS', $selectedMunicipality->PAIS)->where('PRO', intval($selectedProvince->COD))->where('MUN', intval($selectedMunicipality->COD))->orderBy('NOM')->get();
        }else{
            $districts = [];
        }

        if(normalize_value($this->selectedDistrict) && normalize_value($this->selectedMunicipality) && normalize_value($this->selectedProvince)){
            $selectedDistrict = json_decode($this->selectedDistrict);
            $sections = Section::where('PAIS', $selectedDistrict->PAIS)->where('PRO', intval($selectedProvince->COD))->where('MUN', intval($selectedMunicipality->COD))->where('DIS', intval($selectedDistrict->COD))->orderBy('NOM')->get();
        }else{
            $sections = [];
        }

        if(normalize_value($this->selectedSection) && normalize_value($this->selectedDistrict) && normalize_value($this->selectedMunicipality) && normalize_value($this->selectedProvince)){
            $selectedSection = json_decode($this->selectedSection);
            $sectors = Sector::where('PAIS', $selectedDistrict->PAIS)->where('PRO', intval($selectedProvince->COD))->where('MUN', intval($selectedMunicipality->COD))->where('DIS', intval($selectedDistrict->COD))->where('SECCIO', intval($selectedSection->COD))->orderBy('NOM')->get();
        }else{
            $sectors = [];
        }

        $profile = null;

        if(auth()->check()) $profile = (new CandidateService())->profile();

        return view('livewire.postulation-form', compact('provinces', 'countries', 'municipalities', 'districts', 'sections', 'sectors', 'languages', 'skills', 'trainings', 'nationalities', 'cod', 'profile', 'postulationOffers'));
    }
}
